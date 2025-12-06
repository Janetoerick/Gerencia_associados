<?php

session_start();

// Configuração para mostrar erros na tela (APENAS PARA DEBUG/DESENVOLVIMENTO)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Inclui o Autoloader do Composer
require_once __DIR__ . '/../vendor/autoload.php'; 

use App\Core\Router;

// Limpeza do URI
$uri = $_SERVER['REQUEST_URI'];

// Remove a string da query
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

// Adiciona a barra inicial se estiver faltando, garantindo o formato '/rota'
if (empty($uri)) {
    $uri = '/';
}

// Armazena o URI limpo em $_SERVER['REQUEST_URI'] para o Router utilizar
$_SERVER['REQUEST_URI'] = $uri; 

// Despacha a requisição
$router = new Router();
$router->dispatch();