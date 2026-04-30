<?php

namespace Util;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/initEnv.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailChamado
{
    public static function enviarAberturaChamado($dados, $fotos = [])
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['SMTP_USER'];
            $mail->Password   = $_ENV['SMTP_PASS'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $_ENV['SMTP_PORT'];
            $mail->CharSet    = $_ENV['SMTP_CHARSET'];

            // Remetente
            $mail->setFrom('suporte.ti@udlog.com.br', 'Chamados Syscheck');

            // Destinatários
            $mail->addAddress('priscila.braz@udlog.com.br', 'Priscila');



            foreach ($fotos as $foto) {
                $caminhoRel = isset($foto['caminhoImagem']) ? $foto['caminhoImagem'] : null;
                if (!$caminhoRel) {
                    error_log('EmailChamado: foto sem caminho (caminhoImagem ausente)');
                    continue;
                }


                $possiveis = [];

                $possiveis[] = __DIR__ . '/../../' . $caminhoRel;
                $possiveis[] = __DIR__ . '/../../src/views/' . ltrim($caminhoRel, '/');
                $possiveis[] = __DIR__ . '/../../views/' . ltrim($caminhoRel, '/');
                $possiveis[] = $caminhoRel;

                $anexado = false;
                foreach ($possiveis as $caminho) {
                    if (file_exists($caminho)) {
                        try {
                            $mail->addAttachment($caminho);
                            $anexado = true;
                            break;
                        } catch (\Exception $e) {
                            error_log('EmailChamado: falha ao anexar ' . $caminho . ' - ' . $e->getMessage());
                        }
                    }
                }

                if (!$anexado) {
                    error_log('EmailChamado: arquivo de foto nao encontrado em tentativas: ' . implode(', ', $possiveis));
                }
            }

            $fotosTexto = !empty($fotos) ? 'As imagens seguem em anexo.' : 'Nenhuma foto enviada';

            $mail->isHTML(true);
            $mail->Subject = 'Novo Chamado Aberto - Nº ' . $dados['idChamado'];

            $mail->Body = "
            <div style='width:100%;display:flex;justify-content:center;background:#f7f8fa;'>
                <div style='background:#fff;
                            border-radius:10px;
                            padding:24px 32px;
                            margin:24px auto;
                            max-width:540px;
                            box-shadow:0 2px 8px #0001;
                            border:1px solid #e5e7eb;'>
                    <h3 style='color:#222;margin-bottom:16px;font-weight:600;'>
                        Abertura do Chamado: {$dados['data']}
                    </h3>
                    <table border='0' cellpadding='6' cellspacing='0' style='width:100%;font-size:15px;'>
                        <tr>
                            <td><strong>Equipamento:</strong></td>
                            <td>{$dados['equipamento']}</td>
                        </tr>
                        <tr>
                            <td><strong>Última Providência:</strong></td>
                            <td>{$dados['descricao']}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>Em andamento</td>
                        </tr>
                        <tr>
                            <td><strong>Usuário:</strong></td>
                            <td>{$dados['usuario']}</td>
                        </tr>
                        <tr>
                            <td style='vertical-align:top;'><strong>Fotos do Chamado:</strong></td>
                            <td>{$fotosTexto}</td>
                        </tr>
                    </table>
                    <div style='margin-top:18px;font-size:12px;color:#888;'>
                        <em>SysCheck - notificação automática</em>
                    </div>
                </div>
            </div>
            ";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Erro ao enviar email: {$mail->ErrorInfo}");
            return false;
        }
    }
}
