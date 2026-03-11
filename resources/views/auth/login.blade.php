<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Connexion | Gestion du Parc Informatique</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin: 0;
            font-family: "Segoe UI", Arial, sans-serif;
            background: linear-gradient(135deg, #f9d423, #fceabb);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            background: #ffffff;
            width: 400px;
            padding: 35px;
            border-radius: 10px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
            text-align: center;
        }

        .brand-logo {
            width: 80px;
            height: auto;
            margin-bottom: 10px;
        }

        .bank-name {
            font-size: 18px;
            font-weight: bold;
            color: #c99700;
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 25px;
        }

        h2 {
            color: #333;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 18px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            color: #444;
        }

        input {
            width: 100%;
            padding: 11px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: #f9d423;
            box-shadow: 0 0 0 2px rgba(249, 212, 35, 0.3);
        }

        .remember {
            display: flex;
            align-items: center;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .remember input {
            width: auto;
            margin-right: 8px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #f9d423;
            border: none;
            border-radius: 5px;
            color: #333;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #e6c200;
        }

        .forgot {
            text-align: center;
            margin-top: 20px;
        }

        .forgot a {
            color: #c99700;
            text-decoration: none;
            font-size: 13px;
        }

        .forgot a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
    </style>
</head>

<body>

    <div class="login-box">
        {{-- Logo --}}
        <a href="{{ route('login') }}">
            <img src="{{ asset('assets/img/bms.jpg') }}" alt="Logo BMS" class="brand-logo">
        </a>

        <div class="bank-name">Banque Malienne de Solidarité</div>
        <div class="subtitle">Système de gestion du parc informatique</div>

        <h2>Connexion sécurisée</h2>

        {{-- Messages d'alerte --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="form-group">
                <label>Email / Identifiant</label>
                <input type="email" name="email" placeholder="" value="{{ old('email') }}"
                    required>
            </div>

            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" placeholder="" required>
            </div>

            {{-- <div class="remember">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Se souvenir de moi</label>
            </div> --}}

            <button type="submit">Se connecter</button>
        </form>

        {{-- <div class="forgot">
            <a href="#">Mot de passe oublié ?</a>
        </div> --}}
    </div>

</body>

</html>
