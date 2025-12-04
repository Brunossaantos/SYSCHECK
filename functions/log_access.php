<?php
require_once __DIR__ . '/log.php';

function logAcessoPagina()
{
    $rota = $_SERVER['REQUEST_URI'] ?? 'rota_desconhecida';
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'ip_desconhecido';
    $user = $_SESSION['idUsuario'] ?? 'desconhecido';

    registrarLog("Acesso à página '$rota' | Usuário: $user | IP: $ip", "ACCESS");
}