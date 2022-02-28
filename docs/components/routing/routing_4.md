<?php

require __DIR__.'/../vendor/autoload.php';

$router = new \Laventure\Component\Routing\Router();

$router->domain('http://localhost:8080');

$router->patterns([
    'id'    => '\d+', // [0-9]+
    'lang'  => '\w+', // _local
]);


/*
$router->get('/', 'FrontController@home', 'home')->groupName('admin.')->name('homepage');
$router->get('/about', 'FrontController@about', 'about');
$router->get('/post/{id}', 'PostController@show', 'post.show')->whereNumber('id');
$router->map(['GET', 'POST'], '/contact', 'FrontController@contact', 'contact');
$router->get('/welcome', function () {
    return "Hello, World!";
}, 'welcome');

*/


$router->get('/welcome', function () {
    return "Hello, World!";
}, 'welcome');



$options = [
    'prefix'     => '{lang}/admin/user',
    'namespace'  => 'Admin\\',
    'as'         => 'admin.user.',
    'middleware' => [\App\Middleware\AuthenticatedMiddleware::class]
];

$router->group(function (\Laventure\Component\Routing\Router $router) {
      $router->get('', 'UserController@index', 'list');
      $router->get('/{id}', 'UserController@show', 'show');
      $router->map('GET|POST', '/', 'UserController@new', 'new');
      $router->map('GET|POST', '/{id}/edit', 'UserController@edit', 'edit');
      $router->delete('/{id}', 'UserController@delete', 'delete');
      $router->get('/restore/{id}', 'UserController@restore', 'restore');
}, $options);


$router->get('/', 'FrontController@home')->name('homepage');
$router->get('/about', 'FrontController@about', 'about');
$router->map(['GET', 'POST'], '/contact', 'FrontController@contact', 'contact');


$router->remove('admin.user.restore');


$route = $router->match($_SERVER['REQUEST_METHOD'], $uri = $_SERVER['REQUEST_URI']);

if (! $route) {
    dd("Route {$uri} not found!");
}


echo '<h2>Routes</h2>';
dump($router->getRoutes());

echo '<h2>Routes By Method</h2>';
dump($router->getRoutesByMethod());

echo '<h2>Routes By Name</h2>';
dump($router->getRoutesByName());

echo '<h2>Matched Route</h2>';
dump($route);

echo '<h2>Generate path</h2>';
dump($router->generate('admin.user.list'));
dump($router->generate('admin.user.show', ['lang' => 'en', 'id' => 2]));