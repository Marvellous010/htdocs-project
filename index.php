<?php

/**
 * Front-controller voor alle pagina's
 * Verwerkt binnenkomende verzoeken en laadt de juiste pagina
 */


$requestUri = $_SERVER['REQUEST_URI'];
$path = trim(parse_url($requestUri, PHP_URL_PATH), '/');

if ($path === 'logout') {
    require_once __DIR__ . '/actions/logout.php';
    exit;
}

if ($path === 'login-handler') {
    require_once __DIR__ . '/actions/login.php';
    exit;
}

if ($path === 'register-handler') {
    require_once __DIR__ . '/actions/register.php';
    exit;
}



if ($path === 'account') {
    require_once __DIR__ . '/pages/account.php';
    exit;
}

if ($path === 'update-account') {
    require_once __DIR__ . '/actions/update-account.php';
    exit;
}

if ($path === 'create-reservation') {
    require_once __DIR__ . '/actions/create-reservation.php';
    exit;
}

if ($path === 'my-reservations') {
    require_once __DIR__ . '/pages/my-reservations.php';
    exit;
}

if ($path === 'hulp') {
    require_once __DIR__ . '/pages/hulp.php';
    exit;
}

if ($path === 'events') {
    require_once __DIR__ . '/pages/events.php';
    exit;
}


$page = $path ?: 'home';
$file = __DIR__ . '/pages/' . $page . '.php';

if (file_exists($file)) {
    include $file;
} else {
    http_response_code(404);
    include __DIR__ . '/pages/404.php';
}
