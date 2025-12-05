<?php

use FastRoute\RouteCollector;

return function (RouteCollector $r) {

    // Rota Padrão (HOME)
    $r->addRoute('GET', '/', 'HomeController@index');

    // Rota Associado |----------------------------------------------------------------------------------
    // GET /associados (Listagem)
    $r->addRoute('GET', '/associados', 'AssociadoController@index');

    // GET /associados/novo (Formulário de Criação)
    $r->addRoute('GET', '/associados/novo', 'AssociadoController@create');

    // POST /associados (Salvar Novo Associado)
    $r->addRoute('POST', '/associados', 'AssociadoController@store');

};