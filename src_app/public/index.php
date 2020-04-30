<?php

require_once '../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Aura\Router\RouterContainer;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

//Load env vars
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

// Add Loger
$log = new Logger('test');
$log->pushHandler(new StreamHandler(__DIR__.'/../logs/prod.log', Logger::WARNING));

$log->warning('wellcome');


$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => getenv('DB_DRIVER'),
    'host'      => getenv('DB_HOST'),
    'database'  => getenv('DB_DATABASE'),
    'username'  => getenv('DB_USERNAME'),
    'password'  => getenv('DB_PASSWORD'),
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

//Get Request
$request = Zend\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

// Router
$routerContainer = new RouterContainer();
$map = $routerContainer->getMap();

// Routing
$map->get('home_index', '/', '../index.php');
$map->get('paises', '/paises', '../paises.php');
$map->get('user', '/users',
    [
        'controller' => 'App\Controllers\UserController',
        'action'     => 'indexAction'
    ]
);
$map->get('user_add', '/users/add',
    [
        'controller' => 'App\Controllers\UserController',
        'action'     => 'addAction'
    ]
);

$matcher = $routerContainer->getMatcher();
$route   = $matcher->match($request);

if(!$route){
    echo '404 - Not Found';
}else{
    $handlerData    =  $route->handler;
    $controllerName =  $handlerData['controller'];
    $actionName     =  $handlerData['action'];

    $controller = new $controllerName;
    $controller->$actionName();
}
