<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Syscheck - Primeiro acesso</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen flex flex-col items-center justify-center p-8">

    <!-- Card centralizado -->
    <div class="bg-gray-800 p-8 rounded-2xl shadow-2xl w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Primeiro Acesso</h2>

        <form action="/syscheck/usuario/cadastrarSenha" method="POST" class="space-y-6">

            <input type="hidden" name="idUsuario" value="<?=$usuario->getIdUsuario()?>">

            <!-- Usuário -->
            <div>
                <label for="username" class="block mb-2 font-medium">Usuário</label>
                <input type="text" id="username" name="usuario"
                       value="<?=$usuario->getNomeUsuario()?>"
                       readonly
                       class="w-full p-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <!-- Senha -->
            <div>
                <label for="password" class="block mb-2 font-medium">Senha</label>
                <input type="password" id="password" name="senha" placeholder="Digite sua senha"
                       class="w-full p-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <!-- Confirmar Senha -->
            <div>
                <label for="conf_senha" class="block mb-2 font-medium">Confirmar Senha</label>
                <input type="password" id="conf_senha" name="conf_senha" placeholder="Digite sua senha novamente"
                       class="w-full p-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <!-- Botão -->
            <button type="submit"
                    class="w-full bg-green-500 hover:bg-green-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">
                Entrar
            </button>

        </form>
    </div>

</body>
</html>
