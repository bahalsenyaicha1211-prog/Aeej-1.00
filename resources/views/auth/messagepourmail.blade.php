<div class="container" style="text-align: center; margin-top: 50px;">
    <h1>Féllicitations {{ $nom }} pour votre inscription sur la plateforme de AEEJ !</h1>
    
    <div style="background: #f4f4f4; padding: 20px; border-radius: 8px; display: inline-block;">
        <h2>📧 Vérifiez votre boîte mail</h2>
        <p>
            Un lien de configuration de mot de passe a été envoyé à : <br>
            <strong>{{ $email }}</strong>
        </p>
        <p>Veuillez cliquer sur le bouton dans l'email pour finaliser la création de votre compte.<br>
    NB: <strong>Le message peut figurer dans vos spam.</strong></p>

    </div>

    <div style="margin-top: 20px;">
        <a href="{{ route('accueil') }}" class="btn btn-primary">Retour à l'accueil</a>
    </div>
</div>