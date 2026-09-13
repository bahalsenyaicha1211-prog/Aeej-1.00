<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());
            RateLimiter::hit($this->emailThrottleKey(), 60);

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        RateLimiter::clear($this->emailThrottleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * Deux verrous distincts : l'un par couple email+IP (5 essais, le
     * comportement d'origine), l'autre par email seul, quelle que soit
     * l'IP (10 essais). Le premier est contournable si l'IP perçue peut
     * être falsifiée via les en-têtes X-Forwarded-* (l'application fait
     * confiance à tous les proxys — nécessaire derrière l'hébergeur) ;
     * le second empêche malgré tout de forcer un compte précis en
     * changeant d'IP à chaque tentative.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        $limited = RateLimiter::tooManyAttempts($this->throttleKey(), 5)
            ? $this->throttleKey()
            : (RateLimiter::tooManyAttempts($this->emailThrottleKey(), 10) ? $this->emailThrottleKey() : null);

        if ($limited === null) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($limited);

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }

    /**
     * Verrou secondaire, indépendant de l'IP perçue.
     */
    public function emailThrottleKey(): string
    {
        return 'email-only:'.Str::transliterate(Str::lower($this->string('email')));
    }
}
