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
    $rutaListadoResults = $routes->get('ruta_result_list')->getPath();
    echo <<< ____MARCA_FIN
    <h1>RUTAS</h1>
    <ul>
        <li><a href="$rutaListadoUsers">Listado Usuarios</a></li>
        <br>
        <li><a href="$rutaListadoResults">Listado Resultados</a></li>
        <br>
    </ul>
____MARCA_FIN;
}

function funcionListadoUsuarios(): void
{
    global $routes;
    $rutaCreateUser = $routes->get('ruta_create_user')->getPath();

    $entityManager = Utils::getEntityManager();

    $userRepository = $entityManager->getRepository(User::class);
    $users = $userRepository->findAll();

    echo <<< __MARCA_FIN
        <h1>USUARIOS</h1>
            <table border="1px">
                <thead>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Enabled</th>
                </thead>
                <tbody>
__MARCA_FIN;
    foreach ($users as $user) {
        $id = $user->getId();
        $username = $user->getUsername();
        $email = $user->getEmail();
        $enabled = $user->isEnabled();
        echo <<< ____MARCA_FIN
            <tr>
                <td>$id</td>
                <td>$username</td>
                <td>$email</td>
                <td>$enabled</td>
                <td>
                    <a href="/users/$username">Borrar Usuario</a>
                </td>
                <td>
                    <a href="/users/update/$username">Actualizar Usuario</a>
                </td>
            </tr>
____MARCA_FIN;
    }
    echo '</tbody></table>';
    echo "<br><a href=$rutaCreateUser>Crear Usuario</a><br>";
    echo '<br><a href="/">Volver a home</a>';
}

function funcionCrearUsuario() {
    if (empty($_POST)){
        echo <<< ___MARCA_FIN
        <form method="post">
            <fieldset>
                <legend>Alta usuario</legend>
                <label for="username">Inserte un nombre de usuario: </label>
                <input type="text" name="username" placeholder="Nombre de usuario">
                <br>
                <label for="email">Inserte un email: </label>
                <input type="email" name="email" placeholder="Email">
                <br>
                <label for="password">Inserte una contraseña: </label>
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
    $entityManager = Utils::getEntityManager();
    $userRepository = $entityManager->getRepository(User::class);
    $oldUser = $userRepository->findOneBy(['username' => $name]);
    if ($oldUser != null) {
        if (empty($_POST)){
            echo <<< ___MARCA_FIN
            <form method="post">
                <fieldset>
                    <legend>Actualizar usuario $name</legend>
                    <label for="username">Inserte un nuevo nombre de usuario: </label>
                    <input type="text" name="username" placeholder="Nombre de usuario">
                    <br>
                    <label for="email">Inserte un nuevo email: </label>
                    <input type="email" name="email" placeholder="Email">
                    <br>
                    <label for="password">Inserte una nueva contraseña: </label>
                    <input type="password" name="password" placeholder="Contraseña">
                    <br>
                    <button type="submit">Enviar</button>
                </fieldset>
            </form>
___MARCA_FIN;
        } else {
            if ($_POST['username'] != null) {
                $oldUser->setUsername($_POST['username']);
            }
            if ($_POST['email'] != null) {
                $oldUser->setEmail($_POST['email']);
            }
            if ($_POST['password'] != null) {
                $oldUser->setPassword($_POST['password']);
            }

            if ($userRepository->findOneBy(['username' => $_POST['username']]) !== null ||
                $userRepository->findOneBy(['email' => $_POST['email']]) !== null) {
                echo '<p>El usuario o email ya existe en la BBDD</p>';
            } else {
                try {
                    $entityManager->merge($oldUser);
                    $entityManager->flush();

                    echo '<p>Usuario actualizado correctamente</p>';
                } catch(Throwable $t) {
                    echo $t->getMessage();
                }
            }
        }
    }

    echo '<br><a href="/">Volver a home</a>';
}

function funcionUsuario(string $name)
{
    $entityManager = Utils::getEntityManager();
    $userRepository = $entityManager->getRepository(User::class);
    $userExists = $userRepository->findOneBy(['username' => $name]);

    if($userExists != null) {
        $entityManager->remove($userExists);
        $entityManager->flush();

        echo "<p>Usuario borrado: $name</p>" . PHP_EOL;
        echo '<br><a href="/">Volver a home</a>';

    } else {
        echo "<p>El usuario $name no existe en la base de datos.</p>" . PHP_EOL;
        echo '<br><a href="/">Volver a home</a>';
    }
}

function funcionListadoResultados(): void
{
    global $routes;
    $rutaCreateResult = $routes->get('ruta_create_result')->getPath();

    $entityManager = Utils::getEntityManager();

    $resultRepository = $entityManager->getRepository(Result::class);
    $results = $resultRepository->findAll();

    echo <<< __MARCA_FIN
        <h1>RESULTADOS</h1>
            <table border="1px">
                <thead>
                    <th>ID</th>
                    <th>Result</th>
                    <th>UserId</th>
                    <th>Username</th>
                    <th>Date</th>
                </thead>
                <tbody>
__MARCA_FIN;
    foreach ($results as $result) {
        $id = $result->getId();
        $resultId = $result->getResult();
        $user = $result->getUser();
        $userId = $user->getId();
        $username = $user->getUsername();
        $date = $result->getTime()->format('Y-m-d H:i:s');
        echo <<< ____MARCA_FIN
            <tr>
                <td>$id</td>
                <td>$resultId</td>
                <td>$userId</td>
                <td>$username</td>
                <td>$date</td>
                <td>
                    <a href="/results/$resultId">Borrar Resultado</a>
                </td>
                <td>
                    <a href="/result/update/$resultId">Actualizar Resultado</a>
                </td>
            </tr>
            
____MARCA_FIN;
    }
    echo '</tbody></table>';
    echo "<br><a href=$rutaCreateResult>Crear Resultado</a><br>";
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
                <label for="fecha">Inserte una fecha: </label>
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
    $entityManager = Utils::getEntityManager();
    $resultRepository = $entityManager->getRepository(Result::class);
    $oldResult = $resultRepository->findOneBy(['result' => $name]);
    if ($oldResult != null) {
        if (empty($_POST)){
            echo <<< ___MARCA_FIN
            <form method="post">
                <fieldset>
                    <legend>Actualizar resultado $name</legend>
                    <label for="resultId">Inserte un nuevo id para el resultado: </label>
                    <input type="number" name="resultId" placeholder="ID resultado">
                    <br>
                    <label for="userId">Inserte el nuevo ID del usuario asociado: </label>
                    <input type="number" name="userId" placeholder="ID usuario">
                    <br>
                    <label for="fecha">Inserte una nueva fecha: </label>
                    <input type="date" name="fecha" placeholder="Fecha">
                    <br>
                    <button type="submit">Enviar</button>
                </fieldset>
            </form>
___MARCA_FIN;
        } else {
            $userExists = true;
            try {
                if ($_POST['resultId'] != null) {
                    $oldResult->setResult($_POST['resultId']);
                }
                if ($_POST['userId'] != null) {
                    $user = $entityManager->getRepository(User::class)->find($_POST['userId']);
                    if($user !== null) {
                        $oldResult->setUser($user);
                    } else {
                        $userExists = false;
                    }
                }
                if ($_POST['fecha'] !== null) {
                    $oldResult->setTime(new DateTime($_POST['fecha']));
                }
            } catch (Throwable $t) {
                echo $t->getMessage();
            }
            if ($resultRepository->findOneBy(['result' => $_POST['resultId']]) !== null) {
                echo '<p>El resultado ya existe en la BBDD</p>';
            } else if(!$userExists) {
                echo '<p>El usuario introducido no existe</p>';
            } else {
                try {
                    $entityManager->merge($oldResult);
                    $entityManager->flush();

                    echo '<p>Resultado actualizado correctamente</p>';
                } catch(Throwable $t) {
                    echo $t->getMessage();
                }
            }
        }
    }

    echo '<br><a href="/">Volver a home</a>';
}

function funcionResultado(string $name) {
    $entityManager = Utils::getEntityManager();
    $resultRepository = $entityManager->getRepository(Result::class);
    $resultExists = $resultRepository->findOneBy(['result' => $name]);

    if($resultExists != null) {
        $entityManager->remove($resultExists);
        $entityManager->flush();

        echo "<p>Resultado borrado: $name</p>" . PHP_EOL;
        echo '<br><a href="/">Volver a home</a>';

    } else {
        echo "<p>El resultado $name no existe en la base de datos.</p>" . PHP_EOL;
        echo '<br><a href="/">Volver a home</a>';
    }
}

