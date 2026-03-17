<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - SYSCHECK</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background-color: #1f2937;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn .5s ease-in-out;
        }



        .logo {
            background: linear-gradient(90deg, #3b82f6, #60a5fa, #93c5fd);

            background-clip: text;

            -webkit-background-clip: text;


            -webkit-text-fill-color: transparent;
        }
    </style>

</head>

<body class="flex flex-col items-center justify-center min-h-screen text-white p-8">

    <div class="text-center mb-10">

        <h1 class="text-5xl font-extrabold logo tracking-widest">
            SYSCHECK
        </h1>

        <h2 class="text-xl text-gray-300 mt-2">
            SISTEMA DE CHECKLIST
        </h2>

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

    <div class="bg-gray-800 p-8 rounded-2xl shadow-2xl w-full max-w-md animate-fadeIn">

        <h2 class="text-2xl font-bold text-center mb-6">
            LOGIN
        </h2>

        <form action="/syscheck/usuario/login" method="POST" class="flex flex-col gap-4">

            <div class="flex flex-col">

                <label class="mb-1">Usuário</label>

                <input
                    type="text"
                    name="usuario"
                    placeholder="Digite seu usuário"
                    required
                    class="p-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">

            </div>

            <div class="flex flex-col relative">

                <label class="mb-1">Senha</label>

                <input
                    id="senha"
                    type="password"
                    name="senha"
                    placeholder="Digite sua senha"
                    class="p-3 pr-10 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">

                <button
                    type="button"
                    onclick="toggleSenha()"
                    class="absolute right-3 top-10 text-gray-400 hover:text-white">

                    <!-- ícone olho -->
                    <svg id="iconeOlho" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51 7.36 4.5 12 4.5s8.577 3.01 9.964 7.178a1.012 1.012 0 010 .644C20.577 16.49 16.64 19.5 12 19.5S3.423 16.49 2.036 12.322z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>

                </button>

            </div>

            <button
                type="submit"
                class="bg-blue-500 hover:bg-blue-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105 mt-2">

                Entrar

            </button>

        </form>

    </div>

    <script>
        /* mostrar senha */

        function toggleSenha() {

            const campo = document.getElementById("senha")

            if (campo.type === "password") {
                campo.type = "text"
            } else {
                campo.type = "password"
            }

        }

        /* parametros url */

        const params = new URLSearchParams(window.location.search)

        const mensagens = {
            login: "Faça login para acessar essa página.",
            inatividade: "Sua sessão expirou por inatividade."
        }

        const msg = params.get("msg")

        if (msg && mensagens[msg]) {
            alert(mensagens[msg])
        }

        if (params.get("erro")) {
            alert("Usuário ou senha incorretos.")
        }

        if (msg || params.get("erro")) {
            window.history.replaceState({}, document.title, window.location.pathname)
        }
    </script>

</body>

</html>