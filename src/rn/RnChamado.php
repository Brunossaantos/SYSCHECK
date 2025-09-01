<?php

namespace rn;

require __DIR__ .'/../../vendor/autoload.php';

use models\Chamado;
use DAO\DaoChamado;
use DAO\DaoFoto;
use database\Conexao;
use DateTime;
use Util\Sessao;

class RnChamado{
    private $idUsuarioSessao;

    function __construct($idUsuarioSessao){
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    /*
    - Receber o objeto chamado salvar ele no banco de dados e retornar o ID
    - Atribuir do ID do chamado em uma variavel e utilizar ela para salvar as fotos
    - Salvar o follow up inicial "Abertura de chamado" utilizando o id retornado
    */   
    
    function abrirChamado(Chamado $chamado, $fotos){
        $daoChamado = new DaoChamado((new Conexao())->conectar(), $this->idUsuarioSessao);    
        
        $idChamado = $daoChamado->salvarChamado($chamado);        
    
        if($idChamado > 0){           
            $pastaFotos = __DIR__ . '/../../src/views/fotos_chamados/';
            $fotosUpload = [];    
            
            foreach ($fotos['fotos']['tmp_name'] as $key => $tmpName) {
                if ($fotos['fotos']['error'][$key] === UPLOAD_ERR_OK) {
                    $arquivoOriginal = $fotos['fotos']['name'][$key];
                    $extensaoArquivo = pathinfo($arquivoOriginal, PATHINFO_EXTENSION);    
                    
                    $nomeArquivo = $idChamado."_".$key.".".$extensaoArquivo;
                    $destino = $pastaFotos . $nomeArquivo;    
                    
                    if (move_uploaded_file($tmpName, $destino)) {
                        $fotosUpload[] = [
                            'fkChamado' => $idChamado,
                            'original' => $arquivoOriginal,
                            'nome' => $nomeArquivo,
                            'path' => $destino
                        ];
                    }
                }
            }

            if (!empty($fotosUpload)) {
                foreach($fotosUpload as &$foto){
                    $foto['path'] = str_replace(__DIR__ .'/../../src/views/', "", $foto['path']);                                              
                }

                $this->salvarFotoChamado($fotosUpload);
            }
            
            $followUpInicial = [
                'fkChamado' => $idChamado,
                'fkUsuario' => Sessao::idusuario(),
                'followUp' => "Chamado aberto",
                'dataHora' => $chamado->getDataAberturaChamado()
            ];

            $idFollowUp = (new RnChamado(Sessao::idusuario()))->salvarFollowUp($followUpInicial);

            if($idChamado > 0 && $idFollowUp >0){
                header("Location:/syscheck/chamado/selecionarChamado/".$idChamado);
                exit;
            }
        }
    }    

    function selecionarChamado($fkChamado){
        return (new DaoChamado((new Conexao())->conectar(), $this->idUsuarioSessao))->selecionarChamado($fkChamado);
    }

    function atualizarChamado(){

    }

    function listarChamados(){
        $daoChamado = (new DaoChamado((new Conexao())->conectar(), $this->idUsuarioSessao));
        $listaChamados = $daoChamado->listarChamados();
        return $listaChamados;
    }

    function salvarFotoChamado($fotos){
        for($key = 0; $key < count($fotos); $key++){
            (new DaoChamado((new Conexao())->conectar(), $this->idUsuarioSessao))->salvarFoto($fotos[$key]);
        }
    }

    function listarFotosChamado($fkChamado){
        return (new DaoChamado((new Conexao())->conectar(), $this->idUsuarioSessao))->listarFotosChamado($fkChamado);
    }

    function salvarFollowUp($followup = null){
        if($followup == null){
            $datahora = (new DateTime())->format('d/m/Y H:i');
            echo "abrir tela para fazer o followup";
            return;
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $followup[] = [
                'fkChamado' => $_POST['fkchamado'],
                'fkusuario' => Sessao::idusuario(),
                'followUp' => $_POST['descricao']
            ];

            $idFollowUp = (new DaoChamado((new Conexao())->conectar(), $this->idUsuarioSessao))->salvarFollowUp($followup);
            return $idFollowUp;
        }
    }

    function listaFollowUp($fkChamado){
        return (new DaoChamado((new Conexao())->conectar(), $this->idUsuarioSessao))->listarFollowUp($fkChamado);
    }
}

?>