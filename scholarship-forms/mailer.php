<?php
/**
 * Single-file mail helper with SMTP support.
 *
 * Usage:
 *   require_once __DIR__ . '/mailer.php';
 *
 *   $mailer = new SimpleMailer([
 *       'host' => 'smtp.example.com',
 *       'port' => 587,
 *       'username' => 'user@example.com',
 *       'password' => 'secret',
 *       'encryption' => 'tls', // tls, ssl, or null
 *       'from_email' => 'user@example.com',
 *       'from_name' => 'Example App',
 *   ]);
 *
 *   $mailer->send([
 *       'to' => 'recipient@example.com',
 *       'subject' => 'Hello',
 *       'html' => '<strong>Hello world</strong>',
 *       'text' => 'Hello world',
 *   ]);
 */

class SimpleMailerException extends Exception
{
}

class SimpleMailer
{
    private array $config;
    private ?string $lastError = null;

    public function __construct(array $config = [])
    {
        $this->config = array_merge([
            'host' => null,
            'port' => 587,
            'username' => null,
            'password' => null,
            'encryption' => 'tls',
            'timeout' => 20,
            'from_email' => null,
            'from_name' => null,
            'reply_to' => null,
            'charset' => 'UTF-8',
            'debug' => false,
            'allow_self_signed' => false,
            'use_php_mail' => false,
        ], $config);
    }

    public function send(array $message): bool
    {
        $this->lastError = null;

        try {
            $message = $this->normalizeMessage($message);

            if ($this->config['use_php_mail'] || empty($this->config['host'])) {
                return $this->sendWithPhpMail($message);
            }

            return $this->sendWithSmtp($message);
        } catch (Throwable $e) {
            $this->lastError = $e->getMessage();

            if (!empty($this->config['debug'])) {
                throw $e;
            }

            return false;
        }
    }

    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    private function normalizeMessage(array $message): array
    {
        $message = array_merge([
            'to' => [],
            'cc' => [],
            'bcc' => [],
            'reply_to' => $this->config['reply_to'],
            'from_email' => $this->config['from_email'],
            'from_name' => $this->config['from_name'],
            'subject' => '',
            'html' => null,
            'text' => null,
            'attachments' => [],
            'headers' => [],
        ], $message);

        $message['to'] = $this->normalizeAddresses($message['to']);
        $message['cc'] = $this->normalizeAddresses($message['cc']);
        $message['bcc'] = $this->normalizeAddresses($message['bcc']);
        $message['reply_to'] = $this->normalizeAddresses($message['reply_to']);

        if (empty($message['to'])) {
            throw new SimpleMailerException('At least one recipient is required.');
        }

        if (empty($message['from_email']) || !filter_var($message['from_email'], FILTER_VALIDATE_EMAIL)) {
            throw new SimpleMailerException('A valid from_email is required.');
        }

        if ($message['html'] === null && $message['text'] === null) {
            throw new SimpleMailerException('Either html or text body is required.');
        }

        foreach (array_merge($message['to'], $message['cc'], $message['bcc'], $message['reply_to']) as $address) {
            if (!filter_var($address['email'], FILTER_VALIDATE_EMAIL)) {
                throw new SimpleMailerException('Invalid email address: ' . $address['email']);
            }
        }

        return $message;
    }

    private function normalizeAddresses($addresses): array
    {
        if ($addresses === null || $addresses === '') {
            return [];
        }

        if (is_string($addresses)) {
            return [['email' => $addresses, 'name' => null]];
        }

        if (isset($addresses['email'])) {
            return [[
                'email' => $addresses['email'],
                'name' => $addresses['name'] ?? null,
            ]];
        }

        $normalized = [];

        foreach ((array) $addresses as $key => $value) {
            if (is_string($key) && is_string($value)) {
                $normalized[] = ['email' => $key, 'name' => $value];
                continue;
            }

            if (is_string($value)) {
                $normalized[] = ['email' => $value, 'name' => null];
                continue;
            }

            if (is_array($value) && isset($value['email'])) {
                $normalized[] = [
                    'email' => $value['email'],
                    'name' => $value['name'] ?? null,
                ];
            }
        }

        return $normalized;
    }

    private function sendWithSmtp(array $message): bool
    {
        $transport = $this->openSmtpConnection();

        try {
            $this->smtpExpect($transport, [220]);

            $serverName = $this->serverName();
            $this->smtpCommand($transport, 'EHLO ' . $serverName, [250]);

            if ($this->config['encryption'] === 'tls') {
                $this->smtpCommand($transport, 'STARTTLS', [220]);

                if (!stream_socket_enable_crypto($transport, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                    throw new SimpleMailerException('Unable to start TLS encryption.');
                }

                $this->smtpCommand($transport, 'EHLO ' . $serverName, [250]);
            }

            if (!empty($this->config['username'])) {
                $this->smtpCommand($transport, 'AUTH LOGIN', [334]);
                $this->smtpCommand($transport, base64_encode((string) $this->config['username']), [334]);
                $this->smtpCommand($transport, base64_encode((string) $this->config['password']), [235]);
            }

            $this->smtpCommand($transport, 'MAIL FROM:<' . $message['from_email'] . '>', [250]);

            foreach ($this->allRecipients($message) as $recipient) {
                $this->smtpCommand($transport, 'RCPT TO:<' . $recipient['email'] . '>', [250, 251]);
            }

            $this->smtpCommand($transport, 'DATA', [354]);
            $this->smtpWrite($transport, $this->dotStuff($this->buildMessage($message)) . "\r\n.");
            $this->smtpExpect($transport, [250]);
            $this->smtpCommand($transport, 'QUIT', [221]);

            fclose($transport);
            return true;
        } catch (Throwable $e) {
            if (is_resource($transport)) {
                fclose($transport);
            }

            throw $e;
        }
    }

    private function sendWithPhpMail(array $message): bool
    {
        $headers = $this->buildHeaders($message, true);
        $body = $this->buildBody($message);
        $to = $this->formatAddressList($message['to']);
        $subject = $this->encodeHeader($message['subject']);

        $sent = mail($to, $subject, $body, implode("\r\n", $headers));

        if (!$sent) {
            throw new SimpleMailerException('PHP mail() returned false.');
        }

        return true;
    }

    private function openSmtpConnection()
    {
        $host = (string) $this->config['host'];
        $port = (int) $this->config['port'];
        $scheme = $this->config['encryption'] === 'ssl' ? 'ssl://' : '';
        $contextOptions = [];

        if (!empty($this->config['allow_self_signed'])) {
            $contextOptions['ssl'] = [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ];
        }

        $context = stream_context_create($contextOptions);
        $transport = @stream_socket_client(
            $scheme . $host . ':' . $port,
            $errno,
            $errstr,
            (int) $this->config['timeout'],
            STREAM_CLIENT_CONNECT,
            $context
        );

        if (!$transport) {
            throw new SimpleMailerException("SMTP connection failed: {$errstr} ({$errno}).");
        }

        stream_set_timeout($transport, (int) $this->config['timeout']);
        return $transport;
    }

    private function buildMessage(array $message): string
    {
        return implode("\r\n", $this->buildHeaders($message, false)) . "\r\n\r\n" . $this->buildBody($message);
    }

    private function buildHeaders(array $message, bool $includeBcc): array
    {
        $headers = [
            'Date: ' . date(DATE_RFC2822),
            'From: ' . $this->formatAddress([
                'email' => $message['from_email'],
                'name' => $message['from_name'],
            ]),
            'To: ' . $this->formatAddressList($message['to']),
            'Subject: ' . $this->encodeHeader($message['subject']),
            'MIME-Version: 1.0',
        ];

        if (!empty($message['cc'])) {
            $headers[] = 'Cc: ' . $this->formatAddressList($message['cc']);
        }

        if ($includeBcc && !empty($message['bcc'])) {
            $headers[] = 'Bcc: ' . $this->formatAddressList($message['bcc']);
        }

        if (!empty($message['reply_to'])) {
            $headers[] = 'Reply-To: ' . $this->formatAddressList($message['reply_to']);
        }

        foreach ($message['headers'] as $name => $value) {
            $name = trim((string) $name);
            $value = trim((string) $value);

            if ($name !== '' && $value !== '' && !$this->hasHeaderInjection($name . $value)) {
                $headers[] = $name . ': ' . $value;
            }
        }

        if (!empty($message['attachments'])) {
            $headers[] = 'Content-Type: multipart/mixed; boundary="' . $this->boundary('mixed') . '"';
        } elseif ($message['html'] !== null && $message['text'] !== null) {
            $headers[] = 'Content-Type: multipart/alternative; boundary="' . $this->boundary('alt') . '"';
        } elseif ($message['html'] !== null) {
            $headers[] = 'Content-Type: text/html; charset=' . $this->config['charset'];
            $headers[] = 'Content-Transfer-Encoding: quoted-printable';
        } else {
            $headers[] = 'Content-Type: text/plain; charset=' . $this->config['charset'];
            $headers[] = 'Content-Transfer-Encoding: quoted-printable';
        }

        return $headers;
    }

    private function buildBody(array $message): string
    {
        $mixedBoundary = $this->boundary('mixed');
        $altBoundary = $this->boundary('alt');

        if (empty($message['attachments'])) {
            return $this->buildAlternativeBody($message, $altBoundary);
        }

        $body = [];
        $body[] = '--' . $mixedBoundary;

        if ($message['html'] !== null && $message['text'] !== null) {
            $body[] = 'Content-Type: multipart/alternative; boundary="' . $altBoundary . '"';
            $body[] = '';
            $body[] = $this->buildAlternativeBody($message, $altBoundary);
        } else {
            $contentType = $message['html'] !== null ? 'text/html' : 'text/plain';
            $content = $message['html'] !== null ? $message['html'] : $message['text'];
            $body[] = 'Content-Type: ' . $contentType . '; charset=' . $this->config['charset'];
            $body[] = 'Content-Transfer-Encoding: quoted-printable';
            $body[] = '';
            $body[] = quoted_printable_encode((string) $content);
        }

        foreach ($message['attachments'] as $attachment) {
            $body[] = $this->buildAttachmentPart($attachment, $mixedBoundary);
        }

        $body[] = '--' . $mixedBoundary . '--';
        return implode("\r\n", $body);
    }

    private function buildAlternativeBody(array $message, string $boundary): string
    {
        if ($message['html'] !== null && $message['text'] !== null) {
            return implode("\r\n", [
                '--' . $boundary,
                'Content-Type: text/plain; charset=' . $this->config['charset'],
                'Content-Transfer-Encoding: quoted-printable',
                '',
                quoted_printable_encode((string) $message['text']),
                '--' . $boundary,
                'Content-Type: text/html; charset=' . $this->config['charset'],
                'Content-Transfer-Encoding: quoted-printable',
                '',
                quoted_printable_encode((string) $message['html']),
                '--' . $boundary . '--',
            ]);
        }

        return quoted_printable_encode((string) ($message['html'] ?? $message['text']));
    }

    private function buildAttachmentPart(array $attachment, string $boundary): string
    {
        $path = $attachment['path'] ?? null;

        if (!$path || !is_readable($path)) {
            throw new SimpleMailerException('Attachment is not readable: ' . (string) $path);
        }

        $name = $attachment['name'] ?? basename($path);
        $type = $attachment['type'] ?? $this->detectMimeType($path);
        $content = chunk_split(base64_encode((string) file_get_contents($path)));

        return implode("\r\n", [
            '--' . $boundary,
            'Content-Type: ' . $type . '; name="' . addslashes($name) . '"',
            'Content-Transfer-Encoding: base64',
            'Content-Disposition: attachment; filename="' . addslashes($name) . '"',
            '',
            trim($content),
        ]);
    }

    private function smtpCommand($transport, string $command, array $expectedCodes): string
    {
        $this->smtpWrite($transport, $command);
        return $this->smtpExpect($transport, $expectedCodes);
    }

    private function smtpWrite($transport, string $line): void
    {
        fwrite($transport, $line . "\r\n");
    }

    private function smtpExpect($transport, array $expectedCodes): string
    {
        $response = '';

        while (($line = fgets($transport, 515)) !== false) {
            $response .= $line;

            if (strlen($line) >= 4 && $line[3] === ' ') {
                break;
            }
        }

        $code = (int) substr($response, 0, 3);

        if (!in_array($code, $expectedCodes, true)) {
            throw new SimpleMailerException('SMTP error: ' . trim($response));
        }

        return $response;
    }

    private function allRecipients(array $message): array
    {
        return array_merge($message['to'], $message['cc'], $message['bcc']);
    }

    private function formatAddressList(array $addresses): string
    {
        return implode(', ', array_map([$this, 'formatAddress'], $addresses));
    }

    private function formatAddress(array $address): string
    {
        $email = $address['email'];
        $name = $address['name'] ?? null;

        if ($name === null || $name === '') {
            return '<' . $email . '>';
        }

        return $this->encodeHeader($name) . ' <' . $email . '>';
    }

    private function encodeHeader(string $value): string
    {
        if ($this->hasHeaderInjection($value)) {
            throw new SimpleMailerException('Header injection detected.');
        }

        if (preg_match('/[^\x20-\x7E]/', $value)) {
            return '=?' . $this->config['charset'] . '?B?' . base64_encode($value) . '?=';
        }

        return $value;
    }

    private function hasHeaderInjection(string $value): bool
    {
        return strpos($value, "\r") !== false || strpos($value, "\n") !== false;
    }

    private function dotStuff(string $message): string
    {
        return preg_replace('/^\./m', '..', $message);
    }

    private function detectMimeType(string $path): string
    {
        if (function_exists('mime_content_type')) {
            $mimeType = mime_content_type($path);

            if ($mimeType) {
                return $mimeType;
            }
        }

        return 'application/octet-stream';
    }

    private function boundary(string $name): string
    {
        static $boundaries = [];

        if (!isset($boundaries[$name])) {
            $boundaries[$name] = 'simple_mailer_' . $name . '_' . bin2hex(random_bytes(12));
        }

        return $boundaries[$name];
    }

    private function serverName(): string
    {
        return $_SERVER['SERVER_NAME'] ?? gethostname() ?: 'localhost';
    }
}

function send_mail(array $smtpConfig, array $message): bool
{
    return (new SimpleMailer($smtpConfig))->send($message);
}
