<?php

namespace controllers;

require __DIR__ . '/../../vendor/autoload.php';

use DAO\DaoErro;
use DAO\DaoEtapaRealizada;
use DAO\DaoFoto;
use database\Conexao;
use models\Checklist;
use rn\RnChecklist;
use rn\RnTipoChecklist;
use rn\RnEtapasChecklist;
use rn\RnObjeto;
use rn\RnFoto;
use DateTime;
use rn\RnEtapaRealizada;
use rn\RnResponsavel;
use rn\RnUsuario;
use rn\RnChamado;
use models\Chamado;
use models\Erro;
use Util\Util;
use Util\Sessao;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use rn\RnBateria;
use rn\RnHorimetro;
use rn\RnPeriferico;
use rn\RnusuarioEmpilhadeira;

class ChecklistController
{
    private $rnChecklist;

    function __construct($rnChecklist)
    {
        $this->rnChecklist = $rnChecklist;
    }



    function index()
    {
        $usuario = (new RnUsuario(Sessao::idusuario()))->selecionarUsuario(Sessao::idusuario());
        $horimetroPendente = (new UsuarioController((new RnUsuario(Sessao::idusuario()))))->verificarSeExisteHorimetroPendente(Sessao::idusuario());
        $liberarNovoChecklist = true;

        if (!empty($horimetroPendente)) {
            $liberarNovoChecklist = false;
        }

        require_once __DIR__ . '/../views/features/checklists/index.php';
    }

    function iniciarChecklist()
    {
        $usuario = (new RnUsuario(Sessao::idusuario()))->selecionarUsuario(Sessao::idusuario());
        $listaTipos = (new RnTipoChecklist(Sessao::idusuario()))->retornarListaTiposChecklist();

        if ($usuario->getUserTipoChecklist() >= 1) {
            $listaTipos = array_filter($listaTipos, function ($tipoChecklist) use ($usuario) {
                return $tipoChecklist->getIdTipoChecklist() === $usuario->getUserTipoChecklist();
            });
        }

        require_once __DIR__ . '/../views/features/checklists/checklists/iniciarchecklist.php';
    }

    function iniciarChecklistVeicular($fkUsuario, $fkObjeto)
    {
        $fkTipo = 1;
        $dataInicio = (new DateTime())->format('d/m/Y H:i:s');
        $checklist = new Checklist(1, $fkUsuario, $fkTipo, $fkObjeto, $dataInicio, "", 1);
        $idChecklist = (new RnChecklist(Sessao::idusuario()))->iniciarChecklist($checklist);
        header("Location:/syscheck/lista/");
    }

    function salvarInicioChecklist()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $checklist = new Checklist(1, $_POST['fkusuario'], $_POST['fktipo'], $_POST['fkobjeto'], $_POST['datainicio'], "", 1);

            $tipoEmpilhadeira = (new RnTipoChecklist(Sessao::idusuario()))->verificarTipoEmpilhadeira($checklist->getFkTipo());
            $fkEmpilhadeira = $checklist->getFkObjeto();

            // 🔎 Verifica horímetro aberto antes de iniciar novo checklist
            if ($this->verificarHorimetroAberto($fkEmpilhadeira)) {
                Sessao::salvarMensagemNaSessao(
                    "Empilhadeira com registro de horímetro em aberto. Solicite que o último usuário encerre o uso do equipamento ou contate o líder."
                );
                header("Location:/syscheck/");
                exit;
            }

            $idChecklist = (new RnChecklist(Sessao::idusuario()))->iniciarChecklist($checklist);

            if (!empty($tipoEmpilhadeira)) {
                $relacaoEmpilhadeira = $tipoEmpilhadeira[0];
                switch ($relacaoEmpilhadeira['FK_TIPO_EMPILHADEIRA']) {
                    case 1:
                        $this->iniciarChecklistEmpilhadeiraGas($idChecklist);
                        break;
                    case 2:
                        $this->iniciarChecklistBateriaComum($idChecklist);
                        break;
                    case 3:
                        $this->iniciarChecklistBateriaLitio($idChecklist);
                        break;
                }
            } else {
                if ($idChecklist > 0) {
                    header("Location: /syscheck/etapaschecklist/etapa/" . $idChecklist . "/" . $checklist->getFkTipo() . "/1");
                } else {
                    echo "não foi possível iniciar o checklist";
                }
            }
        }
    }

    private function verificarHorimetroAberto($fkEmpilhadeira)
    {
        $rnUsuarioEmp = new \rn\RnusuarioEmpilhadeira(Sessao::idusuario());
        $registroUso = $rnUsuarioEmp->listarHorimetrosAbertos($fkEmpilhadeira); // agora retorna a última linha ou []

        if (!empty($registroUso)) {
            $encerramento = $registroUso['DATA_HORA_ENCERRAMENTO'];
            // Verifique valores que você usa no banco para "não encerrado" (p.ex. "0" ou "")
            if ($encerramento === null || $encerramento === "" || $encerramento === "0") {
                Sessao::salvarMensagemNaSessao("Empilhadeira com registro de horímetro em aberto. Solicite que o último usuário encerre o uso do equipamento ou contate o líder.");
                header("Location:/syscheck/");
                exit;
            }
        }
    }

    function iniciarChecklistBateriaLitio($idChecklist)
    {

        /*
        //checklist bateria de litio        
        $listaBaterias = (new RnBateria(Sessao::idusuario()))->gerarListaBaterias();

        $verificacaoExpediente = (new RnusuarioEmpilhadeira(Sessao::idusuario()))->verificarChecklistAberto(Sessao::idusuario());        

        //var_dump($verificacaoExpediente);

        if(empty($verificacaoExpediente)){
            $expediente = (new RnusuarioEmpilhadeira(Sessao::idusuario()))->iniciarExpediente($idChecklist);
        }       

        require_once __DIR__ . '/../views/features/checklists/empilhadeiraeletrica/empilhadeiraeletrica.php';     
        */

        require_once __DIR__ . '/../views/features/checklists/empilhadeiraeletrica/empilhadeiraeletricabateriacomum.php';
    }


    function salvarHorimetro()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $fkChecklist = $_POST['idchecklist'];
            $fkempilhadeira = $_POST['fkempilhadeira'];
            $horimetro = $_POST['horimetro'];

            (new RnHorimetro(Sessao::idusuario()))->salvarHorimetro($fkChecklist, $fkempilhadeira, $horimetro);

            /*if((new RnHorimetro(Sessao::idusuario()))->salvarHorimetro($fkChecklist, $fkempilhadeira, $horimetro) > 0){
                
            }*/
        }
    }


    function verificarFotoPorEtapa($fkChecklist, $numeroEtapa)
    {
        $foto = (new RnFoto(Sessao::idusuario()))->selecionarFotoEtapa($fkChecklist, $numeroEtapa);

        /*echo "<pre>";
        var_dump($foto->getCaminhoFoto());*/

        if ($foto != null) {
            echo $foto->getCaminhoFoto();
        } else {
            echo "";
        }
    }


    function salvarinfobateria()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $idChecklist = $_POST['idchecklist'];
            $fkbateria = $_POST['fkbateria'];
            $nivelBateria = $_POST['nivelbateria'];

            //(new RnBateria(Sessao::idusuario()))->salvarBateriaDeUso($idChecklist, $fkbateria, $nivelBateria);

            if ((new RnBateria(Sessao::idusuario()))->salvarBateriaDeUso($idChecklist, $fkbateria, $nivelBateria)) {
                echo "sucesso";
            } else {
                echo "falhou";
            }
        }
    }

    function salvaInfoBateriaComum()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            (new RnBateria(Sessao::idusuario()))->salvarNivelBateriaComum($_POST['idchecklist'], $_POST['nivelbateria']);
        }
    }

    function iniciarChecklistBateriaComum($idChecklist)
    {

        //checklist bateria de litio        
        $listaBaterias = (new RnBateria(Sessao::idusuario()))->gerarListaBaterias();

        $verificacaoExpediente = (new RnusuarioEmpilhadeira(Sessao::idusuario()))->verificarChecklistAberto(Sessao::idusuario());

        //var_dump($verificacaoExpediente);

        if (empty($verificacaoExpediente)) {
            $expediente = (new RnusuarioEmpilhadeira(Sessao::idusuario()))->iniciarExpediente($idChecklist);
        }

        require_once __DIR__ . '/../views/features/checklists/empilhadeiraeletrica/empilhadeiraeletrica.php';

        /*
        require_once __DIR__ . '/../views/features/checklists/empilhadeiraeletrica/empilhadeiraeletricabateriacomum.php';
        */
        //header("Location: /syscheck/checklist/horimetro/".$idChecklist);
    }

    function horimetro($idChecklist)
    {
        $checklist = (new RnChecklist(Sessao::idusuario()))->selecionarChecklist($idChecklist);
        $empilhadeira = (new RnObjeto(Sessao::idusuario()))->selecionarObjeto($checklist->getFkObjeto());

        require_once __DIR__ . '/../views/features/checklists/empilhadeiras/horimetro.php';
    }

    function iniciarChecklistEmpilhadeiraGas($idChecklist)
    {

        $checklist = (new RnChecklist(Sessao::idusuario()))->selecionarChecklist($idChecklist);
        $empilhadeira = (new RnObjeto(Sessao::idusuario()))->selecionarObjeto($checklist->getFkObjeto());

        require_once __DIR__ . '/../views/features/checklists/empilhadeiras/horimetro.php';
    }

    function atualizarChecklist()
    {
        echo "Rota - atualização de checklist";
    }

    function encerrarUsoEmpilhadeiraEletrica($fkChecklist)
    {
        (new RnusuarioEmpilhadeira(Sessao::idusuario()))->encerrarExpediente($fkChecklist);
        header("Location:/syscheck/");
    }

    function finalizarChecklist($idChecklist)
    {

        $checklist = $this->rnChecklist->selecionarChecklist($idChecklist);
        $fkTipoChecklist = $checklist->getFkTipo();
        $enviarEmail = false;

        if ($checklist != null) {
            $checklist->setDataFim((new DateTime())->format('d/m/Y H:i:s'));
            $checklist->setStatusChecklist(3);

            $atualizarChecklist = $this->rnChecklist->atualizarChecklist($checklist);

            if ($atualizarChecklist > 0) {
                //echo "<pre>";
                //print_r($checklist);
                //echo "Checklist finalizado com sucesso.";

                $listaEtapasRealizadas = (new RnEtapaRealizada(Sessao::idusuario()))->montarChecklist($checklist->getIdChecklist());
                foreach ($listaEtapasRealizadas as $etapa) {
                    if ($etapa['ACAO'] != 1) {
                        $enviarEmail = true;
                        break;
                    }
                }

                if ($enviarEmail) {
                    $this->enviarEmail($idChecklist);
                }

                header("Location:/syscheck/checklist/checklistfinalizado/" . $idChecklist);
            }
        } else {
            echo "Checklist inválido";
        }
    }

    private function enviarEmail($idChecklist)
    {
        $checklist = $this->rnChecklist->selecionarChecklist($idChecklist);
        $listaEtapasRealizadas = (new RnEtapaRealizada(Sessao::idusuario()))->montarChecklist($checklist->getIdChecklist());

        //recuperar dados de usuário e item checado
        $usuario = (new RnUsuario(Sessao::idusuario()))->selecionarUsuario($checklist->getFkUsuario());
        $itemChecado = (new RnObjeto(Sessao::idusuario()))->selecionarObjeto($checklist->getFkObjeto());

        $listaEtapasReprovadas = [];
        $listaFotosApontamentos = [];
        $listaResponsaveis = [];

        //popular lista de etapas com apontamentos de e reprovadas
        foreach ($listaEtapasRealizadas as $etapa) {
            if ($etapa['ACAO'] != 1) {
                $etapaReprovada = (new RnEtapasChecklist(Sessao::idusuario()))->selecionarEtapaPeloId($etapa['FK_ETAPA']);

                $listaEtapasReprovadas[] = [
                    'TITULO' => $etapaReprovada->getTituloEtapa(),
                    'CONTEUDO' => $etapaReprovada->getConteudoEtapa(),
                    'OBSERVACAO' => $etapa['OBSERVACAO'],
                    'ACAO' => $etapa['ACAO']
                ];

                //recuperar lista de fotos
                /*$foto = (new DaoFoto((new Conexao())->conectar(), Sessao::idusuario()))->selecionarFotoEtapa($etapa['NUMERO'], $etapa['NUMERO_ETAPA']);
                $listaFotosApontamentos[] = $foto;                
                
                var_dump($listaFotosApontamentos);*/
            }
        }

        //selecionando os dados do responsavel pelo tipo do checklist
        $fkResponsavel = (new RnTipoChecklist(Sessao::idusuario()))->retornarResponsavel($checklist->getFkTipo());
        $responsavel = (new RnResponsavel(Sessao::idusuario()))->selecionarResponsavel($fkResponsavel);


        //fazer o envio do email
        $email = new PHPMailer(true);
        try {
            $email->isSMTP();
            $email->Host = 'smtplw.com.br';
            $email->SMTPAuth = true;
            $email->Username = 'udlog';
            $email->Password = 'Ti2020@#';
            $email->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $email->Port = 587;

            $email->CharSet = 'UTF-8';

            /*$email->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ];*/

            //Destinarios
            $email->setFrom('suporte.ti@udlog.com.br', 'Syscheck');
            $email->addAddress($responsavel->getEmailResponsavel(), $responsavel->getNomeResponsavel());

            //carregando o template do email
            $emailTemplate = file_get_contents(__DIR__ . '/../views/features/checklists/checklists/email_template.html');

            $emailTemplate = str_replace('{{item}}', $itemChecado->getDescricaoObjeto(), $emailTemplate);
            $emailTemplate = str_replace('{{numero_checklist}}', $checklist->getIdChecklist(), $emailTemplate);
            $emailTemplate = str_replace('{{data_inicio}}', $checklist->getDataInicio(), $emailTemplate);
            $emailTemplate = str_replace('{{data_fim}}', $checklist->getDataFim(), $emailTemplate);
            $emailTemplate = str_replace('{{usuario}}', $usuario->getNome(), $emailTemplate);

            $linhasTabela = "";

            foreach ($listaEtapasReprovadas as $etapaTemplate) {
                $linhasTabela .= "<tr>
                                    <td>{$etapaTemplate['TITULO']}</td>
                                    <td>{$etapaTemplate['CONTEUDO']}</td>
                                    <td>{$etapaTemplate['OBSERVACAO']}</td>
                                    <td>Item reprovado</td>
                                </tr>";
            }

            $emailTemplate = str_replace('{{tabela}}', $linhasTabela, $emailTemplate);

            //echo $emailTemplate;            

            //conteúdo do email
            $email->isHTML(true);
            $email->Subject = "Syscheck - {$itemChecado->getDescricaoObjeto()} - Checklist Finalizado com apontamentos";
            $email->Body = $emailTemplate;
            $email->AltBody = "Checklist finalizado com apontamentos, acesse o sistema para verificar o checklist número {$checklist->getIdChecklist()}";

            $email->send();
            //echo 'Email enviado com sucesso';
        } catch (Exception $e) {
            Util::inserirErro($e, 'enviarEmail', Sessao::idusuario());
            echo "Erro ao enviar o email: {$email->ErrorInfo}";
        }



        //debug
        //echo "<pre>";
        //var_dump($responsavel);
        //var_dump($listaEtapasReprovadas);

    }


    function enviaralertabateriabaixa()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idchecklist = $_POST['idchecklist'];
            $fkbateria = $_POST['fkbateria'];
            $nivelbateria = $_POST['nivelbateria'];
        }
    }

    function abrirChamado()
    {
        $listaPerifericos = (new RnPeriferico(Sessao::idusuario()))->listarPerifericos();
        $dataHora = (new DateTime())->format("d/m/Y H:i:s");


        require_once __DIR__ . '/../views/features/checklists/empilhadeiraeletrica/chamadosperifericos.php';
    }

    function salvarChamado()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $chamado = new Chamado(1, $_POST['itemchamado'], $_POST['descricaochamado'], $_POST['datahora'], null, Sessao::idusuario(), 1);

            $isSucces = (new RnChamado(Sessao::idusuario()))->abrirChamado($chamado);

            if ($isSucces > 0) {
                echo "chamado aberto com sucesso";
            } else {
                echo "falha ao abrir chamado";
            }
        }
    }

    function checklistFinalizado($idChecklist)
    {
        $checklist = $this->rnChecklist->selecionarChecklist($idChecklist);

        $tipo = (new RnTipoChecklist(Sessao::idusuario()))->selecionarTipoChecklist($checklist->getFkTipo());
        $empilhadeiraEletrica = false;
        $empilhadeira = false;
        $empilhadeiraBateriaComum = false;
        $item = (new RnObjeto(Sessao::idusuario()))->selecionarObjeto($checklist->getFkObjeto());
        $status = Util::statusChecklist((int) $checklist->getStatusChecklist());

        $listaEtapas = $this->montarChecklist($idChecklist);
        $listaFotos = $this->recuperarListaFotos($idChecklist);

        $listaTitulos = [];

        $usuario = (new RnUsuario(Sessao::idusuario()))->selecionarUsuario($checklist->getFkUsuario());

        foreach ($listaEtapas as $etapa) {
            if (!in_array($etapa['TITULO'], $listaTitulos)) {
                $listaTitulos[] = $etapa['TITULO'];
            }
        }

        //echo "<pre>";
        //var_dump($listaFotos);

        //$tipoChecklist = (new RnTipoChecklist(Sessao::idusuario()))->selecionarTipoChecklist($checklist->getFkTipo());
        $itemChecado = (new RnObjeto(Sessao::idusuario()))->selecionarObjeto($checklist->getFkObjeto());
        $responsavel = (new RnUsuario(Sessao::idusuario()))->selecionarUsuario($checklist->getFkUsuario());

        if ($tipo->getIdTipoChecklist() == 4 || $tipo->getIdTipoChecklist() == 3 || $tipo->getIdTipoChecklist() == 10) {
            $empilhadeira = true;
            $listaHorimetros = (new RnHorimetro(Sessao::idusuario()))->recuperarListaHorimetros($idChecklist);
        }

        if ($tipo->getIdTipoChecklist() == 10) {
            $empilhadeiraBateriaComum = true;
            $infoNivelBateria = (new RnBateria(Sessao::idusuario()))->selecionarNivelBateriaComum($idChecklist);
            $nivelBateria = $infoNivelBateria['NIVEL_BATERIA'];
        }

        if ($tipo->getIdTipoChecklist() == 3) {
            $empilhadeiraEletrica = true;
            $listaBaterias = (new RnBateria(Sessao::idusuario()))->selecionarBateriasParaChecklist($idChecklist);
        }



        //require_once __DIR__ . '/../views/features/checklists/checklists/checklistfinalizado.php';
        require_once __DIR__ . '/../views/features/checklists/checklists/teste.php';
    }

    function listarChecklists()
    {
        $filtros = [];

        $listaTipos = (new RnTipoChecklist(Sessao::idusuario()))->retornarListaTiposChecklist();
        $listaObjetos = (new RnObjeto(Sessao::idusuario()))->listarObejetos();
        $listaUsuarios = (new RnUsuario(Sessao::idusuario()))->listarUsuarios();


        if (isset($_GET['numero']) && !empty($_GET['numero'])) {
            $filtros['numero'] = $_GET['numero'];
        }

        if (isset($_GET['data_inicio']) && !empty($_GET['data_inicio'])) {
            $filtros['data_inicio'] = $_GET['data_inicio'];
        }

        if (isset($_GET['tipo']) && !empty($_GET['tipo'])) {
            $filtros['tipo'] = $_GET['tipo'];
        }

        if (isset($_GET['objeto']) && !empty($_GET['objeto'])) {
            $filtros['objeto'] = $_GET['objeto'];
        }

        if (isset($_GET['usuario']) && !empty($_GET['usuario'])) {
            $filtros['usuario'] = $_GET['usuario'];
        }

        if (isset($_GET['status']) && !empty($_GET['status'])) {
            $filtros['status'] = $_GET['status'];
        } else {
            $filtros['status'] = 0;
        }

        if (!empty($filtros)) {
            $listaChecklists = $this->rnChecklist->listarComFiltros($filtros);
        } else {
            $listaChecklists = $this->rnChecklist->listarChecklists();
        }

        require_once __DIR__ . '/../views/features/checklists/checklists/listachecklists.php';
    }


    function montarChecklist(int $idChecklist)
    {
        $listaEtapas = (new RnEtapaRealizada(Sessao::idusuario()))->montarChecklist($idChecklist);
        return $listaEtapas;
    }

    function recuperarListaFotos(int $idChecklist)
    {
        $listaFotos = (new RnFoto(Sessao::idusuario()))->selecionarFotoChecklist($idChecklist);
        return $listaFotos;
    }

    function listarItens()
    {
        $url = $_SERVER['REQUEST_URI'];
        $partes = explode('/', $url);
        $fkTipo = end($partes);

        if (is_numeric($fkTipo)) {
            $listaObjetos = (new RnObjeto(Sessao::idusuario()))->listarObjetosPeloTipo($fkTipo);

            $option = '';

            foreach ($listaObjetos as $objeto) {
                if ($objeto->getStatusObjeto() > 0) {
                    $option .= '<option value="' . htmlspecialchars($objeto->getIdObjeto()) . '">' . htmlspecialchars($objeto->getDescricaoObjeto()) . '</option>';
                }
            }

            header('Content-Type: text/html');
            echo $option;
        } else {
            header('HTTP/1.1 400 Bad Request');
            echo '<option value="">ID inválido</option>';
        }
    }
}
