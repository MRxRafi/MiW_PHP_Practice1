<?php

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use MiW\Results\Entity\Result;
use MiW\Results\Utility\Utils;

// Carga las variables de entorno
Utils::loadEnv(dirname(__DIR__, 2));

$entityManager = Utils::getEntityManager();

$resultsRepository = $entityManager->getRepository(Result::class);

if ($argc < 2 || $argc > 3) {
    $fich = basename(__FILE__);
    echo <<< MARCA_FIN
    Usage: $fich <Id> [--json]
MARCA_FIN;
    exit(0);
}

$resultId = (int) $argv[1];
$result = $resultsRepository->find($resultId);

if (null === $result) {
    echo "Resultado $resultId no encontrado" . PHP_EOL;
    exit(0);
}

if (in_array('--json', $argv, true)) {
    echo json_encode($result, JSON_PRETTY_PRINT);
} else {
    echo PHP_EOL
        . sprintf('%3s - %3s - %22s - %s', 'Id', 'res', 'username', 'time')
        . PHP_EOL;
    echo  $result . PHP_EOL;
}