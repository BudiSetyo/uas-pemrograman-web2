<?php

session_start();

require_once __DIR__ . '/app/helpers.php';
require_once __DIR__ . '/config/database.php';

// Autoload controllers and models
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/app/controllers/' . $class . '.php',
        __DIR__ . '/app/models/' . $class . '.php',
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Simple Router
class Router
{
    private $routes = [];

    public function get($pattern, $handler)
    {
        $this->routes['GET'][$pattern] = $handler;
    }

    public function post($pattern, $handler)
    {
        $this->routes['POST'][$pattern] = $handler;
    }

    public function dispatch($method, $uri)
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = rtrim($uri, '/');
        if ($uri === '') {
            $uri = '/';
        }

        if (!isset($this->routes[$method])) {
            return false;
        }

        foreach ($this->routes[$method] as $pattern => $handler) {
            $regex = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $pattern);
            $regex = '#^' . $regex . '$#';

            if (preg_match($regex, $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                [$controllerName, $methodName] = explode('@', $handler);

                if (!class_exists($controllerName)) {
                    throw new Exception("Controller $controllerName not found");
                }

                $controller = new $controllerName();
                $controller->$methodName(...array_values($params));
                return true;
            }
        }
        return false;
    }
}

$router = new Router();

// Home
$router->get('/', 'HomeController@index');

// Auth
$router->get('/login', 'AuthController@loginForm');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@registerForm');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');

// Profile
$router->get('/profile', 'ProfileController@index');
$router->post('/profile', 'ProfileController@update');

// Articles
$router->get('/articles', 'ArticleController@index');
$router->get('/articles/create', 'ArticleController@create');
$router->post('/articles/create', 'ArticleController@store');
$router->get('/articles/edit/{id}', 'ArticleController@edit');
$router->post('/articles/edit/{id}', 'ArticleController@update');
$router->get('/articles/delete/{id}', 'ArticleController@delete');
$router->get('/articles/{slug}', 'ArticleController@show');

try {
    $method = $_SERVER['REQUEST_METHOD'];
    $uri = $_SERVER['REQUEST_URI'];

    $result = $router->dispatch($method, $uri);

    if ($result === false) {
        http_response_code(404);
        view('404');
    }
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}