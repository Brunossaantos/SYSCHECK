<?php

namespace controllers;

include_once __DIR__ .'/../../vendor/autoload.php';

use DateTime;
use models\Chamado;
use rn\RnChamado;
use rn\RnObjeto;
use rn\RnTipoChecklist;
use rn\RnUsuario;
use Util\Sessao;

class ChamadoController{
    private $rnChamado;

    function __construct($rnChamado){
        $this->rnChamado = $rnChamado;
    }

    function index(){
        echo "Controlador funcionando";
    }

    function abrirChamado(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $fkUsuario = $_POST['fkusuario'];
            $fkTipo = $_POST['fktipo'];
            $fkEquipamento = $_POST['fkequipamento'];
            $descChamado = $_POST['deschamado'];            
            $dataHora = $_POST['datahora'];            

            $chamado = new Chamado(0, $fkEquipamento, $descChamado, $dataHora, null, $fkUsuario, 1);
            
            (new RnChamado(Sessao::idusuario()))->abrirChamado($chamado, $_FILES);

        } else {
            $listaTipos = (new RnTipoChecklist(Sessao::idusuario()))->retornarListaTiposChecklist();
            $listaEquipamentos = (new RnObjeto(Sessao::idusuario()))->listarObejetos();
            $dataHora = (new DateTime())->format('d/m/Y H:i');
            $usuario = (new RnUsuario(Sessao::idusuario()))->selecionarUsuario(Sessao::idusuario());
            
            require_once __DIR__ . '/../views/features/chamados/abrirchamado.php';
        }
    }

    function gerenciarChamados(){
        $listaChamados = (new RnChamado(Sessao::idusuario()))->listarChamados();
        $rnUsuario = new RnUsuario(Sessao::idusuario());
        $rnObjeto = new RnObjeto(Sessao::idusuario());

        
        require_once __DIR__ . '/../views/features/chamados/gerenciarchamados.php';
    }

    function followupChamado(){
        require_once __DIR__ . '/../views/features/chamados/followupchamado.php';
    }

    function selecionarChamado($fkChamado){
        $rnChamado = (new RnChamado(Sessao::idusuario()));
        $rnUsuario = (new RnUsuario(Sessao::idusuario()));
        $chamado = $rnChamado->selecionarChamado($fkChamado);
        $listaFollowUp = $rnChamado->listaFollowUp($fkChamado);
        $listaFotos = $rnChamado->listarFotosChamado($fkChamado);

        $usuario = $rnUsuario->selecionarUsuario($chamado->getFkUsuario());
        $equipamento = (new RnObjeto(Sessao::idusuario()))->selecionarObjeto($chamado->getFkItemChamado());    
        
        foreach($listaFollowUp as &$followUp){
            $followUp['fkUsuario'] = $rnUsuario->selecionarUsuario($followUp['fkUsuario']);
        }
        
        require_once __DIR__ . '/../views/features/chamados/followupchamado.php';

    }

    

    
}

?>