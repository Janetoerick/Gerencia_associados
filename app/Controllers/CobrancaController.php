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

        // busca o NOME
        $associado = $this->associadoModel->getById($associadoId); 
        
        // busca a LISTA de cobranças
        $cobrancas = $this->model->findByAssociadoId($associadoId); 

        // busca o valor total em cobranças do associado
        $total = $this->model->valorTotalAssociadoId($associadoId);
        
        // 3. Renderiza a View com ambos os dados
        $this->view->render('cobrancas/index', [
            'associado' => $associado, 
            'cobrancas' => $cobrancas,
            'total' => $total,
            'titulo' => 'Cobranças de ' . $associado['nome']
        ]);
    }

    /**
     * Exibe o formulário para criar uma nova cobrança manual para um associado.
     * rota GET /associados/{id}/cobrancas/novo
     */
    public function create(int $id)
    {
        
        $associado = $this->associadoModel->getById($id);

        $dados = [
            'associado' => $associado,
            'ano_corrente' => date('Y'),
            'titulo' => 'Adicionar cobrança - ' . $associado['nome']
        ];
        $this->view->render('cobrancas/create', $dados);
    }

    /**
     * Processa a criação de uma nova cobrança.
     * rota POST /associados/cobrancas
     */
    public function store()
    {

        $associadoId = (int)($_POST['associado_id'] ?? 0);
        $ano = (int)($_POST['ano_referencia'] ?? 0);

        // Validação Básica
        if ($associadoId <= 0 || $ano <= 0) {
            $_SESSION['msg_erro'] = "Dados insuficientes ou inválidos para criar a cobrança.";
            header("Location: /associados/{$associadoId}/cobrancas/novo");
            exit();
        }

        $valor = $this->anuidadeModel->getValorByAno($ano);

        // Validação se o ano tem registro para anuidade
        if (empty($valor)) {
            $_SESSION['msg_erro'] = "Ano de referência não criado para anuidade.";
            header("Location: /associados/{$associadoId}/cobrancas/novo");
            exit();
        }

        $has_cobranca = $this->model->verifyAssociadoIdHasAno($associadoId, $ano);


        if ($has_cobranca) {
            $_SESSION['msg_erro'] = "Associado já tem cobrança no ano $ano";
            header("Location: /associados/{$associadoId}/cobrancas/novo");
            exit();
        }
        
        // Monta o array de dados
        $cobrancaData = [
            'Associado_id' => $associadoId,
            'Anuidade_ano' => $ano,
            'valor_cobrado' => $valor
        ];
        
        // Tenta criar a cobrança
        try {
            if ($this->model->createCobranca($cobrancaData)) {
                $_SESSION['msg_sucesso'] = "Cobrança criada com sucesso para o Associado (Ano {$ano})!";
            } else {
                $_SESSION['msg_erro'] = "Erro ao criar cobrança manual no Model.";
            }
        } catch (\Exception $e) {
            // Se o Model lançar uma exceção (erro SQL), capturamos a mensagem
            $_SESSION['msg_erro'] = "Erro Fatal no SQL: " . $e->getMessage();
        }
        
        // Redirecionamento
        header("Location: /associados/{$associadoId}/cobrancas"); 
        exit();
    }

    /**
     * Processa o pagamento de uma cobrança
     * rota PUT /cobrancas/{id}/pagar
     */
    public function registrarPagamento(int $id)
    {
        $cobrancaId = $id;
        $associadoId = (int)($_POST['associado_id'] ?? 0);

        // Validação Básica
        if ($cobrancaId <= 0 || $associadoId <= 0) {
            $_SESSION['msg_erro'] = "ID da cobrança inválido na URL.";
            header("Location: /associados"); 
            exit();
        }

        if ($this->model->registrarPagamento($cobrancaId)) {
            $_SESSION['msg_sucesso'] = "Cobrança paga com sucesso!";
        } else {
            $_SESSION['msg_erro'] = "Erro no pagamento!";
        }
        header("Location: /associados/{$associadoId}/cobrancas"); 
        exit();

    }

    /**
     * Processa o pagamento de uma cobrança
     * rota POST /associados/{id}/cobrancas/pagar_tudo
     */
    public function registrarPagamentoTotal(int $id)
    {

        $associadoId = $id;

        // Validação Básica
        if ($associadoId <= 0) {
            $_SESSION['msg_erro'] = "ID da cobrança inválido na URL.";
            header("Location: /associados"); 
            exit();
        }

        if ($this->model->registrarTodosPagamentos($associadoId)) {
            $_SESSION['msg_sucesso'] = "Cobrança paga com sucesso!";
        } else {
            $_SESSION['msg_erro'] = "Erro no pagamento!";
        }
        header("Location: /associados/{$associadoId}/cobrancas"); 
        exit();

    }

    /**
     * Gera a cobrança para todos os associados do ano atual
     * rota POST /cobrancas/gerar_anuidade_atual
     */
    public function gerarAnuidadeAtual()
    {
        // Identifica o ano corrente
        $ano_atual = date('Y');

        // Chama o Model para executar a lógica de geração
        $resultado = $this->model->gerarCobrancasEmLote($ano_atual);

        if ($resultado['sucesso']) {
            $_SESSION['msg_sucesso'] = "Cobranças do ano {$ano_atual} geradas com sucesso! ({$resultado['criadas']} cobranças criadas).";
        } else {
            $_SESSION['msg_erro'] = "Erro na geração das cobranças: " . $resultado['mensagem'];
        }

        // Redireciona de volta para a tela de anuidades
        header('Location: /anuidades');
        return;
    }

}