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

    /**
     * Exibe o formulário de edição para um ano específico.
     * rota GET /anuidades/{ano}/editar
     */
    public function edit(int $ano)
    {
        $valor = $this->model->getValorByAno($ano);

        if (!$valor) {
            $_SESSION['msg_erro'] = "Anuidade não encontrada.";
            header("Location: /anuidades");
            exit();
        }

        // Renderiza a view de edição com os dados da anuidade
        $this->view->render('anuidades/edit', ['valor' => $valor, 'ano' => $ano], 'form_layout');
    }

    /**
     * Processa a atualização do valor de uma anuidade.
     * rota POST /anuidades/{ano}/update
     */
    public function update(int $ano)
    {
        
        $data = [
            'ano' => $ano,
            'valor' => trim(str_replace(',', '.', $_POST['valor'] ?? '')),
        ];

        if (empty($data['valor']) || !is_numeric($data['valor'])) {
            $_SESSION['msg_erro'] = "Valor é obrigatório e deve ser numérico.";
            header("Location: /anuidades/{$ano}/editar");
            exit();
        }

        if ($this->model->save($data)) {
            $_SESSION['msg_sucesso'] = "Anuidade para o ano {$ano} atualizada com sucesso!";
            header("Location: /anuidades");
            exit();
        } else {
            $_SESSION['msg_erro'] = "Erro ao atualizar anuidade.";
            header("Location: /anuidades/{$ano}/editar");
            exit();
        }
    }

    /**
     * Processa a exclusão de uma anuidade.
     * Rota: POST /anuidades/{ano}/delete (Simulando DELETE)
     */
    public function destroy(int $ano)
    {
        if ($this->model->delete($ano)) {
            $_SESSION['msg_sucesso'] = "Anuidade para o ano {$ano} excluída com sucesso!";
        } else {
            $_SESSION['msg_erro'] = "Erro ao excluir. Verifique se há cobranças ativas vinculadas a este ano.";
        }

        header("Location: /anuidades");
        exit();
    }

}