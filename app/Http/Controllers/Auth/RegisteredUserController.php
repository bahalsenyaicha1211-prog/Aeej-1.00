<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Affiche la page d'inscription.
     */
    public function create(): View
    {
        return view('auth.register');
    }

   
/*Creation d'un nouvel utilisateur avec mot de passe aléatoire,*/ 

public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name'  => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        
    ]);

    // 1. Création du user avec mot de passe aléatoire
    $user = User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make(Str::random(32)), 
    ]);

    // 2. Générer le jeton de réinitialisation (Token)
    $token = Password::createToken($user);


    // Laravel Breeze va envoyer automatiquement le lien correct avec l'email inclus
    $user->sendPasswordResetNotification($token);

    
    return redirect()->route('login')
        ->with('status', 'Inscription réussie ! Veuillez vérifier votre boîte mail pour définir votre mot de passe.');
}
}
