require __DIR__.'/../vendor/autoload.php';

$router = new \Laventure\Component\Routing\Router();

$router->get('/', 'FrontController@home', 'home')->groupName('admin.')->name('homepage');
$router->get('/about', 'FrontController@about', 'about');
$router->get('/post/{id}', 'PostController@show', 'post.show')->whereNumber('id');
$router->map(['GET', 'POST'], '/contact', 'FrontController@contact', 'contact');
$router->get('/welcome', function () {
return "Hello, World!";
}, 'welcome');



$router->domain('http://localhost:8080');

echo '<h2>Routes</h2>';
dump($router->getRoutes());

echo '<h2>Routes By Method</h2>';
dump($router->getRoutesByMethod());

echo '<h2>Routes By Name</h2>';
dump($router->getRoutesByName());



$route = $router->match($_SERVER['REQUEST_METHOD'], $uri = $_SERVER['REQUEST_URI']);

if (! $route) {
dd("Route {$uri} not found!");
}


echo '<h2>Matched Route</h2>';
dump($route);

echo '<h2>Generate path</h2>';
dump($router->generate('admin.homepage'));