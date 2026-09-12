<?php

namespace App\Services;

use CodeIgniter\Email\Email;

class EmailService
{
    protected Email $email;
    protected ?string $lastError = null;

    public function __construct(?Email $email = null)
    {
        $this->email = $email ?? service('email');
    }

    /**
     * Envia um e-mail com conteúdo HTML.
     */
    public function enviar(
        string|array $to,
        string $subject,
        string $message,
        ?string $fromEmail = null,
        ?string $fromName = null,
        array $attachments = []
    ): bool {
        $this->lastError = null;
        $this->email->clear(true);

        if ($fromEmail) {
            $this->email->setFrom($fromEmail, $fromName ?? '');
        }

        $this->email->setTo($to);
        $this->email->setSubject($subject);
        $this->email->setMessage($message);
        $this->email->setMailType('html');

        foreach ($attachments as $attachment) {
            if (is_string($attachment) && file_exists($attachment)) {
                $this->email->attach($attachment);
            }
        }

        if (!$this->email->send()) {
            $this->lastError = $this->email->printDebugger(['headers', 'subject']);
            log_message('error', '[EmailService] Falha ao enviar e-mail para {to}: {error}', [
                'to'    => is_array($to) ? implode(', ', $to) : $to,
                'error' => $this->lastError,
            ]);

            return false;
        }

        return true;
    }

    /**
     * Envia um e-mail renderizando uma View do CodeIgniter.
     */
    public function enviarTemplate(
        string|array $to,
        string $subject,
        string $viewPath,
        array $data = [],
        ?string $fromEmail = null,
        ?string $fromName = null
    ): bool {
        $message = view($viewPath, $data);

        return $this->enviar($to, $subject, $message, $fromEmail, $fromName);
    }

    /**
     * Envia o código de verificação de e-mail (usado na confirmação de conta).
     */
    public function enviarCodigoVerificacao(string $to, string $codigo, string $nome = ''): bool
    {
        $data = [
            'nome'   => $nome,
            'codigo' => $codigo,
            'titulo' => 'Verificação de E-mail',
        ];

        $subject = 'Seu código de verificação - AcheiProfissional';

        $viewPath = 'Emails/verificacao_email';
        if (is_file(APPPATH . 'Views/' . $viewPath . '.php')) {
            return $this->enviarTemplate($to, $subject, $viewPath, $data);
        }

        $html = $this->gerarHtmlPadrao(
            'Verificação de E-mail',
            "Olá,<br><br>Seu código de verificação para ativar sua conta na plataforma <strong>AcheiProfissional</strong> é:",
            $codigo,
            "Se você não realizou esse cadastro, ignore esta mensagem."
        );

        return $this->enviar($to, $subject, $html);
    }

    /**
     * Envia o código de recuperação de senha.
     */
    public function enviarCodigoRecuperacao(string $to, string $codigo, string $nome = ''): bool
    {
        $data = [
            'nome'   => $nome,
            'codigo' => $codigo,
            'titulo' => 'Recuperação de Senha',
        ];

        $subject = 'Recuperação de senha - AcheiProfissional';

        $viewPath = 'Emails/recuperacao_senha';
        if (is_file(APPPATH . 'Views/' . $viewPath . '.php')) {
            return $this->enviarTemplate($to, $subject, $viewPath, $data);
        }

        $html = $this->gerarHtmlPadrao(
            'Recuperação de Senha',
            "Olá <strong>" . esc($nome ?: 'Usuário') . "</strong>,<br><br>Recebemos uma solicitação para redefinir a sua senha. Utilize o código de verificação abaixo para continuar:",
            $codigo,
            "Se você não solicitou a alteração de senha, ignore este e-mail."
        );

        return $this->enviar($to, $subject, $html);
    }

    /**
     * Retorna a última mensagem de erro capturada pelo debugger.
     */
    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    /**
     * Gera um template HTML padrão responsivo caso nenhuma view específica tenha sido criada.
     */
    protected function gerarHtmlPadrao(string $titulo, string $mensagem, string $destaque = '', string $rodapeInfo = ''): string
    {
        $destaqueHtml = '';
        if ($destaque !== '') {
            $destaqueHtml = "
                <div style='margin: 25px 0; text-align: center;'>
                    <span style='display: inline-block; font-size: 28px; font-weight: bold; letter-spacing: 5px; color: #0d6efd; background-color: #e7f1ff; padding: 12px 24px; border-radius: 8px; border: 1px dashed #0d6efd;'>
                        " . esc($destaque) . "
                    </span>
                </div>";
        }

        $rodapeHtml = '';
        if ($rodapeInfo !== '') {
            $rodapeHtml = "<p style='font-size: 12px; color: #6c757d; margin-top: 20px;'>" . esc($rodapeInfo) . "</p>";
        }

        return "
        <!DOCTYPE html>
        <html lang='pt-BR'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>" . esc($titulo) . "</title>
        </head>
        <body style='font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 20px; color: #212529;'>
            <table align='center' border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 600px; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08);'>
                <tr>
                    <td style='background-color: #0d6efd; padding: 24px; text-align: center; color: #ffffff;'>
                        <h2 style='margin: 0; font-size: 22px; font-weight: 600;'>AcheiProfissional</h2>
                    </td>
                </tr>
                <tr>
                    <td style='padding: 30px 25px;'>
                        <h3 style='margin-top: 0; color: #212529; font-size: 18px;'>" . esc($titulo) . "</h3>
                        <p style='font-size: 15px; line-height: 1.6; color: #495057; margin-bottom: 0;'>{$mensagem}</p>
                        {$destaqueHtml}
                        {$rodapeHtml}
                    </td>
                </tr>
                <tr>
                    <td style='background-color: #f1f3f5; padding: 15px; text-align: center; font-size: 12px; color: #6c757d; border-top: 1px solid #e9ecef;'>
                        © " . date('Y') . " AcheiProfissional - Plataforma de Autônomos. Todos os direitos reservados.
                    </td>
                </tr>
            </table>
        </body>
        </html>";
    }

    protected function gerarCodigoVerificacao(int $tamanho = 6): string                                                                      
    {                                                                                                                                        
        $max = (10 ** $tamanho) - 1;                                                                                                         
        $numero = random_int(0, $max);                                                                                                       
                                                                                                                                             
        return sprintf("%0{$tamanho}d", $numero);                                                                                            
    }

    protected function salvarCodigoVerificacao(int $usuarioId, string $finalidade, string $codigo): bool
    {
        $db = db_connect();
        $builder = $db->table('usuario_codigos_email');

        $data = [
            'usuario_id' => $usuarioId,
            'finalidade' => $finalidade,
            'tentativas' => 0,
            'utilizado'  => false,
            'codigo'     => $codigo,
        ];

        return (bool) $builder->insert($data);
    }
}
