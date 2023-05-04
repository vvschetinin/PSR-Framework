<?php 
namespace Tests\App\Http\Action;

use App\Http\Action\HomeAction;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;

class HelloActionTest extends TestCase 
{
  public function testGuest() 
  {
    $action = new HomeAction();

    $request = new ServerRequest();
    $response = $action($request);

    self::assertEquals(200, $response->getStatusCode());
    self::assertEquals('Hello, Guest!', $response->getBody()->getContents());
  }

  public function testJohn() 
  {
    $action = new HomeAction();

    $request = (new ServerRequest())
      ->withQueryParams(['name' => 'John']);

    $response = $action($request);

    self::assertEquals('Hello, John!', $response->getBody()->getContents());
  }

  
}



?>