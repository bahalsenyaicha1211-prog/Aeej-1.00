<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Un clavier mobile peut modifier la casse du champ e-mail (majuscule
        // automatique) et faire échouer la comparaison exacte du jeton.
        $request->merge(['email' => Str::lower(trim($request->string('email')))]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                $user->forceFill([
                    'password' => $request->password,
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));

                // Ce même écran sert à la fois au nouveau membre qui définit
                // son mot de passe initial et à l'utilisateur qui a oublié le
                // sien. Dans le premier cas, son e-mail n'a encore jamais été
                // vérifié : on lui envoie donc automatiquement le mail de
                // vérification ici, sans attendre qu'il clique sur
                // « Renvoyer l'e-mail de vérification » une fois connecté.
                if (! $user->hasVerifiedEmail()) {
                    $user->sendEmailVerificationNotification();
                }
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            $this->journaliserEchec($request, $status);

            return back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
        }

        return redirect()->route('login')->with('status', __($status));
    }

    /**
     * Diagnostic temporaire : le message "jeton invalide" est générique et ne
     * dit pas si le jeton est absent, expiré, ou remplacé par un plus récent.
     * On consigne l'état réel de la table pour identifier la vraie cause.
     */
    private function journaliserEchec(Request $request, string $status): void
    {
        $ligne = DB::table('password_reset_tokens')
            ->where('email', $request->input('email'))
            ->first();

        Log::warning('Échec de réinitialisation de mot de passe', [
            'email' => $request->input('email'),
            'status' => $status,
            'jeton_existe_en_base' => $ligne !== null,
            'age_du_jeton_en_base_secondes' => $ligne
                ? now()->diffInSeconds(\Carbon\Carbon::parse($ligne->created_at))
                : null,
        ]);
    }
}
