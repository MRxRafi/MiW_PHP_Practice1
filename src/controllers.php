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
    echo '<br><a href="/">Volver a home</a>';
}

function funcionCrearUsuario() {
    if (empty($_POST)){
        echo <<< ___MARCA_FIN
        <form method="post">
            <fieldset>
                <legend>Alta usuario</legend>
                <label for="username">Inserte un nombre de usuario</label>
                <input type="text" name="username" placeholder="Nombre de usuario">
                <br>
                <label for="email">Inserte un email</label>
                <input type="email" name="email" placeholder="Email">
                <br>
                <label for="password">Inserte una contraseña</label>
                <input type="password" name="password" placeholder="Contraseña">
                <br>
                <button type="submit">Enviar</button>
            </fieldset>
        </form>
___MARCA_FIN;
    } else {
        $entityManager = Utils::getEntityManager();
        $userRepository = $entityManager->getRepository(User::class);

        $userExists = $userRepository->findBy(['username' => $_POST['username']]);
        if (sizeof($userExists) !== 0) {
            echo '<p>El usuario ya existe en la BBDD</p>';
        } else {
            try {
                $user = new User($_POST['username'], $_POST['email'], $_POST['password'], true);

                $entityManager->persist($user);
                $entityManager->flush();

                echo '<p>Usuario creado correctamente</p>';
            } catch(Throwable $t) {
                echo $t->getMessage();
            }
        }
    }
    echo '<br><a href="/">Volver a home</a>';
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
    echo '<br><a href="/">Volver a home</a>';
}

function funcionCrearResultado() {
    if (empty($_POST)){
        echo <<< ___MARCA_FIN
        <form method="post">
            <fieldset>
                <legend>Alta resultado</legend>
                <label for="resultId">Inserte un id para el resultado: </label>
                <input type="number" name="resultId" placeholder="ID resultado">
                <br>
                <label for="userId">Inserte el ID del usuario asociado: </label>
                <input type="number" name="userId" placeholder="ID usuario">
                <br>
                <label for="fecha">Inserte una fecha (opcional): </label>
                <input type="date" name="fecha" placeholder="Fecha">
                <br>
                <button type="submit">Enviar</button>
            </fieldset>
        </form>
___MARCA_FIN;
    } else {
        $entityManager = Utils::getEntityManager();
        $resultRepository = $entityManager->getRepository(Result::class);

        $resultExists = $resultRepository->findBy(['result' => $_POST['resultId']]);
        if (sizeof($resultExists) !== 0) {
            echo '<p>El resultado ya existe en la BBDD</p>';
        } else {
            try {
                $userRepository = $entityManager->getRepository(User::class);

                $user = $userRepository->find($_POST['userId']);
                $date = $_POST['fecha'];
                if ($date == null) {
                    echo '<p>No se ha introducido una fecha válida, se usará la actual en su defecto.</p>' . PHP_EOL;
                    $date = new DateTime('now');
                } else {
                    $date = new DateTime($_POST['fecha']);
                }
                $result = new Result($_POST['resultId'], $user, $date);
                $entityManager->persist($result);
                $entityManager->flush();

                echo '<p>Resultado creado correctamente</p>';
            } catch(Throwable $t) {
                echo $t->getMessage();
            }
        }
    }
    echo '<br><a href="/">Volver a home</a>';
}

function funcionActualizarResultado(string $name) {
    echo 'Actualizar resultado funciona ' . $name;
}


