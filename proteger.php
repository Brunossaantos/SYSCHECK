<?php

$redirectUrl = '/syscheck/login.php';
$waitSeconds = 5;

// ===============================
// CONFIGURAÇÃO DE LOG
// ===============================
$logDir  = __DIR__ . '/../logs';
$logFile = $logDir . '/system.log';

// Garante que a pasta de logs exista
if (!is_dir($logDir)) {
    mkdir($logDir, 0777, true);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$rotaAtual = $_SERVER['REQUEST_URI'] ?? '';

// Evita aplicar validação na rota de login
if (strpos($rotaAtual, '/syscheck/usuario/login') !== false) {
    return;
}

// Tempo máximo de inatividade (30 minutos)
$tempoInatividade = 1800;

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $tempoInatividade)) {

    session_unset();
    session_destroy();

    echo <<<HTML
<div id="session-expired-modal" style="
    position:fixed; inset:0; display:flex; align-items:center; justify-content:center;
    background:rgba(0,0,0,0.45); z-index:99999; font-family:Arial,Helvetica,sans-serif;
">
  <div style="
      max-width:420px; width:90%; background:#fff; border-radius:10px; padding:20px;
      box-shadow:0 10px 30px rgba(0,0,0,0.25);
  ">
    <h2>Sessão encerrada</h2>
    <p>
      Sua sessão foi encerrada por inatividade.
      Você será redirecionado em
      <strong id="countdown">{$waitSeconds}</strong> segundos.
    </p>
    <div style="text-align:right; margin-top:15px;">
      <a href="{$redirectUrl}">Entrar novamente</a>
    </div>
  </div>
</div>

<script>
(function(){
    let t = {$waitSeconds};
    const el = document.getElementById('countdown');
    const timer = setInterval(() => {
        t--;
        if (el) el.textContent = t;
        if (t <= 0) {
            clearInterval(timer);
            window.location.href = "{$redirectUrl}";
        }
    }, 1000);
})();
</script>
HTML;

    exit();
}

// Atualiza atividade
$_SESSION['LAST_ACTIVITY'] = time();

// ===============================
// VALIDA CAMPOS OBRIGATÓRIOS
// ===============================
$camposObrigatorios = ['idUsuario', 'nomeUsuario', 'statusUsuario'];

foreach ($camposObrigatorios as $campo) {

    if (!array_key_exists($campo, $_SESSION)) {

        $mensagemLog = sprintf(
            "[%s] [SESSAO INVALIDA] Campo ausente: %s | URI: %s | SESSION: %s%s",
            date('Y-m-d H:i:s'),
            $campo,
            $rotaAtual,
            json_encode($_SESSION, JSON_UNESCAPED_UNICODE),
            PHP_EOL
        );

        // Grava diretamente no arquivo desejado
        error_log($mensagemLog, 3, $logFile);

        // Redirecionamento silencioso
        header("Location: {$redirectUrl}");
        exit;
    }
}
