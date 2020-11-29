<?php

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use MiW\Results\Entity\User;
use MiW\Results\Utility\Utils;

// Carga las variables de entorno
Utils::loadEnv(dirname(__DIR__, 2));

$entityManager = Utils::getEntityManager();
$userRepository = $entityManager->getRepository(User::class);

if ($argc < 4 || $argc > 5) {
    $fich = basename(__FILE__);
    echo <<< MARCA_FIN

    Usage: $fich <username> <email> <password> [--json]

MARCA_FIN;
    exit(0);
}

$userName = $argv[1];
$email = $argv[2];
$password = $argv[3];

$usernameExists = $userRepository->findOneBy(['username' => $userName]);
$emailExists = $userRepository->findOneBy(['email' => $email]);

if($usernameExists != null) {
    echo "Nombre de usuario $userName ya existe" . PHP_EOL;
    exit(0);
} else if($emailExists != null) {
    echo "Usuario con email $email ya existe" . PHP_EOL;
    exit(0);
}

$user = new User($userName, $email, $password, true);
try {
   $entityManager->persist($user);
   $entityManager->flush();

   echo "Created user: " . PHP_EOL;
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

