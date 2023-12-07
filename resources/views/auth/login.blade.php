<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/login.css">
    <title>Alpha </title>
</head>
<body>

<div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">

        @csrf


    <div class="login">
        <div class="logo-yyt-logo-lo">
            <img class="logo-yyt-logo-login" src="/img/Logo.png">
        </div>

        <div>
            <label for="email">Email </label>
            <input class="inputLogin" type="email" name="email" :value="old('email')" required autofocus autocomplete="username">
        </div>

     
        <div>
            <label for="password">Senha </label>
            <input id="password" class="inputLogin"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
        </div>


        <div class="senhmiy">

        </div>
            <div>
                <button  id="adicionar">
                    Login
                </button>
                <button  href="/register" id="adicionar">
                    Cadastrar
                </button>
            </div>
        </div>

    </form>
</div>

</div>
</html>
