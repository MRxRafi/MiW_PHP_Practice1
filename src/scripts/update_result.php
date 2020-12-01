<?php

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use MiW\Results\Entity\Result;
use MiW\Results\Entity\User;
use MiW\Results\Utility\Utils;

// Carga las variables de entorno
Utils::loadEnv(dirname(__DIR__, 2));

if ($argc < 4 || $argc > 5) {
    $fich = basename(__FILE__);
    echo <<< MARCA_FIN

    Usage: $fich <Result> <newResult> <newUserId> [--json]

MARCA_FIN;
    exit(0);
}

$actualResult    = (int) $argv[1];
$newResult    = (int) $argv[2];
$newUserId       = (int) $argv[3];

$entityManager = Utils::getEntityManager();

/** @var User $user */
$user = $entityManager
    ->getRepository(User::class)
    ->findOneBy(['id' => $newUserId]);
if (null === $user) {
    echo "Usuario $newUserId no encontrado" . PHP_EOL;
    exit(0);
}

$resultRepository = $entityManager->getRepository(Result::class);
$oldResult = $resultRepository->findOneBy(['result' => $actualResult]);
$newResultGiven = $resultRepository->findOneBy(['result' => $newResult]);

if($oldResult == null) {
    echo "El resultado $oldResult no existe" . PHP_EOL;
    exit(0);
} else if($newResultGiven != null) {
    echo "El resultado $newResult ya existe" . PHP_EOL;
    exit(0);
}
try {
    $oldResult->setResult($newResult);
    $oldResult->setUser($user);
    $entityManager->merge($oldResult);
    $entityManager->flush();
    echo 'Updated Result with ID ' . $oldResult->getId()
        . ' USER ' . $user->getUsername() . PHP_EOL;
} catch (Throwable $exception) {
    echo $exception->getMessage();
}