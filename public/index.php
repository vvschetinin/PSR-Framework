<?php

use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\RouteCollection;
use Framework\Http\Router\Router;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

// Несколько классов обьединенные в одном контроллере
use App\Http\Controllers\SiteController;
use App\Http\Controllers\AuthController;
// Один класс в одном контроллере
use App\Http\Action\ContactAction;
use App\Http\Action\Blog\IndexAction;
use App\Http\Action\Blog\ShowAction;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

// INIT

$routes = new RouteCollection();

// Несколько классов обьединенные в одном контроллере
$routes->get('home', '/', [new SiteController(), 'home']);
$routes->get('about', '/about', [new SiteController(), 'about']);
$routes->get('auth', '/auth', [new AuthController(), 'auth']);
// Один класс в одном контроллере передается как функция
$routes->get('contact', '/contact', new ContactAction());
$routes->get('blog', '/blog', new IndexAction());
$routes->get('blog_show', '/blog/{id}', new ShowAction());

$router = new Router($routes);

// Running

$request = ServerRequestFactory::fromGlobals();
try {
  $result = $router->match($request);
  foreach ($result->getAttributes() as $attribute => $value) {
    $request = $request->withAttribute($attribute, $value);
  }
  // @var callable $action 
  $action = $result->getHandler();
  $response = $action($request);
} catch (RequestNotMatchedException $e) {
  $response = new JsonResponse(['error' => 'Undefined Page'], 404);
}

// Postprocessing

$response = $response->withHeader('X-Developer', 'VSchetinin');

// Sending

$emitter = new SapiEmitter();
$emitter->emit($response);

?>