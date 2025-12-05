<?php

namespace App\Controllers;

use App\Models\CobrancaModel;
use App\Models\AssociadoModel;
use App\Models\AnuidadeModel;
use App\Core\View;

class CobrancaController
{

    private CobrancaModel $model;
    private AssociadoModel $associadoModel;
    private AnuidadeModel $anuidadeModel;
    private View $view;

    public function __construct()
    {
        $this->model = new CobrancaModel();
        $this->associadoModel = new AssociadoModel();
        $this->anuidadeModel = new AnuidadeModel();
        $this->view = new View();
    }

    /**
     * Exibe a lista de cobranças para um associado específico.
     * Rota: GET /associados/{id}/cobrancas
     */
    public function index(int $id)
    {
        $associadoId = $id;

        // 1. Usa o MODEL DE ASSOCIADO para buscar o NOME
        $associado = $this->associadoModel->getById($associadoId); 
        
        // 2. Usa o MODEL DE COBRANÇA para buscar a LISTA
        $cobrancas = $this->model->findByAssociadoId($associadoId); 

        $total = $this->model->valorTotalAssociadoId($associadoId);
        
        // 3. Renderiza a View com ambos os dados
        $this->view->render('cobrancas/index', [
            'associado' => $associado, 
            'cobrancas' => $cobrancas,
            'total' => $total,
            'titulo' => "Cobranças de " . $associado['nome']
        ]);
    }

}