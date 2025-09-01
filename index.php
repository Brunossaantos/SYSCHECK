<?php
date_default_timezone_set('America/Sao_Paulo');

require __DIR__ . '/vendor/autoload.php';

//controladores

use controllers\ChamadoController;
use controllers\UsuarioController;
use controllers\TiposChecklistController;
use controllers\ChecklistController;
use controllers\EtapasChecklistController;
use controllers\EtapaRealizadaController;
use controllers\ObjetosChecklistController;
use controllers\FotosController;
use controllers\ListaController;
use controllers\TesteUnitarioController;
use controllers\GerenciamentoChecklistsController;
use controllers\InterfaceController;
use controllers\CombateIncendioController;

//regras de negócio
use rn\RnUsuario;
use rn\RnTipoChecklist;
use rn\RnChecklist;
use rn\RnEtapaRealizada;
use rn\RnEtapasChecklist;
use rn\RnObjeto;
use rn\RnFoto;
use rn\RnLista;
use rn\RnTesteUnitario;
use rn\RnGerenciamentoChecklists;
use rn\RnInterface;
use rn\RnChamado;
use rn\RnCombateIncendio;

// Obter a URL amigável após a reescrita
$url = isset($_GET['url']) ? $_GET['url'] : '';

// Tratar a URL para identificar o recurso e a ação
if (!empty($url)) {
    $url = rtrim($url, '/');
    $url_parts = explode('/', $url);

    // Primeiro parâmetro é o recurso (ex: usuario, veiculo)
    $recurso = isset($url_parts[0]) ? $url_parts[0] : '';

    // Segundo parâmetro é a ação (ex: cadastrarUsuario, listarVeiculos)
    $acao = isset($url_parts[1]) ? $url_parts[1] : 'index';

    // Instanciar o controlador e a RN necessária
    switch ($recurso) {
        case 'usuario':
            $controller = new UsuarioController(new RnUsuario(1));
            break;
        case 'tiposchecklist':
            $controller = new TiposChecklistController(new RnTipoChecklist(1));
            break;
        case 'checklist':
            $controller = new ChecklistController(new RnChecklist(1));
            break;
        case 'etapaschecklist':
            $controller = new EtapasChecklistController(new RnEtapasChecklist(1));
            break;
        case 'etaparealizada':
            $controller = new EtapaRealizadaController(new RnEtapaRealizada(1));
            break;
        case 'objeto':
            $controller = new ObjetosChecklistController(new RnObjeto(1));
            break;
        case 'foto':
            $controller = new FotosController(new RnFoto(1));
            break;
        case 'lista':
            $controller = new ListaController(new RnLista());
            break;
        case 'testeunitario':
            $controller = new TesteUnitarioController(new RnTesteUnitario(1));
            break;
        case 'gerenciamentochecklists':
            $controller = new GerenciamentoChecklistsController(new RnGerenciamentoChecklists(1));
            break;
        case 'interface':
            $controller = new InterfaceController((new RnInterface(1)));
            break;
        case 'chamado':
            $controller = new ChamadoController((new RnChamado(1)));
            break;
        case 'combateincendio':
            $controller = new CombateIncendioController((new RnCombateIncendio(1)));
            break;
        default:
            $controller = null;
            break;
    }

    // Verificar se o controlador foi instanciado e a ação existe
    if ($controller && method_exists($controller, $acao)) {
        // Passar parâmetros adicionais, se necessário
        $params = array_slice($url_parts, 2);
        call_user_func_array([$controller, $acao], $params);
    } else {
        echo "404 não encontrado - método não existe no controlador";
    }
} else {
    require_once 'login.php';
    exit;
}
