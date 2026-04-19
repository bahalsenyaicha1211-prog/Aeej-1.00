<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Réussie | AEEJ</title>
    <style>
        /* Variables de couleurs AEEJ */
        :root {
            --primary-green: #2ecc71;
            --dark-green: #27ae60;
            --light-green: #e8f8f5;
            --text-dark: #2c3e50;
            --white: #ffffff;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: radial-gradient(circle at top right, var(--light-green), #f9fbf9);
            color: var(--text-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Container Principal avec effet Glassmorphism */
        .card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 3rem 2rem;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(46, 204, 113, 0.2);
            max-width: 600px;
            width: 90%;
            text-align: center;
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Icône animée */
        .success-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            display: inline-block;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
            40% {transform: translateY(-10px);}
            60% {transform: translateY(-5px);}
        }

        h1 {
            color: var(--dark-green);
            font-size: 1.8rem;
            margin-bottom: 1rem;
            line-height: 1.3;
        }

        .info-box {
            background: var(--white);
            border-left: 5px solid var(--primary-green);
            padding: 1.5rem;
            border-radius: 12px;
            margin: 2rem 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            text-align: left;
        }

        .info-box h2 {
            font-size: 1.2rem;
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            color: var(--primary-green);
        }

        .email-display {
            display: block;
            background: #f0fdf4;
            color: var(--dark-green);
            padding: 8px 12px;
            border-radius: 6px;
            font-weight: bold;
            margin: 10px 0;
            word-break: break-all;
        }

        .nb-text {
            font-size: 0.9rem;
            color: #7f8c8d;
            margin-top: 10px;
            font-style: italic;
        }

        /* Bouton Moderne */
        .btn-home {
            display: inline-block;
            background: var(--primary-green);
            color: white;
            text-decoration: none;
            padding: 14px 30px;
            border-radius: 50px;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(46, 204, 113, 0.3);
        }

        .btn-home:hover {
            background: var(--dark-green);
            transform: translateY(-3px);
            box-shadow: 0 15px 25px rgba(46, 204, 113, 0.4);
        }

        /* Décorations flottantes */
        .blob {
            position: absolute;
            z-index: -1;
            filter: blur(40px);
            opacity: 0.4;
            border-radius: 50%;
            background: var(--primary-green);
        }

        /* Responsive */
        @media (max-width: 480px) {
            .card { padding: 2rem 1.5rem; }
            h1 { font-size: 1.5rem; }
        }
    </style>
</head>
<body>

    <div class="blob" style="width: 300px; height: 300px; top: -100px; left: -100px;"></div>
    <div class="blob" style="width: 200px; height: 200px; bottom: -50px; right: -50px; background: #3498db;"></div>

    <div class="card">
        <div class="success-icon">🎉</div>
        
        <h1>Félicitations {{ $nom }} !</h1>
        <p style="color: #7f8c8d;">Votre inscription sur la plateforme de l'AEEJ est confirmée.</p>
        
        <div class="info-box">
            <h2>📧 Vérifiez votre boîte mail</h2>
            <p>Un lien de configuration de mot de passe a été envoyé à :</p>
            <span class="email-display">{{ $email }}</span>
            <p>Veuillez cliquer sur le bouton dans l'email pour finaliser la création de votre compte,<br>
                créer un mot de passe d'au moin 4 caractères.</p>
            
            <p class="nb-text">
                📌 NB : Le message peut figurer dans vos <strong>spams</strong>.
            </p>
        </div>

        <div style="margin-top: 30px;">
            <a href="{{ route('accueil') }}" class="btn-home">Retour à l'accueil</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            console.log('Bienvenue à l\'AEEJ, {{ $nom }} !');
            
            // Animation légère de la boîte info
            const box = document.querySelector('.info-box');
            box.style.opacity = '0';
            box.style.transform = 'scale(0.95)';
            
            setTimeout(() => {
                box.style.transition = 'all 0.5s ease';
                box.style.opacity = '1';
                box.style.transform = 'scale(1)';
            }, 600);
        });
    </script>
</body>
</html>