<?php

require_once __DIR__ . '/../../../../controllers/RelatorioController.php';

use Controller\RelatorioController;

if (!isset($_GET['id_tipo']) || empty($_GET['id_tipo'])) {
    echo json_encode([]);
    exit;
}

$controller = new RelatorioController();
$equipamentos = $controller->listarTodosEquipamentos();

header('Content-Type: application/json');
echo json_encode($equipamentos);
