<?php

namespace App\Models;

use App\Core\Database;

class CobrancaModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
    * Cria uma nova cobrança na tabela 'cobranca'.
    */
    public function createCobranca(array $data)
    {
        $pago = 0;

        $sql = "INSERT INTO cobranca (Associado_id, Anuidade_ano, valor_cobrado, pago)
                VALUES (:associado_id, :anuidade_ano, :valor_cobrado, :pago)";
        
        try {
            $stmt = $this->db->getConnection()->prepare($sql);
            
            $stmt->bindParam(':associado_id', $data['Associado_id'], \PDO::PARAM_INT);
            $stmt->bindParam(':anuidade_ano', $data['Anuidade_ano'], \PDO::PARAM_INT);
            $stmt->bindParam(':valor_cobrado', $data['valor_cobrado']);
            $stmt->bindParam(':pago', $pago);
            
            return $stmt->execute(); 
            
        } catch (\PDOException $e) {
            error_log("Erro SQL ao criar cobrança: " . $e->getMessage());
            return false;
        }
    }

    /**
    * Faz a ligação da cobrança com todos os associados.
    * Return: O número de associados que foram cobrados.
    */
    public function gerarCobrancaEmMassa(int $ano, float $valor): int 
    {
        
        $associadoModel = new \App\Models\AssociadoModel();
        $associados = $associadoModel->getAll();

        $count = 0;
        
        // Itera sobre os IDs e cria a cobrança
        foreach ($associados as $associado) {
            $cobrancaData = [
                'Associado_id' => $associado['id'],
                'Anuidade_ano' => $ano,
                'valor_cobrado' => $valor
            ];

            if ($this->createCobranca($cobrancaData)) { 
                $count++;
            }
        }

        return $count;
    }

    /**
    * Busca todas as cobranças de um associado.
    */
    public function findByAssociadoId(int $associadoId)
    {
        $sql = "SELECT 
                    c.id, 
                    c.valor_cobrado, 
                    c.pago, 
                    c.data_pagamento, 
                    c.Anuidade_ano,
                    a.valor AS valor_anuidade_base
                FROM cobranca c
                JOIN anuidade a ON c.Anuidade_ano = a.ano
                WHERE c.Associado_id = :associado_id
                ORDER BY c.Anuidade_ano DESC";
        
        try {
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindParam(':associado_id', $associadoId, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erro ao buscar cobranças por associado: " . $e->getMessage());
            return [];
        }
    }

    /**
    * Busca o valor total de cobrancas do associado pelo id.
    */
    public function valorTotalAssociadoId(int $associadoId)
    {
        $sql = "SELECT SUM(valor_cobrado) AS total FROM cobranca WHERE pago = 0 AND Associado_id = :associadoId;";

        try {
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindParam(':associadoId', $associadoId, \PDO::PARAM_INT);
            $stmt->execute();

            $total = $stmt->fetchColumn();

            return (float)($total ?? 0.00);

        } catch (\PDOException $e) {
            error_log("Erro ao buscar cobranças por associado: " . $e->getMessage());
            return [];
        }
    }

    /**
    * Verifica se o associado já tem cobranca no ano.
    */
    public function verifyAssociadoIdHasAno(int $associadoId, int $ano)
    {
        $sql = "SELECT COUNT(*) FROM cobranca WHERE Associado_id = :associado_id AND Anuidade_ano = :ano";
        
        try {
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindParam(':associado_id', $associadoId, \PDO::PARAM_INT);
            $stmt->bindParam(':ano', $ano, \PDO::PARAM_INT);
            $stmt->execute();
            
            $count = $stmt->fetchColumn();
            
            // Retorna true se a contagem for maior que zero
            return $count > 0;
            
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
    * Registra o pagamento de uma cobrança específica.
    */
    public function registrarPagamento(int $cobrancaId): bool
    {
        // Define a data de pagamento para a data atual
        $dataPagamento = date('Y-m-d');
        
        $sql = "UPDATE cobranca 
                SET pago = 1, data_pagamento = :dataPagamento 
                WHERE id = :cobrancaId AND pago = 0";

        try {
            $stmt = $this->db->getConnection()->prepare($sql);
            
            $stmt->bindParam(':dataPagamento', $dataPagamento);
            $stmt->bindParam(':cobrancaId', $cobrancaId, \PDO::PARAM_INT);
            
            // Executa a atualização e retorna o resultado (true/false)
            return $stmt->execute();
            
        } catch (\PDOException $e) {
            error_log("Erro SQL ao registrar pagamento (ID: {$cobrancaId}): " . $e->getMessage());
            return false;
        }
    }

    /**
    * Registra o pagamento de todas as cobranças.
    */
    public function registrarTodosPagamentos(int $associadoId): bool
    {
        // Define a data de pagamento para a data atual
        $dataPagamento = date('Y-m-d');
        
        $sql = "UPDATE cobranca 
                SET pago = 1, data_pagamento = :dataPagamento 
                WHERE Associado_id = :associadoId AND pago = 0";

        try {
            $stmt = $this->db->getConnection()->prepare($sql);
            
            $stmt->bindParam(':dataPagamento', $dataPagamento);
            $stmt->bindParam(':associadoId', $associadoId, \PDO::PARAM_INT);
            
            // Executa a atualização e retorna o resultado (true/false)
            return $stmt->execute();
            
        } catch (\PDOException $e) {
            error_log("Erro SQL ao registrar os pagamentos do associado(ID: {$associadoId}): " . $e->getMessage());
            return false;
        }
    }

    /**
    * Registra a cobrança para todos os associados cadastrados do ano.
    */
    public function gerarCobrancasEmLote(int $ano): array
    {
        try {
            // --- 1. BUSCAR VALOR DA ANUIDADE ---
            // Você precisará de um método para buscar o valor na tabela 'anuidades'

            $anuidadeModel = new \App\Models\AnuidadeModel(); 
            $anuidade = $anuidadeModel->getValorByAno($ano); 

            if (!$anuidade) {
                return ['sucesso' => false, 
                    'mensagem' => "Valor da anuidade para o ano {$ano} não foi cadastrado. Crie o registro primeiro."
                ];
            }

            $valor_base = $anuidade;

            // Busca todos os associados
            $associadoModel = new \App\Models\AssociadoModel();
            $associados = $associadoModel->getAll();
            
            $criadas = 0; // para contagem de quantos associados recebem a cobrança

            $pago = 0;

            foreach ($associados as $associado) {
                // Verifica se a cobrança já existe para evitar duplicidade (usando a constraint UNIQUE)
                
                $sql_check = "SELECT id FROM cobranca WHERE Associado_id = :associado_id AND Anuidade_ano = :ano";
                $stmt_check = $this->db->getConnection()->prepare($sql_check);
                $stmt_check->bindParam(':associado_id', $associado['id'], \PDO::PARAM_INT);
                $stmt_check->bindParam(':ano', $ano, \PDO::PARAM_INT);
                $stmt_check->execute();

                if ($stmt_check->fetchColumn()) {
                    continue; // Cobrança já existe, pula para o próximo
                }

                // Cria o registro da cobrança
                $sql = "INSERT INTO cobranca (Associado_id, Anuidade_ano, valor_cobrado, pago)
                            VALUES (:associado_id, :anuidade_ano, :valor_cobrado, :pago)";

                $stmt = $this->db->getConnection()->prepare($sql);

                $stmt->bindParam(':associado_id', $associado['id'], \PDO::PARAM_INT);
                $stmt->bindParam(':anuidade_ano', $ano, \PDO::PARAM_INT);
                $stmt->bindParam(':valor_cobrado', $valor_base);
                $stmt->bindParam(':pago', $pago, \PDO::PARAM_INT);
                
                
                $stmt->execute();

                $criadas++;
            }

            return ['sucesso' => true, 'criadas' => $criadas, 'mensagem' => 'Cobranças geradas.'];

        } catch (\PDOException $e) {
            // Logar o erro em produção
            return ['sucesso' => false, 'mensagem' => "Erro de Banco de Dados: " . $e->getMessage()];
        }
    }

}