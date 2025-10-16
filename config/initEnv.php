<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Carrega as variáveis do arquivo .env na raiz do projeto
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
