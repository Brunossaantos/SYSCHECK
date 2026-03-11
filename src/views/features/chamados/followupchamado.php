<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Follow-up do Chamado</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="/syscheck/src/views/public/css/styles.css">
</head>

<body class="bg-gray-900 text-white min-h-screen">
  <!-- Barra superior -->
  <div class="w-full flex justify-center space-x-5 gap-6 p-6">
    <a href="/syscheck/chamado/gerenciarChamados"
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


  <div class="max-w-6xl mx-auto mt-10 px-4">
    <h2 class="text-3xl font-bold mb-8">Gerenciamento de Chamado</h2>

    <!-- Cabeçalho do Chamado -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 mb-8 shadow-lg">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-lg">
        <div class="space-y-3">
          <p><span class="font-semibold text-gray-200">Número do Chamado:</span> <?= $chamado->getIdChamado() ?></p>
          <p><span class="font-semibold text-gray-200">Abertura do Chamado:</span> <?= $chamado->getDataAberturaChamado() ?></p>
          <p><span class="font-semibold text-gray-200">Equipamento:</span> <?= $equipamento->getDescricaoObjeto() ?></p>
          <p><span class="font-semibold text-gray-200">Última Providência:</span> <?= $chamado->getDescricaoChamado() ?></p>
        </div>
        <div class="space-y-3">
          <p><span class="font-semibold text-gray-200">Status:</span> Em andamento</p>
          <p><span class="font-semibold text-gray-200">Usuário:</span> <?= $usuario->getNome() ?></p>
          <p class="font-semibold text-gray-200">Fotos do Chamado:</p>
          <div class="flex flex-wrap mt-3 gap-3">
            <?php foreach ($listaFotos as $foto) { ?>
              <img src="/syscheck/src/views/<?= $foto['caminhoImagem'] ?>"
                alt="<?= $foto['caminhoImagem'] ?>"
                class="w-24 h-24 object-cover rounded-lg border border-gray-600 shadow-md">
            <?php } ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabela de Follow-up -->
    <h4 class="text-2xl font-semibold mb-4">Follow-up do Chamado</h4>
    <div class="overflow-y-auto max-h-[500px] border border-gray-700 rounded-lg shadow-lg">
      <table class="w-full border-collapse">
        <thead class="bg-gray-700 text-gray-100 sticky top-0 text-lg">
          <tr>
            <th class="px-6 py-4 text-left">Data/Hora</th>
            <th class="px-6 py-4 text-left">Usuário</th>
            <th class="px-6 py-4 text-left">Ação</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-700 text-gray-200 text-lg">
          <?php foreach ($listaFollowUp as $followup) { ?>
            <tr class="hover:bg-gray-800 transition">
              <td class="px-6 py-4"><?= $followup['dataHora'] ?></td>
              <td class="px-6 py-4"><?= $followup['fkUsuario']->getNome() ?></td>
              <td class="px-6 py-4"><?= $followup['followUp'] ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</body>

</html>