<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Usuários</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        #tabelaUsuarios {
            visibility: hidden;
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

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</head>

<body class="bg-gray-900 text-white min-h-screen flex flex-col items-center p-8">

    <?php

    use rn\RnUsuario;
    use Util\Util;
    use Util\Sessao;

    // Mostra mensagem de sucesso se existir
    Sessao::mostrarMensagem();
    ?>

    <div class="w-full flex justify-between items-center mb-8 max-w-6xl mx-auto">
        <a href="/syscheck/usuario" class="bg-gray-500 hover:bg-gray-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">Voltar</a>
        <a href="/syscheck/index2.php" class="bg-blue-500 hover:bg-blue-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">Home</a>
        <a href="/syscheck/usuario/logout" class="bg-red-500 hover:bg-red-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">Logout</a>
    </div>

    <h1 class="text-3xl font-bold mb-8 text-center">Consulta de Usuários</h1>

    <div class="bg-gray-800 p-6 rounded-2xl shadow-2xl w-full max-w-6xl animate-fadeIn">

        <!-- Campo de pesquisa externo -->
        <div class="mb-4">
            <input type="text" id="filtroUsuario" placeholder="Pesquisar usuários..."
                class="w-full p-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="overflow-x-auto">
            <table id="tabelaUsuarios" class="min-w-full table-auto border border-gray-700">
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
                    <?php foreach ($listaUsuario as $usuario): ?>

                        <tr class="border-t border-gray-700 hover:bg-gray-700 
        <?= $usuario->getStatusUsuario() == 0 ? 'opacity-50' : '' ?>">

                            <!-- Nome -->
                            <td class="px-4 py-2">
                                <?= $usuario->getNome() ?>
                            </td>

                            <!-- Departamento -->
                            <td class="px-4 py-2">
                                <?= $usuario->getDepartamento() ?>
                            </td>

                            <!-- Cargo -->
                            <td class="px-4 py-2">
                                <?= $usuario->getCargo() ?>
                            </td>

                            <!-- Nome de Usuário -->
                            <td class="px-4 py-2">
                                <?= $usuario->getNomeUsuario() ?>
                            </td>

                            <!-- Status -->
                            <td class="px-4 py-2">
                                <?php if ($usuario->getStatusUsuario() > 0): ?>
                                    <span class="text-green-400 font-semibold">Ativo</span>
                                <?php else: ?>
                                    <span class="text-black-400 font-semibold">Inativo</span>
                                <?php endif; ?>
                            </td>

                            <!-- Ações -->
                            <td class="px-4 py-2">
                                <div class="flex gap-2">

                                    <!-- Botão Editar -->
                                    <a href="/syscheck/usuario/alterarCadastroUsuario/<?= $usuario->getIdUsuario() ?>"
                                        class="w-24 text-center bg-blue-500 hover:bg-blue-600 px-3 py-1 rounded text-white text-sm">
                                        Editar
                                    </a>

                                    <?php if ($usuario->getStatusUsuario() > 0): ?>

                                        <!-- Botão Desativar -->
                                        <a href="/syscheck/usuario/excluirUsuario/<?= $usuario->getIdUsuario() ?>"
                                            class="w-24 text-center bg-red-500 hover:bg-red-600 px-3 py-1 rounded text-white text-sm"
                                            onclick="return confirm('Tem certeza que deseja desativar este usuário?');">
                                            Desativar
                                        </a>

                                    <?php else: ?>

                                        <!-- Botão Ativar -->
                                        <a href="/syscheck/usuario/ativarUsuario/<?= $usuario->getIdUsuario() ?>"
                                            class="w-24 text-center bg-green-500 hover:bg-green-600 px-3 py-1 rounded text-white text-sm"
                                            onclick="return confirm('Tem certeza que deseja ativar este usuário?');">
                                            Ativar
                                        </a>

                                    <?php endif; ?>

                                </div>
                            </td>

                            <!-- Senha -->
                            <td class="px-4 py-2">
                                <?= (new rn\RnUsuario(1))->verificarSenha($usuario->getIdUsuario()) ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include_once __DIR__ . '/../../public/components/footer.php'; ?>

    <script>
        $(document).ready(function() {

            var tabelaUsuarios = $('#tabelaUsuarios').DataTable({
                paging: true,
                info: true,
                ordering: true,
                language: {
                    search: "Filtrar:"
                }
            });

            // Mostra tabela depois que DataTable inicializar
            $('#tabelaUsuarios').css('visibility', 'visible');

            // Filtro externo
            $('#filtroUsuario').on('keyup', function() {
                tabelaUsuarios.search(this.value).draw();
            });

        });
    </script>

</body>

</html>