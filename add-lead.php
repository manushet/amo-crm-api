<?php

declare(strict_types=1);

require_once(__DIR__ . '/autoload.php');

require_once(__DIR__ . '/config/config.php');

use AmoEvent\NewLeadEvent;
use LogRegister\LogRegister;

$entityBody = file_get_contents('php://input');

if (!empty($entityBody)) {
    LogRegister::writeLog(LOGS_PATH . '/add-lead.logs', $entityBody);

    try {
        (new NewLeadEvent)->handle($entityBody);
        http_response_code(200);
    } catch (\Exception $e) {
        http_response_code(500);
    }
}

http_response_code(200);
