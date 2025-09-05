<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário - SYSCHECK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
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

<body class="bg-gray-900 text-white min-h-screen flex flex-col items-center p-8">

    <div class="w-full flex justify-center items-center space-x-20 mb-8 max-w-6xl mx-auto">

        <a href="/syscheck/usuario/gerenciarUsuarios"
            class="bg-gray-500 hover:bg-gray-600 w-20 h-12 flex items-center justify-center text-center rounded-lg text-white font-medium transition transform hover:scale-105 mt-2">
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

    <h1 class="text-3xl font-bold mb-8 text-center">Editar Usuário - SYSCHECK</h1>

    <div class="bg-gray-800 p-6 rounded-2xl shadow-2xl w-full max-w-md animate-fadeIn">
        <form action="/syscheck/usuario/salvaralteracao" method="POST" class="flex flex-col gap-4">
            <input type="hidden" name="idusuario" value="<?= $usuario->getIdUsuario() ?>">

            <!-- Nome -->
            <div class="flex flex-col">
                <label for="nome" class="mb-1 font-semibold">Nome</label>
                <input type="text" name="nome" id="nome" value="<?= $usuario->getNome() ?>" readonly
                    class="p-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Departamento -->
            <div class="flex flex-col">
                <label for="departamento" class="mb-1 font-semibold">Departamento</label>
                <input type="text" name="departamento" value="<?= $usuario->getDepartamento() ?>"
                    class="p-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Cargo -->
            <div class="flex flex-col">
                <label for="cargo" class="mb-1 font-semibold">Cargo</label>
                <input type="text" name="cargo" value="<?= $usuario->getCargo() ?>"
                    class="p-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Nome de usuário -->
            <div class="flex flex-col">
                <label for="nomeusuario" class="mb-1 font-semibold">Nome de usuário</label>
                <input type="text" name="nomeusuario" value="<?= $usuario->getNomeUsuario() ?>" readonly
                    class="p-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Status -->
            <div class="flex flex-col">
                <label for="statususuario" class="mb-1 font-semibold">Status do usuário</label>
                <select name="statususuario"
                    class="p-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="1" <?= ($usuario->getStatusUsuario() == 1) ? "selected" : "" ?>>Ativo</option>
                    <option value="0" <?= ($usuario->getStatusUsuario() == 0) ? "selected" : "" ?>>Inativo</option>
                </select>
            </div>

            <!-- Botão salvar -->
            <button type="submit"
                class="bg-red-500 hover:bg-red-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105 mt-4">
                Salvar cadastro
            </button>
        </form>
    </div>

    <?php include_once __DIR__ . '/../../public/components/footer.php'; ?>

</body>

</html>