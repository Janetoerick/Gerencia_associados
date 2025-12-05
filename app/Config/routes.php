<?php

use FastRoute\RouteCollector;

return function (RouteCollector $r) {

    // Rota Padrão (HOME)
    $r->addRoute('GET', '/', 'HomeController@index');

    // Rota Associado |----------------------------------------------------------------------------------
    // GET /associados (Listagem)
    $r->addRoute('GET', '/associados', 'AssociadoController@index');

    // GET /associados/novo (Visualizar Formulário de Criação de Associado)
    $r->addRoute('GET', '/associados/novo', 'AssociadoController@create');

    // POST /associados (Salvar Novo Associado)
    $r->addRoute('POST', '/associados', 'AssociadoController@store');

    // GET /associados/editar/{id} (Visualizar Formulário de Edição de Associado)
    $r->addRoute('GET', '/associados/editar/{id}', 'AssociadoController@edit'); 

    // PUT /associados/atualizar (Editar Associado)
    $r->addRoute('PUT', '/associados/atualizar/{id}', 'AssociadoController@update');

    // DELETE /associados/{id} (Excluir Associado)
    $r->addRoute('DELETE', '/associados/{id}', 'AssociadoController@destroy');


    // Rota Anuidade |----------------------------------------------------------------------------------
    // GET /anuidades (Listagem)
    $r->addRoute('GET', '/anuidades', 'AnuidadeController@index');

    // Exibir formulário de criação
    // GET /anuidades/nova (Visualizar Formulário de Criação de Anuidade)
    $r->addRoute('GET', '/anuidades/nova', 'AnuidadeController@create');
    
    // POST /anuidades (Salvar Nova Anuidade)
    $r->addRoute('POST', '/anuidades', 'AnuidadeController@store');

    // GET /anuidades/{ano}/editar (Visualizar Formulário de Edição de Anuidade)
    $r->addRoute('GET', '/anuidades/{ano:\d+}/editar', 'AnuidadeController@edit');

    // PUT /anuidades/{ano}/update (Editar Anuidade)
    $r->addRoute('PUT', '/anuidades/{ano:\d+}/update', 'AnuidadeController@update');

    // DELETE /anuidades/{ano}/delete (Excluir Anuidade)
    $r->addRoute('DELETE', '/anuidades/{ano:\d+}/delete', 'AnuidadeController@destroy');
};