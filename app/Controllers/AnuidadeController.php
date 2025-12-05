<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\AnuidadeModel; 

class AnuidadeController 
{
    private $model;
    private $view;

    public function __construct() 
    {
        $this->model = new AnuidadeModel();
        $this->view = new View(); 
    }

    /**
     * View para listar associados.
     * Rota: GET /anuidades
     */
    public function index()
    {
        $anuidades = $this->model->findAll();
        
        $this->view->render('anuidades/index', ['anuidades' => $anuidades]);
    }

}