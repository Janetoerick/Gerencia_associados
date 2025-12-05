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

    /**
     * Exibe o formulário para criar um novo valor de anuidade (Ano/Valor).
     * Rota: GET /anuidades/nova
     */
    public function create()
    {

        $dados = [
            'ano_sugerido' => date('Y') + 1
        ];

        $this->view->render('anuidades/create', $dados);
    }

    /**
     * Processa a criação ou edição do valor de uma anuidade.
     * Rota: POST /anuidades
     */
    public function store()
    {
        $data = [
            'ano' => (int)trim($_POST['ano'] ?? 0),
            'valor' => (float)trim($_POST['valor'] ?? 0.0),
        ];

        // Validação simples
        if ($data['ano'] <= 0 || $data['valor'] <= 0) {
            $_SESSION['dados_antigos'] = $data;
            $_SESSION['msg_erro'] = "O Ano e o Valor devem ser maiores que zero.";
            header("Location: /anuidades/nova");
            exit();
        }
        
        // Valida se o ano já está cadastrado
        if ($this->model->getValorByAno($data['ano']) !== null) { 
            $_SESSION['dados_antigos'] = $data;
            $_SESSION['msg_erro'] = "Anuidade para o ano de {$data['ano']} já está cadastrada. Use a rota de edição para atualizar o valor, se necessário.";
            header("Location: /anuidades/nova");
            exit();
        }

        $gerarCobranca = isset($_POST['gerar_cobranca']);

        if ($this->model->save($data)) {
            if ($gerarCobranca) {
                $cobrancaModel = new \App\Models\CobrancaModel();

                $count = $cobrancaModel->gerarCobrancaEmMassa($data['ano'], $data['valor']);
                $_SESSION['msg_sucesso'] = "Anuidade {$data['ano']} criada com sucesso! Foram geradas {$count} cobranças.";
            } else {
                $_SESSION['msg_sucesso'] = "Anuidade {$data['ano']} criada com sucesso. Nenhuma cobrança gerada automaticamente.";
            }

            header("Location: /anuidades");
            exit();
        } else {
            $_SESSION['dados_antigos'] = $data;
            $_SESSION['msg_erro'] = "Erro ao cadastrar anuidade. Tente novamente.";
            header("Location: /anuidades/nova");
            exit();
        }
    }

}