<?php

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use MiW\Results\Entity\User;
use MiW\Results\Utility\Utils;

// Carga las variables de entorno
Utils::loadEnv(dirname(__DIR__, 2));

$entityManager = Utils::getEntityManager();
$userRepository = $entityManager->getRepository(User::class);

if ($argc != 2) {
    $fich = basename(__FILE__);
    echo <<< MARCA_FIN

    Usage: $fich <username>

MARCA_FIN;
    exit(0);
}

$userName = $argv[1];

$userExists = $userRepository->findOneBy(['username' => $userName]);

if($userExists != null) {
    $entityManager->remove($userExists);
    $entityManager->flush();

    echo "Deleted user: " . $userName . PHP_EOL;

} else {
    echo "Nombre de usuario $userName no existe" . PHP_EOL;
}