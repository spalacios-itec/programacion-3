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
$request =  Laminas\Diactoros\ServerRequestFactory::fromGlobals(
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
$map->get('paises', '/paises', '../countries.twig');
$map->get('user', '/user',
    [
        'controller' => 'App\Controllers\UserController',
        'action'     => 'indexAction'
    ]
);
$map->get('user_api_list', '/api/user',
    [
        'controller' => 'App\Controllers\Api\UserController',
        'action'     => 'indexAction'
    ]
);
$map->post('user_api_create', '/api/user',
    [
        'controller' => 'App\Controllers\Api\UserController',
        'action'     => 'createAction'
    ]
);
$map->get('user_api_view', '/api/user/{id}',
    [
        'controller' => 'App\Controllers\Api\UserController',
        'action'     => 'viewAction'
    ]
);

$map->get('user.new', '/user/new',
    [
        'controller' => 'App\Controllers\UserController',
        'action'     => 'createAction'
    ]
);
$map->post('user.save', '/user/new',
    [
        'controller' => 'App\Controllers\UserController',
        'action'     => 'createAction'
    ]
);
$map->get('user.view', '/user/{id}',
    [
        'controller' => 'App\Controllers\UserController',
        'action'     => 'viewAction'
    ]
);


$matcher = $routerContainer->getMatcher();
$route   = $matcher->match($request);

// add route attributes to the request
//http://auraphp.com/packages/3.x/Router/getting-started.html
foreach ($route->attributes as $key => $val) {
    $request = $request->withAttribute($key, $val);
}

if(!$route){
    echo '404 - Not Found';
}else{
    $handlerData    =  $route->handler;
    $controllerName =  $handlerData['controller'];
    $actionName     =  $handlerData['action'];

    $controller = new $controllerName;
    $response   = $controller->$actionName($request);

    echo $response->getBody();
}

