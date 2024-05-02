<?php

declare(strict_types=1);

namespace LogRegister;

class LogRegister
{
    public static function writeLog(string $file, string $data): void
    {
        $logfile = fopen($file, "a+");

        fwrite($logfile, 'Date: ' . date('d-m-Y H:i:s', time()) . "\n\r");

        fwrite($logfile, $data . "\n\r\n\r");

        fclose($logfile);
    }
}