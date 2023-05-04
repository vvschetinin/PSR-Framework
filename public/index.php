<?php

use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\RouteCollection;
use Framework\Http\Router\Router;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

// INIT

$routes = new RouteCollection();

class SiteController 
{
  public function home(ServerRequestInterface $request): ResponseInterface
  {
    $name = $request->getQueryParams()['name'] ?? 'Guest';
    return new HtmlResponse('Hello, ' . $name . '!');
  }

  public function about(ServerRequestInterface $request): ResponseInterface 
  {
    return new HtmlResponse('I am a simple site');
  }

}

$routes->get('home', '/', [new SiteController(), 'home']);
$routes->get('about', '/about', [new SiteController(), 'about']);

$routes->get('blog', '/blog', function () {
  return new JsonResponse([
    ['id' => 2, 'title' => 'The Second Post'],
    ['id' => 1, 'title' => 'The First Post'],
  ]);
});

$routes->get('blog_show', '/blog/{id}', function (ServerRequestInterface $request) {
  $id = $request->getAttribute('id');
  if($id > 5) {
    return new JsonResponse(['error' => 'Undefined Page'], 404);
  }
  return new JsonResponse(['id' => $id, 'title' => 'Post #' . $id]);
}, ['id' => '\d+']);

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