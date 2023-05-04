<?php 
namespace App\Http\Action;

use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\HtmlResponse;

class HomeAction 
{
  public function __invoke(ServerRequestInterface $request)
  {
    $name = $request->getQueryParams()['name'] ?? 'Guest';
    return new HtmlResponse('Hello, ' . $name . '!');
  }

}

?>