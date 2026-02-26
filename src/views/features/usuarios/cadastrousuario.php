<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Util\Sessao;
use database\Conexao;
use DAO\DaoPerfil;
use models\Perfil;

// Inicia a sessão para pegar mensagens
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$mensagem = Sessao::recuperarMensagem();

// ===============================
// BUSCAR EMPRESAS PARA O SELECT
// ===============================
$conn = (new Conexao())->conectar();
$empresas = [];
$stmt = $conn->prepare("SELECT id_empresa, nome FROM tbl_empresas WHERE ativa = 1");
if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $empresas[] = $row;
    }
}

// ===============================
// BUSCAR PERFIS PARA O SELECT
// ===============================
$daoPerfil = new DaoPerfil($conn);
$perfis = $daoPerfil->listarPerfis();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white min-h-screen flex flex-col items-center p-8">

    <!-- Navegação -->
    <div class="w-full flex justify-center items-center space-x-6 mb-8 max-w-6xl mx-auto">
        <a href="/syscheck/usuario/" class="bg-gray-500 hover:bg-gray-600 px-4 py-2 rounded-lg font-medium transition">
            Voltar
        </a>
        <a href="/syscheck/index2.php" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg font-medium transition">
            Home
        </a>
        <a href="/syscheck/usuario/logout" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg font-medium transition">
            Logout
        </a>
    </div>

    <h1 class="text-3xl font-bold mb-8 text-center">Cadastro de Usuário</h1>

    <!-- Mensagem de sucesso/erro -->
    <?php if (!empty($mensagem)) : ?>
        <div class="mb-6 p-4 bg-green-600 rounded-lg text-white text-center">
            <?= htmlspecialchars($mensagem) ?>
        </div>
    <?php endif; ?>

    <!-- Formulário -->
    <div class="bg-gray-800 p-8 rounded-2xl shadow-2xl w-full max-w-md">

        <form action="" method="POST" class="flex flex-col gap-4">

            <!-- Campos fixos -->
            <input type="hidden" name="departamento" value="1">
            <input type="hidden" name="cargo" value="2">

            <!-- Nome completo -->
            <div class="flex flex-col">
                <label for="nome" class="mb-1">Nome completo</label>
                <input type="text" name="nome" id="nome" placeholder="Use letras maiusculas" required
                    class="p-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Nome de usuário -->
            <div class="flex flex-col">
                <label for="nomeusuario" class="mb-1">Nome de usuário</label>
                <input type="text" name="nomeusuario" id="nomeusuario" placeholder="Login para acesso" required
                    class="p-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Empresa -->
            <div class="flex flex-col">
                <label for="fk_empresa" class="mb-1">Empresa</label>
                <select name="fk_empresa" id="fk_empresa" required
                    class="p-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="" disabled selected>Selecione a empresa</option>
                    <?php foreach ($empresas as $empresa): ?>
                        <option value="<?= $empresa['id_empresa'] ?>"><?= htmlspecialchars($empresa['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Perfil / Cargo -->
            <div class="flex flex-col">
                <label for="fk_perfil" class="mb-1">Perfil do usuário</label>
                <select name="fk_perfil" id="fk_perfil" required
                    class="p-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="" disabled selected>Selecione o perfil</option>
                    <?php foreach ($perfis as $perfil): ?>
                        <option value="<?= $perfil->getIdPerfil() ?>">
                            <?= htmlspecialchars($perfil->getNome()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Status -->
            <div class="flex flex-col">
                <label for="statususuario" class="mb-1">Status do usuário</label>
                <select name="statususuario" id="statususuario" required
                    class="p-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="1">Ativo</option>
                    <option value="0">Inativo</option>
                </select>
            </div>

            <!-- Checklist veicular -->
            <div class="flex items-center gap-2 mt-2">
                <input type="checkbox" name="checklistveicular" id="checklistveicular" value="1"
                    class="rounded text-blue-500 focus:ring-blue-500">
                <label for="checklistveicular">Checklist veicular</label>
            </div>

            <button type="submit"
                class="bg-blue-500 hover:bg-blue-600 px-6 py-3 rounded-lg font-medium transition mt-4">
                Cadastrar
            </button>

        </form>

    </div>

    <?php include_once __DIR__ . '/../../public/components/footer.php'; ?>

</body>

</html>