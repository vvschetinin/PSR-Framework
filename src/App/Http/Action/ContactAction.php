<?php 
namespace App\Http\Action;

use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\HtmlResponse;

class ContactAction 
{
  public function __invoke(ServerRequestInterface $request)
  {
    return new HtmlResponse('I am a contact page');
  }
}

?>