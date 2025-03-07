<?php

namespace Src\Service;

use Exception;
use PDOException;
use Src\Config\Config;
use Src\Model\Email;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class EmailService
{
    public static function sendMail(array $data)
    {
        $pdo = Email::getConnection();
        $pdo->beginTransaction();

        try {
            $Email = Email::createLead($data, $pdo);

            if (!$Email) {
                throw new Exception("Não foi possível cadastrar email");
            }

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.escalaweb.com.br';
                $mail->SMTPAuth = true;
                $mail->Username = "teste@escalaweb.com.br";
                $mail->Password = "Escalaweb$17";;
                $mail->SMTPSecure = false;
		        $mail->SMTPAutoTLS = false;
                $mail->Port = 587;

                $mail->setFrom("suporte@escalaweb.com.br", 'Suporte Escala Web');
                $mail->addAddress(Config::get("MAIN_EMAIL"));

                $mail->isHTML(true);
                $mail->Subject = mb_convert_encoding('Contato Através do Site', 'ISO-8859-1', 'UTF-8');
                $mail->Body    = mb_convert_encoding(self::corpoEmail($data), 'ISO-8859-1', 'UTF-8');

                $mail->send();
            } catch (PHPMailerException $e) {
                throw new Exception("Erro ao enviar o email: " . $e->getMessage());
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            return ['error' => "Ocorreu um erro no banco de dados"];
        } catch (Exception $e) {
            $pdo->rollBack();
            return ['error' => "Erro ao enviar o email: " . $e->getMessage()];
        }

        $pdo->commit();
        return "Email enviado com sucesso";
    }

    private static function corpoEmail($formulario)
    {
        $pagina = "Site";


        $informacoes = "
					<b>Novo Lead Capturado pela Escala Web! &#x1F600;</b><br/><br/>
					<b style='font-size:16px;'>Informações do Lead:</b><br/>
					- Nome: " . ($formulario['name'] ?? 'Não informado') . "<br/>
					- Endereço: " . ($formulario['endereco'] ?? 'Não informado') . "<br/>
					- Interesse: " . ($formulario['assunto_email'] ?? 'Agendamento') . "<br/>
					- Origem: " . ($pagina ?? 'Não informado') . "<br/>
					- Detalhe: " . ($formulario['message'] ?? 'Não informado') . "<br/><br/>
					<b style='font-size:16px;'>Contatos:</b><br/>
					- E-mail: " . ($formulario['email'] ? "<a href='mailto:" . ($formulario['email'] ?? '') . "'>" . ($formulario['email'] ?? '') . "</a>" : 'Não informado') . "<br/>
					- Telefone: " . ($formulario['contact'] ?? 'Não informado') . "<br/>
					- WhatsApp: <a href='https://wa.me/" . ($formulario['contact'] ?? '') . "'>https://wa.me/" . ($formulario['contact'] ?? '') . "</a><br/><br/>
					<b style='font-size:12px;'>Data/Hora de Envio:</b><br/>
					" . date('d/m/Y H:i:s') . "<br/>
				";


        return (
            "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//PT' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
        <html xmlns='http://www.w3.org/1999/xhtml' style='-webkit-text-size-adjust:none;'>
            <head>
                <meta charset='utf-8'/>
                <meta name='HandheldFriendly' content='true'/>
                <meta name='viewport' content='width=device-width'/>
                <title>Novo Lead Capturado</title>
            </head>
            <body style='padding:25px 0 75px 0; margin:0 auto; width:100%; height:100%; font-family:Helvetica,Arial,sans-serif;'>
                <table border='0' cellspacing='0' cellpadding='0' style='border-collapse:collapse; margin:50px 0 0 0;' width='100%' height='100%'>
                    <tbody>
                        <tr>
                            <td>
                                <table>
                                    <tr>
                                        <td style='padding: 15px'>
                                            <img src='https://escalaweb.com.br/images/logo-nova.png' alt='Logo' style='max-width:200px; margin-bottom:20px;' />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style='background-color:#FFF; text-align:left; padding:15px; font-size:14px;'>
                                            " . $informacoes . "
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </body>
        </html>"
        );
    }
}
