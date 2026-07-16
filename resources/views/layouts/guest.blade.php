<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AEEJ') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #22c55e;
            --primary-hover: #4ade80;
            --bg-dark: #0f172a;
            --text-main: #ffffff;
            --text-dim: #94a3b8;
            --glass: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        /* Reset & Base */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }

        body {
            height: 100vh;
            background-color: var(--bg-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
            color: var(--text-main);
        }

        /* Orbes magiques en arrière-plan */
        .orb {
            position: absolute;
            width: 50vh;
            height: 50vh;
            border-radius: 50%;
            filter: blur(100px);
            z-index: 1;
            opacity: 0.2;
            animation: pulse 10s infinite alternate;
        }
        .orb-1 { top: -10%; left: -10%; background: var(--primary); }
        .orb-2 { bottom: -10%; right: -10%; background: #064e3b; }

        @keyframes pulse { from { transform: scale(1); } to { transform: scale(1.15); } }

        /* Container Principal */
        .container {
            width: 92%;
            max-width: 440px;
            max-height: 95vh;
            background: var(--glass);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border-radius: 30px;
            padding: 35px;
            border: 1px solid var(--glass-border);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.6);
            z-index: 10;
            overflow-y: auto;
            scrollbar-width: none; /* Firefox */
        }
        .container::-webkit-scrollbar { display: none; } /* Chrome/Safari */

        /* Header Styles */
        .header { text-align: center; margin-bottom: 25px; }

        .logo-box {
            width: 80px;
            height: 80px;
            background: #fff;
            border-radius: 20px;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 20px rgba(34, 197, 94, 0.2);
        }
        .logo-box img { width: 55px; height: 55px; object-fit: contain; }

        h1 { font-size: 26px; font-weight: 700; letter-spacing: 1px; margin-bottom: 5px; }
        h1 span { color: var(--primary); }

        .badge-secure {
            display: inline-block;
            background: rgba(34, 197, 94, 0.15);
            color: var(--primary-hover);
            font-size: 11px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
            text-transform: uppercase;
            margin-bottom: 12px;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .description {
            font-size: 13px;
            color: var(--text-dim);
            line-height: 1.5;
            max-width: 90%;
            margin: 0 auto;
        }

        /* Contenu Laravel (Pages Verif, Reset, etc.) */
        .content-body { margin-top: 20px; }
        
        /* Correction lisibilité des textes informatifs de Laravel */
        .content-body div, .content-body p {
            color: #f1f5f9 !important;
            font-size: 13.5px;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        /* Formulaire */
        label { display: block; color: var(--text-dim); font-size: 12px; margin-bottom: 8px; margin-top: 15px; padding-left: 5px;}
        
        input {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--glass-border);
            padding: 14px;
            border-radius: 12px;
            color: #fff;
            font-size: 14px;
            outline: none;
            transition: 0.3s;
        }
        input:focus { border-color: var(--primary); background: rgba(255, 255, 255, 0.1); box-shadow: 0 0 15px rgba(34, 197, 94, 0.1); }

        /* Boutons & Liens */
        button:not(.underline) {
            width: 100%;
            padding: 16px;
            background: var(--primary);
            color: #0f172a;
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 15px;
            transition: 0.3s ease;
        }
        button:not(.underline):hover { transform: translateY(-2px); filter: brightness(1.1); box-shadow: 0 10px 20px rgba(34, 197, 94, 0.3); }

        a, .underline, button.underline {
            color: var(--primary) !important;
            text-decoration: none !important;
            font-size: 13px;
            font-weight: 600;
            background: none;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }
        a:hover, .underline:hover { color: var(--primary-hover) !important; text-shadow: 0 0 10px rgba(34, 197, 94, 0.4); }

        .flex-group {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 25px;
        }

        /* Messages de succès/erreur */
        .status-success {
            background: rgba(34, 197, 94, 0.1);
            color: var(--primary-hover);
            padding: 12px;
            border-radius: 10px;
            font-size: 13px;
            border: 1px solid rgba(34, 197, 94, 0.2);
            text-align: center;
            margin-bottom: 20px;
        }

        /* Erreurs de validation (par champ) */
        input.field-invalid { border-color: #f87171 !important; box-shadow: 0 0 12px rgba(248, 113, 113, 0.15) !important; }

        ul[class*="text-red"], .field-error {
            list-style: none;
            background: rgba(248, 113, 113, 0.1);
            border: 1px solid rgba(248, 113, 113, 0.25);
            color: #fca5a5 !important;
            font-size: 12.5px;
            font-weight: 600;
            padding: 8px 12px;
            border-radius: 10px;
            margin-top: 8px;
        }
        ul[class*="text-red"] li, .field-error li { margin: 0; }
    </style>
</head>
<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="container">
        <div class="header">
            <div class="logo-box">
                <img src="{{ asset('images/drapeau/AEEJ.png') }}" alt="Logo AEEJ">
            </div>
            <div class="badge-secure">Connexion sécurisée</div>
            <h1>AEEJ<span>.</span></h1>
            <p class="description">Plateforme de l'Association des Étudiants Étrangers à Jendouba</p>
        </div>

        <div class="content-body">
            {{ $slot }}
        </div>
    </div>
</body>
</html>