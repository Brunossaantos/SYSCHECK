<?php

// =======================
//  FUNÇÃO PRINCIPAL DE LOG
// =======================
function registrarLog($mensagem, $nivel = 'ERROR')
{
    $logFile = __DIR__ . '/../logs/system.log';
    $logDir = dirname($logFile);

    // Cria pasta /logs se não existir
    if (!file_exists($logDir)) {
        mkdir($logDir, 0777, true);
    }

    // Cria o arquivo se não existir
    if (!file_exists($logFile)) {
        file_put_contents($logFile, "");
    }

    $nivel = strtoupper($nivel);
    $data = date('Y-m-d H:i:s');
    $linha = "[$data] [$nivel] $mensagem" . PHP_EOL;

    file_put_contents($logFile, $linha, FILE_APPEND);
}



// =======================
//  SESSÃO (para alguns erros aparecerem com usuário)
// =======================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuario = $_SESSION['nomeUsuario'] ?? 'DESCONHECIDO';



// =======================
//  CAPTURAR TODOS OS TIPOS DE ERRO PHP
// =======================
set_error_handler(function ($errno, $errstr, $errfile, $errline) use ($usuario) {

    $descricaoTipo = match ($errno) {
        E_ERROR => "Fatal Error",
        E_WARNING => "Warning",
        E_PARSE => "Parse Error",
        E_NOTICE => "Notice",
        E_CORE_ERROR => "Core Error",
        E_CORE_WARNING => "Core Warning",
        E_COMPILE_ERROR => "Compile Error",
        E_COMPILE_WARNING => "Compile Warning",
        E_USER_ERROR => "User Error",
        E_USER_WARNING => "User Warning",
        E_USER_NOTICE => "User Notice",
        E_STRICT => "Strict Standards",
        E_RECOVERABLE_ERROR => "Recoverable Error",
        E_DEPRECATED => "Deprecated",
        E_USER_DEPRECATED => "User Deprecated",
        default => "Outro Tipo de Erro"
    };

    registrarLog(
        "[$descricaoTipo] Usuário: $usuario | $errstr - Arquivo: $errfile - Linha: $errline",
        "ERROR"
    );
});



// =======================
//  CAPTURAR EXCEÇÕES NÃO TRATADAS
// =======================
set_exception_handler(function ($ex) use ($usuario) {
    registrarLog(
        "EXCEÇÃO NÃO TRATADA: Usuário: $usuario | " .
        $ex->getMessage() .
        " - Arquivo: " . $ex->getFile() .
        " - Linha: " . $ex->getLine(),
        "ERROR"
    );
});