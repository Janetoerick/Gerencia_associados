<?php
// app/Controllers/AssociadoController.php

namespace App\Controllers;

use App\Models\AssociadoModel;
use App\Models\AnuidadeModel;
use App\Models\CobrancaModel;
use App\Core\View;

class AssociadoController {
    
    private AssociadoModel $model;
    private AnuidadeModel $anuidadeModel;
    private CobrancaModel $cobrancaModel;
    private View $view; 

    public function __construct()
    {
        $this->model = new AssociadoModel(); 
        $this->anuidadeModel = new AnuidadeModel();
        $this->cobrancaModel = new CobrancaModel();
        $this->view = new View(); 
    }

    /**
    * View para listar associados.
    * rota GET '/associados'
    */
    public function index() {
        // Busca os dados usando o Model
        $associados = $this->model->getAll();
        
        // Carrega a View, passando os dados
        $this->view->render('associados/index', [
            'titulo' => 'Lista de Associados',
            'associados' => $associados
        ]);
    }

    /**
    * View para criar associado.
    * rota GET '/associados/novo'
    */
    public function create() {
        $this->view->render('associados/create', [
            'titulo' => 'Novo Associado'
        ], 'form_layout');
    }
    
    /**
    * Processa o cadastro de um novo associado e gera as cobranças devidas.
    * rota POST /associados
    */
    public function store()
    {
        // Coleta e Prepara Dados
        $data = [
            'nome' => trim($_POST['nome'] ?? ''),
            'cpf' => trim($_POST['cpf'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            // Define uma data padrão se não for enviada
            'data_filiacao' => trim($_POST['data_filiacao'] ?? date('Y-m-d')), 
        ];

        // VALIDAÇÃO BÁSICA
        if (empty($data['nome']) || empty($data['cpf']) || empty($data['data_filiacao'])) {
            $_SESSION['dados_antigos'] = $data;
            $_SESSION['msg_erro'] = "Nome, CPF e Data de Filiação são obrigatórios.";
            header("Location: /associados/novo");
            exit();
        }

        try {
            $hoje = new \DateTime(); 
            $dataSubmetida = new \DateTime($data['data_filiacao']);
            
            // Zera as horas para comparação apenas da data
            $hoje->setTime(0, 0, 0); 
            $dataSubmetida->setTime(0, 0, 0); 

            // Verifica se a data submetida é maior que hoje
            if ($dataSubmetida > $hoje) {
                $_SESSION['msg_erro'] = "A data de filiação não pode ser uma data futura.";
                header("Location: /associados/novo");
                exit();
            }

        } catch (\Exception $e) {
            // Trata formato de data inválido
            $_SESSION['msg_erro'] = "Formato de data de filiação inválido.";
            header("Location: /associados/novo");
            exit();
        }
        
        $data['cpf'] = preg_replace('/[^0-9]/', '', $data['cpf']);

        // SALVAR O ASSOCIADO
        $associadoData = $this->model->save($data);
        
        // EXTRAIR O ID
        $associadoId = is_array($associadoData) ? ($associadoData['id'] ?? null) : $associadoData;

        
        if ($associadoId && $associadoId > 0) {
            
            // GERAÇÃO DAS COBRANÇAS RETRATIVAS

            try {
                $dataFiliacao = new \DateTime($data['data_filiacao']);
                $anoFiliacao = (int)$dataFiliacao->format('Y');
                $anoAtual = (int)date('Y');
                
                $cobrançasGeradas = 0;
                
                // Loop para cada ano desde a filiação até o ano atual
                for ($ano = $anoFiliacao; $ano <= $anoAtual; $ano++) {
                    
                    $valorAnuidade = $this->anuidadeModel->getValorByAno($ano);
                    
                    // Se o valor base da anuidade for encontrado:
                    if ($valorAnuidade !== null) {
                        
                        $cobrancaData = [
                            'Associado_id' => $associadoId,
                            'Anuidade_ano' => $ano,
                            'valor_cobrado' => $valorAnuidade, 
                            'pago' => 0, 
                        ];
                        
                        if ($this->cobrancaModel->createCobranca($cobrancaData)) {
                            $cobrançasGeradas++;
                        }
                    }
                }

                // REDIRECIONAMENTO DE SUCESSO
                $_SESSION['msg_sucesso'] = " Associado cadastrado com sucesso! Foram geradas {$cobrançasGeradas} cobranças em aberto.";
            
            } catch (\Exception $e) {
                error_log("Erro Fatal ao gerar cobranças para Associado ID {$associadoId}: " . $e->getMessage());
                $_SESSION['msg_sucesso'] = " Associado cadastrado, mas houve um erro ao gerar cobranças ({$e->getMessage()}).";
            }
            
            header("Location: /associados");
            exit();

        } else {
            // Falha no INSERT do Associado
            $_SESSION['dados_antigos'] = $data;
            $_SESSION['msg_erro'] = "Erro ao salvar o associado no banco de dados. CPF ou E-mail já em uso.";
            header("Location: /associados/novo");
            exit();
        }
    }

    /**
    * View para editar associado.
    * rota GET /associados/editar/{id}
    */
    public function edit(int $id) 
    {
        $associado = $this->model->getById($id); 

        if (!$associado) {
            header('Location: /associados'); // Redireciona se não existir
            return;
        }

        $this->view->render('associados/edit', ['associado' => $associado], 'form_layout');
    }

    /**
    * Processa a atualização de um associado.
    * rota POST /associados
    */
    public function update(int $id)
    {
        
        // Coleta e limpa os dados do formulário
        $dados = [
            'nome' => trim($_POST['nome'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'cpf' => trim($_POST['cpf'] ?? ''),
            'data_filiacao' => trim($_POST['data_filiacao'] ??date('Y-m-d')),
        ];
        
        $resultado = $this->model->update($id, $dados); 

        if (is_array($resultado)) {
            $_SESSION['msg_erro'] = "Erro ao salvar o associado no banco de dados. CPF ou E-mail já em uso.";
            header("Location: /associados/editar/{$id}");
            return;
        }
        
        header('Location: /associados');
    }

    /**
    * Processa a exclusão de um associado.
    * rota DELETE /associados/{id}
    */
    public function destroy(int $id)
    {

        $resultado = $this->model->delete($id);

        if ($resultado) {
            $_SESSION['msg_sucesso'] = " Associado deletado com sucesso!";
            header('Location: /associados');
        } else {
            $_SESSION['msg_erro'] = "Erro ao deletar associado.";
            header('Location: /associados');
        }
        exit; 
    }

    
}