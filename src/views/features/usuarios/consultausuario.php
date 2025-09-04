<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Usuários - SYSCHECK</title>
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


<body class="bg-gray-900 text-white min-h-screen flex flex-col items-center p-8">

    <?php
        use rn\RnUsuario;
        use Util\Util;
    ?>

    <div class="w-full flex justify-between items-center mb-8 max-w-6xl mx-auto">
    <a href="/syscheck/usuario"
        class="bg-gray-500 hover:bg-gray-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">
        Voltar
    </a>

    <a href="/syscheck/index2.php"
        class="bg-blue-500 hover:bg-blue-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">
        Home
    </a>

    <a href="/syscheck/usuario/logout"
        class="bg-red-500 hover:bg-red-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">
        Logout
    </a>
</div>
    

    <h1 class="text-3xl font-bold mb-8 text-center">Consulta de Usuários - SYSCHECK</h1>

    

    <div class="bg-gray-800 p-6 rounded-2xl shadow-2xl w-full max-w-6xl animate-fadeIn">

    

        <!-- Formulário de pesquisa -->
        <form class="flex gap-2 mb-6">
            <input type="search" name="pesquisa" placeholder="Pesquisar"
                   class="flex-1 p-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">
                🔍
            </button>
        </form>

        <!-- Tabela de usuários -->
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto border border-gray-700">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left">Nome</th>
                        <th class="px-4 py-2 text-left">Departamento</th>
                        <th class="px-4 py-2 text-left">Cargo</th>
                        <th class="px-4 py-2 text-left">Nome de usuário</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Ações</th>
                        <th class="px-4 py-2 text-left">Senha cadastrada</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($listaUsuario as $usuario){
                        if($usuario->getStatusUsuario() > 0){ ?>
                        <tr class="border-t border-gray-700 hover:bg-gray-700">
                            <td class="px-4 py-2">
                                <a href="/syscheck/usuario/alterarCadastroUsuario/<?=$usuario->getIdUsuario()?>" class="text-blue-400 hover:underline">
                                    <?=$usuario->getNome()?>
                                </a>
                            </td>
                            <td class="px-4 py-2"><?=$usuario->getDepartamento()?></td>
                            <td class="px-4 py-2"><?=$usuario->getCargo()?></td>
                            <td class="px-4 py-2"><?=$usuario->getNomeUsuario()?></td>
                            <td class="px-4 py-2"><?=Util::status($usuario->getStatusUsuario())?></td>
                            <td class="px-4 py-2 flex gap-2">
                                <a href="/syscheck/usuario/alterarCadastroUsuario/<?=$usuario->getIdUsuario()?>"
                                   class="bg-yellow-500 hover:bg-yellow-600 px-3 py-1 rounded text-white text-sm">Alterar</a>
                                <a href="/syscheck/usuario/excluirUsuario/<?=$usuario->getIdUsuario()?>"
                                   class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded text-white text-sm">Excluir</a>
                            </td>
                            <td class="px-4 py-2"><?php echo (new RnUsuario(1))->verificarSenha($usuario->getIdUsuario()) ?></td>
                        </tr>
                    <?php }} ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include_once __DIR__ . '/../../public/components/footer.php'; ?>

</body>
</html>
