<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SYSCHECK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #1f2937;
            /* bg-gray-900 */
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
            animation: fadeIn 0.5s ease-in-out;
        }
    </style>
</head>

<body class="flex flex-col items-center justify-center min-h-screen text-white p-8">

    <!-- Título SYSCHECK -->
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold mb-2">SYSCHECK</h1>
        <h2 class="text-xl text-gray-300">SISTEMA DE CHECKLIST</h2>
    </div>

    <?php
    session_start();
    if (isset($_SESSION['idUsuario'])) {
        header("Location: /syscheck/index2.php");
    }
    ?>

    <div class="bg-gray-800 p-8 rounded-2xl shadow-2xl w-full max-w-md animate-fadeIn">
        <h2 class="text-2xl font-bold text-center mb-6">LOGIN</h2>
        <form action="/syscheck/usuario/login" method="POST" class="flex flex-col gap-4">
            <div class="flex flex-col">
                <label for="username" class="mb-1">Usuário</label>
                <input type="text" name="usuario" id="username" placeholder="Digite seu usuário"
                    class="p-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex flex-col">
                <label for="password" class="mb-1">Senha</label>
                <input type="password" name="senha" id="password" placeholder="Digite sua senha"
                    class="p-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105 mt-2">
                Entrar
            </button>
        </form>
    </div>

</body>

</html>