<?php 
namespace App\Http\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\HtmlResponse;

class SiteController 
{
  public function home(ServerRequestInterface $request)
  {
    $name = $request->getQueryParams()['name'] ?? 'Guest';
    return new HtmlResponse('Hello, ' . $name . '!');
  }

  public function about(ServerRequestInterface $request)
  {
    return new HtmlResponse('I am a simple site');
  }

}

?>