<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\WelcomeSetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, WelcomeSetPassword::class);
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, WelcomeSetPassword::class, function ($notification) {
            $response = $this->get('/reset-password/'.$notification->token);

            $response->assertStatus(200);

            return true;
        });
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, WelcomeSetPassword::class, function ($notification) use ($user) {
            $response = $this->post('/reset-password', [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $response
                ->assertSessionHasNoErrors()
                ->assertRedirect(route('login'));

            return true;
        });
    }

    public function test_password_can_be_reset_when_the_email_field_gets_capitalized_by_a_mobile_keyboard(): void
    {
        Notification::fake();

        $user = User::factory()->create(['email' => 'brahimac2006@gmail.com']);

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, WelcomeSetPassword::class, function ($notification) use ($user) {
            $response = $this->post('/reset-password', [
                'token' => $notification->token,
                'email' => 'Brahimac2006@gmail.com',
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $response
                ->assertSessionHasNoErrors()
                ->assertRedirect(route('login'));

            return true;
        });
    }

    public function test_a_new_reset_request_invalidates_the_earlier_link(): void
    {
        // Documente le comportement réel qui cause la plupart des "jetons
        // invalides" en production : Laravel n'autorise qu'un seul jeton
        // actif par e-mail. Redemander un lien (même quelques secondes plus
        // tard) invalide silencieusement le précédent.
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);
        $ancienToken = null;
        Notification::assertSentTo($user, WelcomeSetPassword::class, function ($notification) use (&$ancienToken) {
            $ancienToken = $notification->token;

            return true;
        });

        $this->travel(2)->minutes();
        $this->post('/forgot-password', ['email' => $user->email]);

        $response = $this->post('/reset-password', [
            'token' => $ancienToken,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_the_invalid_token_screen_offers_a_resend_link(): void
    {
        $user = User::factory()->create();

        $this->from('/reset-password/jeton-invalide?email='.$user->email)
            ->post('/reset-password', [
                'token' => 'jeton-invalide',
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

        $response = $this->get('/reset-password/jeton-invalide?email='.$user->email);

        $response->assertSee(route('password.email'), false);
    }
}
