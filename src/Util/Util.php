<?php

namespace Util;

require __DIR__ . '/../../vendor/autoload.php';

use database\Conexao;
use Exception;
use DateTime;
use models\Erro;
use DAO\DaoErro;

use PHPMailer\PHPMailer\PHPMailer;


class Util{

    public static function inserirErro(Exception $e, $local, $idUsuarioSessao){
        $erro = new Erro(0, $e->getMessage(), $e->getFile(), $e->getLine(), $local, (new DateTime())->format('d/m/Y H:i:s'), $idUsuarioSessao);
        (new DaoErro((new Conexao())->conectar(), $idUsuarioSessao))->inserirErro($erro);
        Util::enviarEmail($erro);

        if(Sessao::idusuario() == 3){
            $listaErros = (new DaoErro((new Conexao())->conectar(), $idUsuarioSessao))->recuperarListaDeErros();  
            require_once __DIR__ . '/../views/features/deploy/deploy.php';
        }
    }

    private static function enviarEmail(Erro $erro){
        $para = "devdanilofranco@gmail.com";
        $assunto = "Erro no sistema Syscheck";


        $email = new PHPMailer();
        try{
            $email->isSMTP();
            $email->Host = 'smtplw.com.br';
            $email->SMTPAuth = true;
            $email->Username = 'udlog';
            $email->Password = 'Ti2020@#';
            $email->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $email->Port = 587;

            $email->CharSet = 'UTF-8';


            $email->setFrom('suporte.ti@udlog.com.br', 'Syscheck');
            $email->addAddress($para, "Danilo");

            $emailTemplate = file_get_contents(__DIR__ . '/../views/features/desenvolvimento/emailerro.html');

            $emailTemplate = str_replace('{{usuario}}', Sessao::nomeUsuario(), $emailTemplate);
            $emailTemplate = str_replace('{{dataHora}}', $erro->getDataHora(), $emailTemplate);
            $emailTemplate = str_replace('{{arquivo}}', $erro->getArquivo(), $emailTemplate);
            $emailTemplate = str_replace('{{linha}}', $erro->getLInha(), $emailTemplate);
            $emailTemplate = str_replace('{{local}}', $erro->getLocal(), $emailTemplate);
            $emailTemplate = str_replace('{{erro}}', $erro->getErro(), $emailTemplate);

            $email->isHTML(true);
            $email->Subject = $assunto;
            $email->Body = $emailTemplate;
            $email->AltBody = "Erro no sistema Syscheck";

            $email->send();

        }catch (Exception $e){
            //Util::inserirErro($e, 'enviarEmail', Sessao::idusuario());  
            error_log("Erro ao enviar e-mail: " . $e->getMessage());          
        }
    }

    public static function statusChecklist($status){
        $retorno = "";
        switch ($status){
            case 1:
                $retorno = "Pendente";
                break;
            case 2:
                $retorno = "Andamento";
                break;
            case 3:
                $retorno = "Finalizado";
                break;
            default:
                $retorno = "Status desconhecido";
        }
        return $retorno;
    }

    public static function status($status){
        $retorno = "";
        switch ($status){
            case 0:
                $retorno = "Inativo";
                break;
            case 1:
                $retorno = "Ativo";
                break;
            default:
                $retorno = "Status inválido";
        }

        return $retorno;
    }

    public static function formatarDataHora($dataHora){
        $dataFormatada = DateTime::createFromFormat('Y-m-d H:i:s', $dataHora);
        return $dataFormatada->format('d/m/Y H:i');
    }

}

?>