<?php 
namespace Framework\Http\Router;

use Psr\Http\Message\ServerRequestInterface;

interface Router 
{
  /**
   * @param ServerRequestInterface $request
   * @trows RequestNotMatchedException 
   * @return Result
   */
  public function match(ServerRequestInterface $request): Result;

  /**
   * @param $name
   * @param array $param
   * @trows RequestNotMatchedException 
   * @return string
   */
  public function generate($name, array $params = []): string;

}



?>