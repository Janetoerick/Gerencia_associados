<?php

namespace App\Controllers;

use App\Core\View;

class HomeController {

    private View $view;

    public function __construct()
    {
        $this->view = new View(); 
    }
    
    // Ação para a rota raiz ('/')
    public function index() {
        $this->view->render('home/index', [
            'titulo' => 'Bem-vindo ao Sistema de Associação'
        ]);
    }
}