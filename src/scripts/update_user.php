<?php

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use MiW\Results\Entity\User;
use MiW\Results\Utility\Utils;

// Carga las variables de entorno
Utils::loadEnv(dirname(__DIR__, 2));

$entityManager = Utils::getEntityManager();
$userRepository = $entityManager->getRepository(User::class);

if ($argc < 5 || $argc > 6) {
    $fich = basename(__FILE__);
    echo <<< MARCA_FIN

    Usage: $fich <username> <newUsername> <newEmail> <newPassword> [--json]

MARCA_FIN;
    exit(0);
}

$userName = $argv[1];
$newUsername = $argv[2];
$newEmail = $argv[3];
$newPassword = $argv[4];

$user = $userRepository->findOneBy(['username' => $userName]);

if($user == null) {
    echo "El usuario $userName no existe" . PHP_EOL;
    exit(0);
} else {
    $user->setUsername($newUsername);
    $user->setPassword($newPassword);
    $user->setEmail($newEmail);

    try {
        $entityManager->merge($user);
        $entityManager->flush();

        echo "Updated user: " . PHP_EOL;
        if(in_array('--json', $argv, true)) {
            echo json_encode($user, JSON_PRETTY_PRINT);
        } else {
            echo PHP_EOL . sprintf(
                    '  %2s: %20s %30s %7s' . PHP_EOL,
                    'Id', 'Username:', 'Email:', 'Enabled:'
                );
            echo sprintf(
                '- %2d: %20s %30s %7s',
                $user->getId(),
                $user->getUsername(),
                $user->getEmail(),
                ($user->isEnabled()) ? 'true' : 'false'
            ),
            PHP_EOL;
        }
    } catch (Throwable $exception) {
        echo $exception->getMessage();
    }
}