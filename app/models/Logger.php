<?php
/**
 * Logger – Technische log klasse
 * Schrijft acties en fouten weg naar logs/app.log
 * Voldoet aan PSR-12 codeconventie
 */
class Logger
{
    private string $logFile;

    public function __construct()
    {
        $this->logFile = APP_ROOT . '/logs/app.log';
    }

    /**
     * Schrijf een INFO bericht naar het logbestand
     */
    public function info(string $message): void
    {
        $this->write('INFO', $message);
    }

    /**
     * Schrijf een ERROR bericht naar het logbestand
     */
    public function error(string $message): void
    {
        $this->write('ERROR', $message);
    }

    /**
     * Schrijf een WARNING bericht naar het logbestand
     */
    public function warning(string $message): void
    {
        $this->write('WARNING', $message);
    }

    /**
     * Interne schrijffunctie – voegt timestamp, level en bericht toe
     */
    private function write(string $level, string $message): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $user      = $_SESSION['user_email'] ?? 'gast';
        $line      = "[{$timestamp}] [{$level}] [gebruiker: {$user}] {$message}" . PHP_EOL;

        try {
            file_put_contents($this->logFile, $line, FILE_APPEND | LOCK_EX);
        } catch (Exception $e) {
            // Stille fout – logging mag de applicatie niet breken
        }
    }
}
