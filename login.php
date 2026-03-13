<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* FUNDO ANIMADO */

        body {
            background: linear-gradient(-45deg, #0f172a, #1e293b, #111827, #1e3a8a);
            background-size: 400% 400%;
            animation: bgMove 15s ease infinite;
        }

        @keyframes bgMove {

            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }

        }

        /* CARD ENTRADA */

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn .6s ease;
        }

        /* PAINEL FLUTUANDO */

        @keyframes floating {

            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-8px);
            }

            100% {
                transform: translateY(0px);
            }

        }

        .floating {
            animation: floating 4s ease-in-out infinite;
        }

        /* TEXTO SYSCHECK GRADIENTE */

        .syscheck-animate {

            font-weight: 700;

            background: linear-gradient(90deg,
                    #3b82f6,
                    #60a5fa,
                    #93c5fd,
                    #60a5fa,
                    #3b82f6);

            background-size: 300%;

            background-clip: text;
            -webkit-background-clip: text;

            color: transparent;
            -webkit-text-fill-color: transparent;

            animation: gradientMove 4s linear infinite;

        }

        @keyframes gradientMove {

            0% {
                background-position: 0%;
            }

            100% {
                background-position: 300%;
            }

        }

        /* INPUT FOCUS */

        .input-focus:focus {
            box-shadow: 0 0 0 2px #3b82f6;
        }

        /* ERRO */

        @keyframes shake {

            0% {
                transform: translateX(0)
            }

            25% {
                transform: translateX(-5px)
            }

            50% {
                transform: translateX(5px)
            }

            75% {
                transform: translateX(-5px)
            }

            100% {
                transform: translateX(0)
            }

        }

        .shake {
            animation: shake .3s;
        }
    </style>

</head>

<body class="flex flex-col items-center justify-center min-h-screen text-white p-4">

    <!-- TEXTO SYSCHECK -->

    <div class="text-center mb-6">

        <h1 class="text-5xl tracking-wide syscheck-animate">
            SYSCHECK
        </h1>

        <p class="text-gray-300 text-sm tracking-widest mt-2">
            SISTEMA DE CHECKLIST
        </p>

    </div>

    <?php

    require_once __DIR__ . '/functions/log.php';

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['idUsuario'])) {
        header("Location: /syscheck/index2.php");
        exit();
    }

    ?>

    <!-- PAINEL LOGIN -->

    <div class="bg-gray-800 p-6 rounded-3xl shadow-2xl w-full max-w-md animate-fadeIn floating">

        <h2 class="text-2xl font-bold text-center mb-4">LOGIN</h2>

        <?php if (isset($_GET['erro'])): ?>
            <div class="bg-red-500 text-white p-3 rounded-lg mb-4 text-center shake">
                Usuário ou senha incorretos
            </div>
        <?php endif; ?>

        <form action="/syscheck/usuario/login" method="POST" class="flex flex-col gap-5">

            <div class="flex flex-col">

                <label for="username" class="mb-1 text-gray-300">Usuário</label>

                <input
                    type="text"
                    name="usuario"
                    id="username"
                    required
                    placeholder="Digite seu usuário"
                    class="p-3 rounded-lg bg-gray-700 border border-gray-600 text-white input-focus outline-none transition">

            </div>

            <div class="flex flex-col relative">

                <label for="password" class="mb-1 text-gray-300">Senha</label>

                <input
                    type="password"
                    name="senha"
                    id="password"
                    placeholder="Digite sua senha"
                    class="p-3 rounded-lg bg-gray-700 border border-gray-600 text-white input-focus outline-none transition pr-10">

                <i
                    id="toggleSenha"
                    class="fa-solid fa-eye absolute right-3 top-[43px] cursor-pointer text-gray-400 hover:text-white transition">
                </i>

            </div>

            <button
                type="submit"
                class="bg-blue-500 hover:bg-blue-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105 mt-2">

                Entrar

            </button>

        </form>

    </div>

    <script>
        const toggleSenha = document.getElementById("toggleSenha");
        const senha = document.getElementById("password");

        toggleSenha.addEventListener("click", function() {

            if (senha.type === "password") {

                senha.type = "text";
                toggleSenha.classList.remove("fa-eye");
                toggleSenha.classList.add("fa-eye-slash");

            } else {

                senha.type = "password";
                toggleSenha.classList.remove("fa-eye-slash");
                toggleSenha.classList.add("fa-eye");

            }

        });
    </script>

</body>

</html>