<x-guest-layout>
    <div class="status-success" style="text-align:left;">
        <strong>Votre compte est en attente de validation.</strong>
    </div>

    <div class="content-body">
        <p>Bonjour {{ auth()->user()->name }},</p>
        <p>
            Votre inscription à l'AEEJ a bien été enregistrée et votre adresse
            e-mail est confirmée. Un administrateur doit maintenant valider
            votre compte avant que vous puissiez accéder à l'espace membre.
        </p>
        <p>
            Vous recevrez l'accès dès que la validation sera faite. En cas de
            délai, contactez le bureau de l'association.
        </p>
    </div>

    <form method="POST" action="{{ route('logout') }}" style="margin-top:20px;">
        @csrf
        <button type="submit">Se déconnecter</button>
    </form>

    <div style="margin-top:14px; text-align:center;">
        <a href="{{ route('accueil') }}">Retour à l'accueil</a>
    </div>
</x-guest-layout>
