<?php

namespace app\routes;

use app\helpers\Request;
use app\helpers\Uri;
use Exception;
use Throwable;

class Router
{

     const CONTROLLER_NAMESPACE = 'app\\controllers';

     public static function load(string $controller, string $method)
     {

          try {
               $controllerNamespace = self::CONTROLLER_NAMESPACE . '\\' . $controller;
               if (!class_exists($controllerNamespace)) {
                    throw new Exception("O Controller {$controller} não existe");
               }

               $controllerInstance = new $controllerNamespace;


               if (!method_exists($controllerInstance, $method)) {
                    throw new Exception("O Metodo {$method} não existe no Controller {$controller}");
               }

               $controllerInstance->$method();
          } catch (\Throwable $th) {

               echo $th->getMessage();
          }
     }
     public static function routes(): array
     {
          return [
               'get' => [
                    '/' => fn () => self::load('homeController', 'index'),
                    '/contact'  => fn () => self::load('ContactController', 'index'),
               ],
               'post' => [
                    '/contact' => fn () => self::load("contactController", 'store')
               ]
          ];
     }

     public static function execute()
     {
          try{

               $routes = self::routes();
               $request = Request::get();
               $uri = Uri::get('path');

               if(!isset($routes[$request])){
                    throw new Exception("A Rota Nao Existe");
               }

               if(!array_key_exists($uri, $routes[$request])){
                    throw new Exception("A Rota nao existe");
               }

               $router = $routes[$request][$uri];

               if(!is_callable($router)){
                    throw new Exception("A Rota Nao Existe");
               }
               
               $router();


          }catch (Throwable $th){
               echo $th->getMessage();
          }
     }

}
