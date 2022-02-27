<?php

require __DIR__.'/../vendor/autoload.php';

$router = new \Laventure\Component\Routing\Router('http://localhost:8080');

$router->patterns([
    'id'    => '\d+', // [0-9]+
    'lang'  => '\w+', // _local
]);

$router->module('App\\Controller');


$router->get('/welcome', function () {
    return "Hello, World!";
}, 'welcome');



$router->get('/', 'FrontController@home')->name('homepage');
$router->get('/about', 'FrontController@about', 'about');
$router->map(['GET', 'POST'], '/contact', 'FrontController@contact', 'contact');


$attributes = [
    'prefix'     => 'admin/user',
    'namespace'  => 'Admin\\',
    'as'         => 'admin.user.',
    'middleware' => [\App\Middleware\AuthenticatedMiddleware::class]
];

$router->prefix('admin/user')
       ->namespace('Admin\\')
       ->name('admin.user.')
       ->middleware([\App\Middleware\AuthenticatedMiddleware::class])
       ->group(function (\Laventure\Component\Routing\Router $router) {
          $router->get('s', 'UserController@index', 'list');
          $router->get('/{id}', 'UserController@show', 'show');
          $router->map('GET|POST', '/', 'UserController@create', 'new');
          $router->map('GET|POST', '/{id}/edit', 'UserController@edit', 'edit');
          $router->delete('/{id}', 'UserController@destroy', 'destroy');
          $router->get('/restore/{id}', 'UserController@restore', 'restore');
});




/*
$router->put('/post/{id}', function () {

}, 'post.edit');
*/

// $router->remove('admin.user.restore');


$route = $router->dispatch($_SERVER['REQUEST_METHOD'], $uri = $_SERVER['REQUEST_URI']);

if (! $route) {
    dd("Route {$uri} not found!");
}


$controller = $route->getTarget()['controller'];
$action     = $route->getTarget()['action'];
$params     = array_values($route->getMatches());



/**
 * @param $template
 * @param array $data
 * @return string
*/
function view($template, array $data = []): string
{
     extract($data);

     ob_start();
     require $template;
     return ob_get_clean();
}



if (! $controller) {
    echo call_user_func($action, $params);
}else{
    echo call_user_func_array([new $controller, $action], $params);
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laventure framework</title>

    <style>
        nav ul li {
             display: inline-block;
             list-style: none;
             /* border: 1px solid #ccc; */
        }

        nav ul li a {
            text-decoration: none;
        }
    </style>
</head>
<body>

   <nav>
       <ul>
           <li>
               <a href="<?= $router->generate('homepage') ?>">Home</a>
           </li>
           <li>
               <a href="<?= $router->generate('about') ?>">About</a>
           </li>
           <li>
               <a href="<?= $router->generate('contact') ?>">Contact</a>
           </li>
       </ul>
   </nav>
</body>
</html>

<?php

echo "<hr>";
/*
echo '<h2>Routes</h2>';
dump($router->getRoutes());

echo '<h2>Routes By Method</h2>';
dump($router->getRoutesByMethod());

echo '<h2>Routes By Name</h2>';
dump($router->getRoutesByName());
*/

echo '<h2>Matched Route</h2>';
dump($route);

echo '<h2>Generate path</h2>';
dump($router->generate('admin.user.list'));
dump($router->generate('admin.user.show', ['lang' => 'en', 'id' => 2]));
