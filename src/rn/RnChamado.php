<?php

namespace rn;

require __DIR__ . '/../../vendor/autoload.php';

use models\Chamado;
use DAO\DaoChamado;
use DAO\DaoFoto;
use database\Conexao;
use DateTime;
use Util\Sessao;

class RnChamado
{
    private $idUsuarioSessao;

    function __construct($idUsuarioSessao)
    {
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    /*
    - Receber o objeto chamado salvar ele no banco de dados e retornar o ID
    - Atribuir do ID do chamado em uma variavel e utilizar ela para salvar as fotos
    - Salvar o follow up inicial "Abertura de chamado" utilizando o id retornado
    */

    function abrirChamado(Chamado $chamado, $fotos = null)
{
    $daoChamado = new DaoChamado((new Conexao())->conectar(), $this->idUsuarioSessao);

    $idChamado = $daoChamado->salvarChamado($chamado);

    if (!($idChamado > 0)) {
        // não salvou, sai
        return false;
    }

    $pastaFotos = __DIR__ . '/../../src/views/fotos_chamados/';
    if (!is_dir($pastaFotos)) {
        @mkdir($pastaFotos, 0755, true);
    }

    $fotosUpload = [];

    // Normaliza entrada de arquivos (suporta input name="fotos[]" ou input single file)
    if (isset($fotos['fotos']['tmp_name']) && is_array($fotos['fotos']['tmp_name'])) {
        $tmpNames = $fotos['fotos']['tmp_name'];
        $errors   = $fotos['fotos']['error'];
        $names    = $fotos['fotos']['name'];
    } elseif (isset($fotos['tmp_name'])) {
        $tmpNames = [$fotos['tmp_name']];
        $errors   = [$fotos['error']];
        $names    = [$fotos['name']];
    } else {
        $tmpNames = [];
    }

    foreach ($tmpNames as $key => $tmpName) {
        if (isset($errors[$key]) && $errors[$key] === UPLOAD_ERR_OK && !empty($tmpName)) {
            $arquivoOriginal = isset($names[$key]) ? $names[$key] : ('foto_' . $key);
            $extensaoArquivo = pathinfo($arquivoOriginal, PATHINFO_EXTENSION);
            $nomeArquivo = $idChamado . "_" . $key . ($extensaoArquivo ? "." . $extensaoArquivo : "");
            $destino = $pastaFotos . $nomeArquivo;

            if (@move_uploaded_file($tmpName, $destino)) {
                $fotosUpload[] = [
                    'fkChamado' => $idChamado,
                    'original'  => $arquivoOriginal,
                    'nome'      => $nomeArquivo,
                    'path'      => $destino
                ];
            } else {
                error_log("Falha ao mover upload para {$destino}");
            }
        }
    }

   
    if (!empty($fotosUpload)) {
        foreach ($fotosUpload as &$foto) {
            $foto['path'] = str_replace(__DIR__ . '/../../src/views/', "", $foto['path']);
        }
        unset($foto);
        
        $this->salvarFotoChamado($fotosUpload);
    }

    
    $followUpInicial = [
        'fkChamado' => $idChamado,
        'fkUsuario' => Sessao::idusuario(),
        'desc'      => "Chamado aberto"
    ];

    
    $idFollowUp = $daoChamado->salvarFollowup($followUpInicial);

    if ($idFollowUp <= 0) {
        
        error_log("Falha ao salvar follow-up inicial para chamado {$idChamado}");
    }

    
    try {
        $usuarioObj = (new \rn\RnUsuario(Sessao::idusuario()))->selecionarUsuario($chamado->getFkUsuario());
    } catch (\Throwable $e) {
        $usuarioObj = null;
        error_log("Erro ao buscar usuário: " . $e->getMessage());
    }

    try {
        $equipamentoObj = (new \rn\RnObjeto(Sessao::idusuario()))->selecionarObjeto($chamado->getFkItemChamado());
    } catch (\Throwable $e) {
        $equipamentoObj = null;
        error_log("Erro ao buscar equipamento: " . $e->getMessage());
    }

    
    $usuarioNome = 'Não informado';
    if (is_object($usuarioObj) && method_exists($usuarioObj, 'getNome')) {
        try { $usuarioNome = $usuarioObj->getNome(); } catch (\Throwable $e) { /* keep default */ }
    }

    $equipamentoDesc = 'Não informado';
    if (is_object($equipamentoObj) && method_exists($equipamentoObj, 'getDescricaoObjeto')) {
        try { $equipamentoDesc = $equipamentoObj->getDescricaoObjeto(); } catch (\Throwable $e) { /* keep default */ }
    }

    
    $dataEmail = $chamado->getDataAberturaChamado();
    $dt = \DateTime::createFromFormat('d/m/Y H:i', $dataEmail);
    if ($dt !== false) {
        $dataEmail = $dt->format('d/m/Y H:i');
    } else {
        $ts = strtotime($dataEmail);
        if ($ts !== false && $ts !== -1) $dataEmail = date('d/m/Y H:i', $ts);
    }

    $dadosEmail = [
        'idChamado'   => $idChamado,
        'data'        => $dataEmail,
        'descricao'   => $chamado->getDescricaoChamado(),
        'usuario'     => $usuarioNome,
        'equipamento' => $equipamentoDesc,
        'status'      => $chamado->getStatusChamado() ?: 'Em andamento'
    ];

    
    $listaFotos = $this->listarFotosChamado($idChamado);

    
    try {
        \Util\EmailChamado::enviarAberturaChamado($dadosEmail, $listaFotos);
    } catch (\Throwable $e) {
        if (class_exists('\Util\Util')) {
            \Util\Util::inserirErro($e, "enviarAberturaChamado", $this->idUsuarioSessao);
        } else {
            error_log("Erro ao enviar email: " . $e->getMessage());
        }
    }

    
    header("Location:/syscheck/chamado/selecionarChamado/" . $idChamado);
    exit;
}

    

    function selecionarChamado($fkChamado)
    {
        return (new DaoChamado((new Conexao())->conectar(), $this->idUsuarioSessao))->selecionarChamado($fkChamado);
    }

    function atualizarChamado() {}

    function listarChamados()
    {
        $daoChamado = (new DaoChamado((new Conexao())->conectar(), $this->idUsuarioSessao));
        $listaChamados = $daoChamado->listarChamados();
        return $listaChamados;
    }

    function salvarFotoChamado($fotos)
    {
        for ($key = 0; $key < count($fotos); $key++) {
            (new DaoChamado((new Conexao())->conectar(), $this->idUsuarioSessao))->salvarFoto($fotos[$key]);
        }
    }

    function listarFotosChamado($fkChamado)
    {
        return (new DaoChamado((new Conexao())->conectar(), $this->idUsuarioSessao))->listarFotosChamado($fkChamado);
    }

    function salvarFollowUp($followup = null)
    {
        if ($followup == null) {
            $datahora = (new DateTime())->format('d/m/Y H:i');
            echo "abrir tela para fazer o followup";
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $followup[] = [
                'fkChamado' => $_POST['fkchamado'],
                'fkusuario' => Sessao::idusuario(),
                'followUp' => $_POST['descricao']
            ];

            $idFollowUp = (new DaoChamado((new Conexao())->conectar(), $this->idUsuarioSessao))->salvarFollowUp($followup);
            return $idFollowUp;
        }
    }

    function listaFollowUp($fkChamado)
    {
        return (new DaoChamado((new Conexao())->conectar(), $this->idUsuarioSessao))->listarFollowUp($fkChamado);
    }
}