<?php
// app/Controllers/AssociadoController.php

namespace App\Controllers;

use App\Models\AssociadoModel;
use App\Models\AnuidadeModel;
use App\Models\CobrancaModel;
use App\Core\View;

class AssociadoController {
    
    private AssociadoModel $model;
    private View $view; 

    public function __construct()
    {

        $this->model = new AssociadoModel(); 
        $this->view = new View(); 
    }

    // Ação para a rota '/associados'
    public function index() {
        // Busca os dados usando o Model
        $associados = $this->model->getAll();
        
        // Carrega a View, passando os dados
        $this->view->render('associados/index', [
            'titulo' => 'Lista de Associados',
            'associados' => $associados
        ]);
    }

    
}