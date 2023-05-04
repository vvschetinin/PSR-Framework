<?php 
namespace App\Http\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\HtmlResponse;

class AuthController 
{
  public function auth(ServerRequestInterface $request)
  {
    return new HtmlResponse('I am a auth page');
  }
}

?>