<?php
// app/Core/Router.php

namespace App\Core;

use FastRoute\Dispatcher;
use FastRoute\simpleDispatcher;

class Router
{
    public function dispatch()
    {
        // Carrega o mapa de rotas
        $dispatcher = \FastRoute\simpleDispatcher( 
            require __DIR__ . '/../Config/routes.php'
        );

        // Coleta a URI e o Método
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        // Transforma o método para DELETE e PUT
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }

        // Encontrar a rota
        $routeInfo = $dispatcher->dispatch($method, $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                header("HTTP/1.0 404 Not Found");
                echo "404 Not Found - Rota não definida.";
                break;

            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                header("HTTP/1.0 405 Method Not Allowed");
                echo "405 Method Not Allowed. Métodos permitidos: " . implode(', ', $allowedMethods);
                break;

            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                // Divide o Handler em Controller e Método
                list($controllerName, $methodName) = explode('@', $handler);
                
                // Monta o nome completo da classe
                $fullControllerName = "App\\Controllers\\" . $controllerName;

                // Instancia e chama o método
                if (!class_exists($fullControllerName)) {
                    die("Erro: Controller '{$controllerName}' não encontrado.");
                }
                
                $controller = new $fullControllerName();
                
                // Chama o método, passando os parâmetros
                call_user_func_array([$controller, $methodName], $vars);
                break;
        }
    }
}