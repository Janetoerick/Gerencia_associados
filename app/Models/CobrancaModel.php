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

}