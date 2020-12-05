<?php

/**
 * PHP version 7.4
 * ResultsDoctrine - controllers.php
 *
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

use MiW\Results\Entity\Result;
use MiW\Results\Entity\User;
use MiW\Results\Utility\Utils;

function funcionHomePage()
{
    global $routes;

    $rutaListadoUsers = $routes->get('ruta_user_list')->getPath();
    $rutaCreateUser = $routes->get('ruta_create_user')->getPath();
    $rutaUpdateUser = $routes->get('ruta_update_user')->getPath();
    $rutaListadoResults = $routes->get('ruta_result_list')->getPath();
    $rutaCreateResult = $routes->get('ruta_create_result')->getPath();
    $rutaUpdateResult = $routes->get('ruta_update_result')->getPath();
    echo <<< ____MARCA_FIN
    <h1>RUTAS</h1>
    <ul>
        <li><a href="$rutaListadoUsers">Listado Usuarios</a></li>
        <br>
        <li><a href="$rutaCreateUser">Crear Usuario</a></li>
        <br>
        <li><a href="$rutaUpdateUser">Actualizar Usuario</a></li>
        <br>
        <li><a href="$rutaListadoResults">Listado Resultados</a></li>
        <br>
        <li><a href="$rutaCreateResult">Crear Resultado</a></li>
        <br>
        <li><a href="$rutaUpdateResult">Actualizar Resultado</a></li>
        <br>
    </ul>
____MARCA_FIN;
}

function funcionListadoUsuarios(): void
{
    $entityManager = Utils::getEntityManager();

    $userRepository = $entityManager->getRepository(User::class);
    $users = $userRepository->findAll();

    echo <<< __MARCA_FIN
        <h1>USUARIOS</h1>
            <ul>
__MARCA_FIN;
    foreach ($users as $user) {
        echo "<li>$user</li>" . PHP_EOL;
    }
    echo '</ul>';
}

function funcionCrearUsuario() {
    echo 'Crear usuario funciona';
}

function funcionActualizarUsuario(string $name) {
    echo 'Actualizar usuario funciona ' . $name;
}

function funcionUsuario(string $name)
{
    echo $name;
}

function funcionListadoResultados(): void
{
    $entityManager = Utils::getEntityManager();

    $resultRepository = $entityManager->getRepository(Result::class);
    $results = $resultRepository->findAll();

    echo <<< __MARCA_FIN
        <h1>RESULTADOS</h1>
            <ul>
__MARCA_FIN;
    foreach ($results as $result) {
        echo "<li>$result</li>" . PHP_EOL;
    }
    echo '</ul>';
}

function funcionCrearResultado() {
    echo 'Crear resultado funciona';
}

function funcionActualizarResultado(string $name) {
    echo 'Actualizar resultado funciona ' . $name;
}


