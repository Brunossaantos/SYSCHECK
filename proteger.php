<?php

$redirectUrl = '/syscheck/login.php';
$waitSeconds = 5;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$rotaAtual = $_SERVER['REQUEST_URI'] ?? '';

if (strpos($rotaAtual, '/syscheck/usuario/login') !== false) {
    return;
}

// Tempo máximo de inatividade (30 minutos)
$tempoInatividade = 1800;

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $tempoInatividade)) {
    session_unset();
    session_destroy();
    echo <<<HTML
<!-- Modal de sessão expirada -->
<div id="session-expired-modal" role="dialog" aria-labelledby="session-expired-title" aria-modal="true" style="
    position:fixed; inset:0; display:flex; align-items:center; justify-content:center;
    background:rgba(0,0,0,0.45); z-index:99999; font-family:Arial,Helvetica,sans-serif;
">
  <div style="
      max-width:420px; width:90%; background:#fff; border-radius:10px; padding:20px 22px;
      box-shadow:0 10px 30px rgba(0,0,0,0.25); text-align:left;
  ">
    <h2 id="session-expired-title" style="margin:0 0 8px 0; font-size:18px; color:#111;">
      Sessão encerrada
    </h2>
    <p style="margin:0 0 12px 0; color:#444; line-height:1.35;">
      Por segurança, sua sessão foi encerrada por inatividade. Você será redirecionado para a página de login em <strong id="session-expired-countdown">{$waitSeconds}</strong> segundos.
    </p>

    <div style="display:flex; gap:8px; justify-content:flex-end; margin-top:16px;">
      <a id="session-expired-btn" href="{$redirectUrl}" style="
          display:inline-block; padding:8px 12px; text-decoration:none; border-radius:6px;
          border:1px solid #0b5fff; color:#0b5fff; background:transparent; font-weight:600;
      ">Entrar novamente</a>

      <button id="session-expired-close" type="button" style="
          display:inline-block; padding:8px 12px; border-radius:6px; border:0;
          background:#0b5fff; color:#fff; font-weight:600; cursor:pointer;
      ">Fechar</button>
    </div>
  </div>
</div>

<script>
(function(){
    var redirect = "{$redirectUrl}";
    var remaining = {$waitSeconds};
    var countdown = document.getElementById('session-expired-countdown');
    var modal = document.getElementById('session-expired-modal');
    var btn = document.getElementById('session-expired-btn');
    var closeBtn = document.getElementById('session-expired-close');

    // Atualiza contador visual a cada segundo
    countdown && (countdown.textContent = remaining);
    var timer = setInterval(function(){
        remaining--;
        if (countdown) countdown.textContent = remaining;
        if (remaining <= 0) {
            clearInterval(timer);
            try { window.top.location.href = redirect; } catch(e) { window.location.href = redirect; }
        }
    }, 1000);

    // Clique no botão leva ao login imediatamente
    btn && btn.addEventListener('click', function(e){
        // deixa o <a> funcionar normalmente; só limpar timer
        clearInterval(timer);
    });

    // Fechar: também redireciona para o login (comportamento seguro)
    closeBtn && closeBtn.addEventListener('click', function(){
        clearInterval(timer);
        try { window.top.location.href = redirect; } catch(e) { window.location.href = redirect; }
    });

    // Caso o modal seja injetado via AJAX ou em páginas onde body não existe yet,
    // usamos um fallback para garantir que o usuário veja a mensagem.
    setTimeout(function(){
        if (!modal || !document.body.contains(modal)) {
            try { window.top.location.href = redirect; } catch(e) { window.location.href = redirect; }
        }
    }, 3000);
})();
</script>
HTML;

    exit();
}

$_SESSION['LAST_ACTIVITY'] = time();

// Campos obrigatórios para considerar sessão válida
$camposObrigatorios = ['idUsuario', 'nomeUsuario', 'statusUsuario'];


foreach ($camposObrigatorios as $campo) {
    if (empty($_SESSION[$campo])) {
        var_dump($_SESSION);
        die("CAMPO FALTANDO: $campo");
    }
}
