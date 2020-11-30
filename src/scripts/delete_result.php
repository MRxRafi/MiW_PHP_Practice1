<?php

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use MiW\Results\Entity\Result;
use MiW\Results\Utility\Utils;

// Carga las variables de entorno
Utils::loadEnv(dirname(__DIR__, 2));

$entityManager = Utils::getEntityManager();
$resultRepository = $entityManager->getRepository(Result::class);

if ($argc != 2) {
    $fich = basename(__FILE__);
    echo <<< MARCA_FIN

    Usage: $fich <result>

MARCA_FIN;
    exit(0);
}

$result = $argv[1];

$resultExists = $resultRepository->findOneBy(['result' => $result]);

if($resultExists != null) {
    $entityManager->remove($resultExists);
    $entityManager->flush();

    echo "Deleted result: " . $result . PHP_EOL;

} else {
    echo "Resultado $result no existe" . PHP_EOL;
}