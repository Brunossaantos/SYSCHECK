<?php

namespace controllers;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/initEnv.php';

use DAO\DaoBaterias;
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
use service\PermissaoService;

/** @var mixed $fkEmpilhadeira */
/** @var mixed $idChecklist */
/** @var int $fkUsuario */



class ChecklistController
{
    private $conexao;
    private $rnChecklist;
    private $idUsuarioSessao;
    private $idEmpresaSessao;

    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $this->idUsuarioSessao = (int) ($_SESSION['idUsuario'] ?? 0);
        $this->idEmpresaSessao = (int) ($_SESSION['fkEmpresa'] ?? 0);

        if ($this->idUsuarioSessao <= 0 || $this->idEmpresaSessao <= 0) {

            session_destroy();

            header("Location: /syscheck/login.php");
            exit;
        }
        $this->rnChecklist = new RnChecklist(
            $this->idUsuarioSessao,
            $this->idEmpresaSessao
        );
    }

    function index()
    {
        $idUsuario = Sessao::idusuario();

        $usuario = (new RnUsuario($idUsuario))->selecionarUsuario($idUsuario);

        $rnChecklist = new RnChecklist(
            $this->idUsuarioSessao,
            $this->idEmpresaSessao
        );
        $rnUsuarioEmp = new RnusuarioEmpilhadeira($idUsuario);

        // 🔹 Horímetro pendente (nova regra)
        $horimetroPendente = $this->rnChecklist->verificarSeExisteHorimetroPendente();

        // 🔹 Expediente aberto (regra antiga)
        $checklistAberto = $rnUsuarioEmp->verificarChecklistAberto($idUsuario);
        $existeChecklistAberto = !empty($checklistAberto);

        require_once __DIR__ . '/../views/features/checklists/index.php';
    }

    public function listar(): void
    {
        try {
            $checklists = $this->rnChecklist->listarChecklists();
            require_once __DIR__ . '/../views/features/checklists/index.php';
        } catch (\Exception $e) {
            Util::inserirErro($e, "ChecklistController::listar", Sessao::idusuario());
            echo "Erro ao carregar checklists.";
        }
    }

    function iniciarChecklist()
    {
        $idUsuario = \Util\Sessao::idusuario();
        $idEmpresa = \Util\Sessao::idempresa();

        // 🔹 Perfil
        $conexao = (new \database\Conexao())->conectar();
        $permissao = new \service\PermissaoService(
            $conexao,
            $idUsuario,
            $idEmpresa
        );

        $perfilId = $permissao->getPerfil();

        // 🔹 Tipos
        $rnTipoChecklist = new \rn\RnTipoChecklist($idUsuario);
        $listaTipos = $rnTipoChecklist->retornarListaTiposChecklist();

        // 🔹 Aplicar filtro por perfil
        $rnChecklist = new \rn\RnChecklist($idUsuario, $idEmpresa);
        $listaTipos = $rnChecklist->filtrarTiposPorPerfil($listaTipos, $perfilId);

        require_once __DIR__ . '/../views/features/checklists/checklists/iniciarchecklist.php';
    }
    function iniciarChecklistVeicular($fkUsuario, $fkObjeto)
    {
        $fkTipo = 1; // ID válido para veicular
        $dataInicio = (new DateTime())->format('d/m/Y H:i:s');

        $usuarioObj = (new RnUsuario(Sessao::idusuario()))->selecionarUsuario($fkUsuario);

        $checklist = new Checklist(
            1,
            $usuarioObj,
            $fkUsuario,
            $fkTipo,       // ✅ agora sempre válido
            $fkObjeto,
            $dataInicio,
            "",
            1
        );

        $idChecklist = $this->rnChecklist->iniciarChecklist($checklist);

        header("Location:/syscheck/lista/");
        exit;
    }

    function salvarInicioChecklist()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $fkUsuario = $_POST['fkusuario'] ?? 0;
        $fkTipo    = $_POST['fktipo'] ?? 0;
        $fkObjeto  = $_POST['fkobjeto'] ?? 0;
        $dataInicio = $_POST['datainicio'] ?? (new DateTime())->format('d/m/Y H:i:s');

        // IDs válidos da tabela tbl_tipos_checklist
        $tiposValidos = [1, 2, 3, 4, 6, 14];
        if (!in_array($fkTipo, $tiposValidos)) {
            Sessao::salvarMensagemNaSessao("Tipo de checklist inválido: {$fkTipo}");
            header("Location:/syscheck/");
            exit;
        }

        // Validar usuário
        $usuarioObj = (new RnUsuario(Sessao::idusuario()))->selecionarUsuario($fkUsuario);
        if (!$usuarioObj) {
            Sessao::salvarMensagemNaSessao("Usuário inválido.");
            header("Location:/syscheck/");
            exit;
        }

        // Validar tipo de checklist
        $tipoChecklist = (new RnTipoChecklist(Sessao::idusuario()))->selecionarTipoChecklist($fkTipo);
        if (!$tipoChecklist) {
            Sessao::salvarMensagemNaSessao("Tipo de checklist inválido.");
            header("Location:/syscheck/");
            exit;
        }

        // Criar checklist
        $checklist = new Checklist(
            1,
            $usuarioObj,
            $fkUsuario,
            $tipoChecklist->getIdTipoChecklist(),
            $fkObjeto,
            $dataInicio,
            "",
            1
        );

        // Verificar horímetro aberto (se for empilhadeira)
        $fkEmpilhadeira = $checklist->getFkObjeto();
        if ($this->verificarHorimetroAberto($fkEmpilhadeira)) {
            Sessao::salvarMensagemNaSessao(
                "Empilhadeira com registro de horímetro em aberto. Solicite que o último usuário encerre o uso do equipamento."
            );
            header("Location:/syscheck/");
            exit;
        }

        // Iniciar checklist
        $idChecklist = $this->rnChecklist->iniciarChecklist($checklist);

        // 🔎 Verifica se é tipo empilhadeira
        $tipoEmpilhadeira = (new RnTipoChecklist(Sessao::idusuario()))
            ->verificarTipoEmpilhadeira($checklist->getFkTipo());

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

            // outros tipos (ex: veicular)
            header("Location: /syscheck/etapaschecklist/executarChecklist/{$idChecklist}/{$fkTipo}/1");
        }

        exit;
    }

    /**
     * Verifica se a empilhadeira possui horímetro aberto
     * Retorna true se houver horímetro aberto
     */
    private function verificarHorimetroAberto($fkEmpilhadeira)
    {
        $rnUsuarioEmp = new RnusuarioEmpilhadeira(Sessao::idusuario());
        if (!empty($registroUso)) {
            foreach ($registroUso as $registro) {
                if (empty($registro['DATA_HORA_ENCERRAMENTO'])) {
                    return true;
                }
            }
        }

        if (!empty($registroUso)) {
            $encerramento = $registroUso['DATA_HORA_ENCERRAMENTO'] ?? null;
            if (empty($encerramento) || $encerramento === "0") {
                return true;
            }
        }
        return false;
    }

    function iniciarChecklistBateriaLitio($idChecklist)
    {
        // Lista de baterias de lítio
        $listaBaterias = (new RnBateria(Sessao::idusuario()))->gerarListaBaterias();

        // Verifica se o usuário tem checklist aberto
        $verificacaoExpediente = (new RnusuarioEmpilhadeira(Sessao::idusuario()))
            ->verificarChecklistAberto(Sessao::idusuario());

        // Inicia expediente se não houver checklist aberto
        if (empty($verificacaoExpediente)) {
            (new RnusuarioEmpilhadeira(Sessao::idusuario()))
                ->iniciarExpediente($idChecklist);
        }

        // Carrega view específica para bateria de lítio
        require_once __DIR__ . '/../views/features/checklists/empilhadeiraeletrica/empilhadeiraeletricabateriacomum.php';
    }

    function iniciarChecklistBateriaComum($idChecklist)
    {
        // Lista de baterias comuns
        $listaBaterias = (new RnBateria(Sessao::idusuario()))->gerarListaBaterias();

        $verificacaoExpediente = (new RnusuarioEmpilhadeira(Sessao::idusuario()))
            ->verificarChecklistAberto(Sessao::idusuario());

        if (empty($verificacaoExpediente)) {
            (new RnusuarioEmpilhadeira(Sessao::idusuario()))
                ->iniciarExpediente($idChecklist);
        }

        require_once __DIR__ . '/../views/features/checklists/empilhadeiraeletrica/empilhadeiraeletrica.php';
    }

    function salvarHorimetro()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $fkChecklist = $_POST['idchecklist'] ?? 0;
        $fkEmpilhadeira = $_POST['fkempilhadeira'] ?? 0;
        $horimetro = $_POST['horimetro'] ?? 0;

        if ($fkChecklist && $fkEmpilhadeira && $horimetro !== null) {
            (new RnHorimetro(Sessao::idusuario()))
                ->salvarHorimetro($fkChecklist, $fkEmpilhadeira, $horimetro);
            echo "sucesso";
        } else {
            echo "dados inválidos";
        }
    }

    function verificarFotoPorEtapa($fkChecklist, $numeroEtapa)
    {
        $foto = (new RnFoto(Sessao::idusuario()))
            ->selecionarFotoEtapa($fkChecklist, $numeroEtapa);

        echo ($foto != null) ? $foto->getCaminhoFoto() : "";
    }

    function salvarinfobateria()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $idChecklist = $_POST['idchecklist'] ?? 0;
        $fkBateria = $_POST['fkbateria'] ?? 0;
        $nivelBateria = $_POST['nivelbateria'] ?? 0;

        if ((new RnBateria(Sessao::idusuario()))
            ->salvarBateriaDeUso($idChecklist, $fkBateria, $nivelBateria)
        ) {
            echo "sucesso";
        } else {
            echo "falhou";
        }
    }

    function salvaInfoBateriaComum()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $idChecklist = $_POST['idchecklist'] ?? 0;
        $nivelBateria = $_POST['nivelbateria'] ?? 0;

        if ($idChecklist && $nivelBateria !== null) {
            (new RnBateria(Sessao::idusuario()))
                ->salvarNivelBateriaComum($idChecklist, $nivelBateria);
            echo "sucesso";
        } else {
            echo "falhou";
        }
    }

    function horimetro($idChecklist)
    {
        $checklist = (new RnChecklist($this->idUsuarioSessao, $this->idEmpresaSessao))
            ->selecionarChecklist($idChecklist);

        $empilhadeira = (new RnObjeto(Sessao::idusuario()))
            ->selecionarObjeto($checklist->getFkObjeto());

        // pegar lista de horímetros já registrados
        $listaHorimetros = (new RnHorimetro(Sessao::idusuario()))
            ->recuperarListaHorimetros($idChecklist);

        // pegar último
        $ultimoHorimetro = 0;

        if (!empty($listaHorimetros)) {
            $ultimo = end($listaHorimetros);
            $ultimoHorimetro = $ultimo['HORIMETRO'] ?? 0;
        }

        require_once __DIR__ . '/../views/features/checklists/empilhadeiras/horimetro.php';
    }
    function iniciarChecklistEmpilhadeiraGas($idChecklist)
    {
        $checklist = (new RnChecklist($this->idUsuarioSessao, $this->idEmpresaSessao))
            ->selecionarChecklist($idChecklist);

        $empilhadeira = (new RnObjeto(Sessao::idusuario()))
            ->selecionarObjeto($checklist->getFkObjeto());

        require_once __DIR__ . '/../views/features/checklists/empilhadeiras/horimetro.php';
    }

    function atualizarChecklist()
    {
        echo "Rota - atualização de checklist";
    }

    function encerrarUsoEmpilhadeiraEletrica($fkChecklist)
    {
        (new RnusuarioEmpilhadeira(Sessao::idusuario()))
            ->encerrarExpediente($fkChecklist);

        header("Location:/syscheck/");
        exit;
    }

    function finalizarChecklist($idChecklist)
    {
        $checklist = $this->rnChecklist->selecionarChecklist($idChecklist);
        if (!$checklist) {
            echo "Checklist inválido";
            return;
        }

        $checklist->setDataFim((new \DateTime())->format('d/m/Y H:i:s'));
        $checklist->setStatusChecklist(3);

        $atualizarChecklist = $this->rnChecklist->atualizarChecklist($checklist);

        if ($atualizarChecklist > 0) {

            // 🔵 ENCERRA EXPEDIENTE DA EMPILHADEIRA
            (new RnusuarioEmpilhadeira(Sessao::idusuario()))
                ->encerrarExpediente($idChecklist);

            $enviarEmail = false;

            $listaEtapasRealizadas = (new RnEtapaRealizada(Sessao::idusuario()))
                ->montarChecklist($checklist->getIdChecklist());

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
            exit;
        } else {
            echo "Falha ao atualizar checklist";
        }
    }
    private function enviarEmail($idChecklist)
    {
        $checklist = $this->rnChecklist->selecionarChecklist($idChecklist);
        if (!$checklist) {
            echo "Checklist inválido";
            return;
        }

        $listaEtapasRealizadas = (new RnEtapaRealizada(Sessao::idusuario()))
            ->montarChecklist($checklist->getIdChecklist());

        $usuario = (new RnUsuario(Sessao::idusuario()))
            ->selecionarUsuario($checklist->getFkUsuario());
        $itemChecado = (new RnObjeto(Sessao::idusuario()))
            ->selecionarObjeto($checklist->getFkObjeto());

        $listaEtapasReprovadas = [];

        foreach ($listaEtapasRealizadas as $etapa) {
            if ($etapa['ACAO'] != 1) {
                $etapaReprovada = (new RnEtapasChecklist(Sessao::idusuario()))
                    ->selecionarEtapaPeloId($etapa['FK_ETAPA']);

                $listaEtapasReprovadas[] = [
                    'TITULO' => $etapaReprovada->getTituloEtapa(),
                    'CONTEUDO' => $etapaReprovada->getConteudoEtapa(),
                    'OBSERVACAO' => $etapa['OBSERVACAO'],
                    'ACAO' => $etapa['ACAO']
                ];
            }
        }

        $fkResponsavel = (new RnTipoChecklist(Sessao::idusuario()))
            ->retornarResponsavel($checklist->getFkTipo());
        $responsavel = (new RnResponsavel(Sessao::idusuario()))
            ->selecionarResponsavel($fkResponsavel);

        $email = new PHPMailer(true);
        try {
            $email->isSMTP();
            $email->Host       = $_ENV['SMTP_HOST'];
            $email->SMTPAuth   = true;
            $email->Username   = $_ENV['SMTP_USER'];
            $email->Password   = $_ENV['SMTP_PASS'];
            $email->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $email->Port       = $_ENV['SMTP_PORT'];
            $email->CharSet    = $_ENV['SMTP_CHARSET'];

            $email->setFrom('suporte.ti@udlog.com.br', 'Syscheck');

            switch ((int)$fkResponsavel) {
                case 1: // VEICULAR
                    $email->addAddress('priscila.braz@udlog.com.br', 'Priscila');
                    $email->addAddress('jessika.rodrigues@udlog.com.br', 'Jessika');
                    $email->addAddress('ronaldo.cruz@udlog.com.br', 'Ronaldo');
                    break;
                case 2: // TI
                    $email->addAddress('suporte.ti@udlog.com.br', 'TI');
                    break;
                case 3: // EMPILHADEIRAS
                    $email->addAddress('priscila.braz@udlog.com.br', 'Priscila');
                    break;
                default:
                    throw new Exception(
                        "FK_RESPONSAVEL ({$fkResponsavel}) sem regra de e-mail definida."
                    );
            }

            $emailTemplate = file_get_contents(
                __DIR__ . '/../views/features/checklists/checklists/email_template.html'
            );

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

            $email->isHTML(true);
            $email->Subject = "Syscheck - {$itemChecado->getDescricaoObjeto()} - Checklist Finalizado com apontamentos";
            $email->Body    = $emailTemplate;
            $email->AltBody = "Checklist finalizado com apontamentos. Verifique o checklist número {$checklist->getIdChecklist()} no sistema.";

            $email->send();
        } catch (Exception $e) {
            Util::inserirErro($e, 'enviarEmail', Sessao::idusuario());
            echo "Erro ao enviar o email: {$email->ErrorInfo}";
        }
    }

    function enviaralertabateriabaixa()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $idChecklist = $_POST['idchecklist'] ?? 0;
        $fkBateria = $_POST['fkbateria'] ?? 0;
        $nivelBateria = $_POST['nivelbateria'] ?? 0;

        if ($idChecklist && $fkBateria && $nivelBateria !== null) {
            // 🔹 Obter conexão e idUsuarioSessao
            $conexao = (new Conexao())->conectar();
            $idUsuarioSessao = Sessao::idusuario();

            // 🔹 Criar DAO com os argumentos corretos
            $dao = new DaoBaterias($conexao, $idUsuarioSessao);

            $alerta = [
                'fk_checklist' => $idChecklist,
                'fk_bateria' => $fkBateria,
                'nivel_bateria' => $nivelBateria,
                'data_alerta' => (new DateTime())->format('Y-m-d H:i:s'),
                'id_usuario' => $idUsuarioSessao
            ];

            $dao->inserirAlerta($alerta);

            echo "alerta enviado";
        } else {
            echo "dados inválidos";
        }
    }

    function abrirChamado()
    {
        $listaPerifericos = (new RnPeriferico(Sessao::idusuario()))
            ->listarPerifericos();
        $dataHora = (new DateTime())->format("d/m/Y H:i:s");

        require_once __DIR__ . '/../views/features/checklists/empilhadeiraeletrica/chamadosperifericos.php';
    }

    function salvarChamado()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $itemChamado = $_POST['itemchamado'] ?? '';
        $descricaoChamado = $_POST['descricaochamado'] ?? '';
        $dataHora = $_POST['datahora'] ?? (new DateTime())->format("d/m/Y H:i:s");

        $chamado = new Chamado(1, $itemChamado, $descricaoChamado, $dataHora, null, Sessao::idusuario(), 1);

        $isSuccess = (new RnChamado(Sessao::idusuario()))->abrirChamado($chamado);

        echo ($isSuccess > 0) ? "chamado aberto com sucesso" : "falha ao abrir chamado";
    }

    function checklistFinalizado($idChecklist)
    {
        $checklist = $this->rnChecklist->selecionarChecklist($idChecklist);

        if (!$checklist) {
            echo "Checklist inválido";
            exit;
        }

        $fkTipoChecklist = $checklist->getFkTipo();
        $fkObjeto = $checklist->getFkObjeto();

        // Validar tipo de checklist
        $tipo = (new RnTipoChecklist(Sessao::idusuario()))
            ->selecionarTipoChecklist($fkTipoChecklist);

        if (!$tipo) {
            $objeto = (new RnObjeto(Sessao::idusuario()))->selecionarObjeto($checklist->getFkObjeto());
            $fkObjeto = $checklist->getFkObjeto();
            $fkTipoDoObjeto = $objeto ? $objeto->getFkTipoChecklist() : 'n/a';
            echo "Tipo de checklist inválido. FK_TIPO recebido: {$fkTipoChecklist} para checklist ID: {$idChecklist}. ";
            echo "Objeto ID: {$fkObjeto} com FK_TIPO_OBJETO: {$fkTipoDoObjeto}";
            exit;
        }

        $empilhadeiraEletrica = false;
        $empilhadeira = false;
        $empilhadeiraBateriaComum = false;

        $item = (new RnObjeto(Sessao::idusuario()))
            ->selecionarObjeto($checklist->getFkObjeto());

        $status = Util::statusChecklist((int)$checklist->getStatusChecklist());

        $listaEtapas = $this->montarChecklist($idChecklist);
        $listaFotos = $this->recuperarListaFotos($idChecklist);

        $listaTitulos = [];
        $usuario = (new RnUsuario(Sessao::idusuario()))
            ->selecionarUsuario($checklist->getFkUsuario());

        foreach ($listaEtapas as $etapa) {
            if (!in_array($etapa['TITULO'], $listaTitulos)) {
                $listaTitulos[] = $etapa['TITULO'];
            }
        }

        $itemChecado = (new RnObjeto(Sessao::idusuario()))
            ->selecionarObjeto($checklist->getFkObjeto());
        $responsavel = (new RnUsuario(Sessao::idusuario()))
            ->selecionarUsuario($checklist->getFkUsuario());

        // Checklist de empilhadeira
        if (in_array($tipo->getIdTipoChecklist(), [3, 4, 10])) {
            $empilhadeira = true;
            $listaHorimetros = (new RnHorimetro(Sessao::idusuario()))
                ->recuperarListaHorimetros($idChecklist);
        }

        // Checklist bateria comum
        if ($tipo->getIdTipoChecklist() == 10) {
            $empilhadeiraBateriaComum = true;
            $infoNivelBateria = (new RnBateria(Sessao::idusuario()))
                ->selecionarNivelBateriaComum($idChecklist);
            $nivelBateria = $infoNivelBateria['NIVEL_BATERIA'] ?? null;
        }

        // Checklist empilhadeira elétrica
        if ($tipo->getIdTipoChecklist() == 3) {
            $empilhadeiraEletrica = true;
            $listaBaterias = (new RnBateria(Sessao::idusuario()))
                ->selecionarBateriasParaChecklist($idChecklist);
        }

        require_once __DIR__ . '/../views/features/checklists/checklists/checklistfinalizado.php';
    }

    public function listarChecklists()
    {
        $filtros = [];

        $listaTipos    = (new RnTipoChecklist(Sessao::idusuario()))->retornarListaTiposChecklist();
        $listaObjetos  = (new RnObjeto(Sessao::idusuario()))->listarObjetos();
        $listaUsuarios = (new RnUsuario(Sessao::idusuario()))->listarUsuarios();

        // Filtros via GET
        $filtros['numero'] = $_GET['numero'] ?? null;
        $filtros['data_inicio'] = $_GET['data_inicio'] ?? null;
        $filtros['tipo'] = $_GET['tipo'] ?? null;
        $filtros['objeto'] = $_GET['objeto'] ?? null;
        $filtros['usuario'] = $_GET['usuario'] ?? null;
        $filtros['status'] = $_GET['status'] ?? 0;

        $listaChecklists = !empty(array_filter($filtros))
            ? $this->rnChecklist->listarComFiltros($filtros)
            : $this->rnChecklist->listarChecklists();

        require_once __DIR__ . '/../views/features/checklists/checklists/listachecklists.php';
    }

    function montarChecklist(int $idChecklist)
    {
        return (new RnEtapaRealizada(Sessao::idusuario()))
            ->montarChecklist($idChecklist);
    }

    function recuperarListaFotos(int $idChecklist)
    {
        return (new RnFoto(Sessao::idusuario()))
            ->selecionarFotoChecklist($idChecklist);
    }

    function listarItens()
    {
        $url = $_SERVER['REQUEST_URI'];
        $partes = explode('/', $url);
        $fkTipo = end($partes);

        if (!is_numeric($fkTipo)) {
            header('HTTP/1.1 400 Bad Request');
            echo '<option value="">ID inválido</option>';
            return;
        }

        $usuario = Sessao::retornarUsuarioLogado();
        $fkEmpresa = $usuario->getFkEmpresa();

        $listaObjetos = (new RnObjeto(Sessao::idusuario()))
            ->listarObjetosPeloTipo($fkTipo);

        $option = '';
        foreach ($listaObjetos as $objeto) {
            if ($objeto->getStatusObjeto() > 0) {
                $option .= '<option value="' . htmlspecialchars($objeto->getIdObjeto()) . '">'
                    . htmlspecialchars($objeto->getDescricaoObjeto())
                    . '</option>';
            }
        }

        header('Content-Type: text/html');
        echo $option;
    }

    public function finalizarChecklistAsync($idChecklist)
    {
        // lógica de finalizar checklist igual ao método normal
        http_response_code(200);
        echo json_encode(['success' => true]);
    }
}
