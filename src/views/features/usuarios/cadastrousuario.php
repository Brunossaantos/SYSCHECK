<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário - SYSCHECK</title>
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

    <div class="w-full flex justify-center items-center space-x-10  mb-8 max-w-6xl mx-auto">

        <a href="/syscheck/usuario/" class="bg-gray-500 hover:bg-gray-600 px-4 py-2 rounded-lg font-medium transition transform hover:scale-105">
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

    <h1 class="text-3xl font-bold mb-8 text-center">Cadastro de Usuário - SYSCHECK</h1>


    <div class="bg-gray-800 p-8 rounded-2xl shadow-2xl w-full max-w-md animate-fadeIn">
        <form action="/syscheck/usuario/cadastrarUsuario" method="POST" class="flex flex-col gap-4">

            <!-- Nome -->
            <div class="flex flex-col">
                <label for="nome" class="mb-1">Nome</label>
                <input type="text" name="nome" id="nome" placeholder="Digite o nome do usuário"
                    class="p-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" autocomplete="off">
            </div>

            <!-- Departamento -->
            <div class="flex flex-col">
                <label for="departamento" class="mb-1">Departamento</label>
                <select name="departamento" id="departamento"
                    class="p-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="--" selected disabled>Selecione um departamento</option>
                    <?php foreach ($listaDepartamentos as $departamento) { ?>
                        <option value="<?= $departamento->getIdDepartamento() ?>"><?= $departamento->getDescricaoDepartamento() ?></option>
                    <?php } ?>
                </select>
            </div>

            <!-- Cargo -->
            <div class="flex flex-col">
                <label for="cargo" class="mb-1">Cargo</label>
                <select name="cargo" id="cargo"
                    class="p-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="--" selected disabled>Selecione o cargo do usuário</option>
                </select>
            </div>

            <!-- Nome de usuário -->
            <div class="flex flex-col">
                <label for="nomeusuario" class="mb-1">Nome de usuário</label>
                <input type="text" name="nomeusuario" id="nomeusuario" placeholder="Login para acesso"
                    class="p-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" autocomplete="off">
            </div>

            <!-- Status do usuário -->
            <div class="flex flex-col">
                <label for="statususuario" class="mb-1">Status do usuário</label>
                <select name="statususuario" id="statususuario"
                    class="p-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="--" selected disabled>Status do usuário</option>
                    <option value="1">Ativo</option>
                    <option value="0">Inativo</option>
                </select>
            </div>

            <!-- Checklist veicular -->
            <div class="flex items-center gap-2 mt-2">
                <input type="checkbox" name="checklistveicular" id="checklistveicular" value="1" class="rounded text-blue-500 focus:ring-blue-500">
                <label for="checklistveicular">Checklist veicular</label>
            </div>

            <!-- Botão Cadastrar -->
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105 mt-4">
                Cadastrar
            </button>

        </form>
    </div>

    <?php include_once __DIR__ . '/../../public/components/footer.php'; ?>

</body>

</html>