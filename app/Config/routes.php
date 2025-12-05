<?php

use FastRoute\RouteCollector;

return function (RouteCollector $r) {

    // Rota Padrão (HOME)
    $r->addRoute('GET', '/', 'HomeController@index');

};