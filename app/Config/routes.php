<?php

use FastRoute\RouteCollector;

return function (RouteCollector $r) {

    // Rota Padrão (HOME)
    $r->addRoute('GET', '/', 'HomeController@index');

    // Rota Associado |----------------------------------------------------------------------------------
    // GET /associados (Listagem)
    $r->addRoute('GET', '/associados', 'AssociadoController@index');

    // GET /associados/novo (Visualizar Formulário de Criação)
    $r->addRoute('GET', '/associados/novo', 'AssociadoController@create');

    // POST /associados (Salvar Novo Associado)
    $r->addRoute('POST', '/associados', 'AssociadoController@store');

    // GET /associados/editar/{id} (Visualizar Formulário de Edição)
    $r->addRoute('GET', '/associados/editar/{id}', 'AssociadoController@edit'); 

    // POST /associados/atualizar (Editar Associado)
    $r->addRoute('PUT', '/associados/atualizar/{id}', 'AssociadoController@update');

    // DELETE /associados/{id} (Excluir Associado)
    $r->addRoute('DELETE', '/associados/{id}', 'AssociadoController@destroy');

};