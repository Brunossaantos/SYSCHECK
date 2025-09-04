<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial - SYSCHECK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.5s ease-in-out;
        }
    </style>
</head>
<body class="bg-gray-900 text-white min-h-screen flex flex-col items-center p-20">

    <?php 
        use Util\Sessao;
        $usuario = Sessao::retornarUsuarioLogado();
    ?>

    <div class="w-full flex justify-center items-center space-x-10  mb-8 max-w-6xl mx-auto">

        <a href="/syscheck/" class="bg-gray-500 hover:bg-gray-600 px-4 py-2 rounded-lg font-medium transition transform hover:scale-105">
            Voltar
        </a>

        <!-- Home -->
        <a href="/syscheck/index2.php" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg font-medium transition transform hover:scale-105">
            Home
        </a>

        <!-- Logout -->
        <a href="/syscheck/usuario/logout" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg font-medium transition transform hover:scale-105">
            Logout
        </a>

    </div>

    <h1 class="text-3xl font-bold mb-12 text-center">Usuarios</h1>


    <div class="flex flex-col items-center gap-6 w-full max-w-6xl">

        <!-- Card Cadastrar Usuários -->
        <div class="bg-gray-800 p-6 rounded-2xl shadow-2xl  hover:scale-105 transform transition animate-fadeIn text-center w-full md:w-1/3">
            <h2 class="text-xl font-semibold mb-3">Cadastrar usuários</h2>
            <p class="text-gray-300 mb-4">Cadastro de usuários no sistema</p>
            <a href="/syscheck/usuario/cadastrarUsuario" class="bg-blue-500 hover:bg-blue-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">
                novos usuários
            </a>
        </div>

        <!-- Card Gerenciar Usuários -->
        <div class="bg-gray-800 p-6 rounded-2xl shadow-2xl hover:scale-105 transform transition animate-fadeIn text-center w-full md:w-1/3">
            <h2 class="text-xl font-semibold mb-3">Gerenciar usuários</h2>
            <p class="text-gray-300 mb-4">Gerenciar usuários cadastrados no sistema</p>
            <a href="/syscheck/usuario/gerenciarUsuarios" class="bg-blue-500 hover:bg-blue-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">
                Gerenciar usuários
            </a>
        </div>

    </div>

    <?php include_once __DIR__ . '/../../public/components/footer.php'; ?>

</body>
</html>
