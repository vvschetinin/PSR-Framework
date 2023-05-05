<?php

use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\RouteCollection;
use Framework\Http\Router\Router;
use Framework\Http\Router\ActionResolver;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

// INIT

$routes = new RouteCollection();

// Один класс в одном контроллере передается как функция
$routes->get('home', '/', App\Http\Action\HomeAction::class);
$routes->get('contact', '/contact', App\Http\Action\ContactAction::class);
$routes->get('blog', '/blog', App\Http\Action\Blog\IndexAction::class);
$routes->get('blog_show', '/blog/{id}', App\Http\Action\Blog\ShowAction::class);
// Несколько классов обьединенные в одном контроллере
$routes->get('about', '/about', [App\Http\Controllers\SiteController::class, 'about']);
$routes->get('auth', '/auth', [App\Http\Controllers\AuthController::class, 'auth']);

$router = new Router($routes);
$resolver = new ActionResolver();

// Running

$request = ServerRequestFactory::fromGlobals();
try {
  $result = $router->match($request);
  foreach ($result->getAttributes() as $attribute => $value) {
    $request = $request->withAttribute($attribute, $value);
  }
  $action = $resolver->resolve($result->getHandler());
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