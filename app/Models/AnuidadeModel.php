<?php

namespace App\Models;

use App\Core\Database;

class AnuidadeModel 
{
    private $db;

    public function __construct()
    {
        // Certifique-se de que a conexão é obtida corretamente
        $this->db = new Database(); 
    }

    /**
     * Busca o valor de anuidade por um ano específico
     */
    public function getValorByAno(int $ano)
    {
        $sql = "SELECT valor FROM anuidade WHERE ano = :ano";
        try {
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindParam(':ano', $ano, \PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $resultado ? $resultado['valor'] : null;
        } catch (\PDOException $e) {
            error_log("Erro ao buscar valor da anuidade: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Lista todos os valores de anuidade cadastrados.
     */
    public function findAll()
    {
        $sql = "SELECT * FROM anuidade ORDER BY ano DESC";
        try {
            $stmt = $this->db->getConnection()->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erro ao listar anuidades: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Salva ou atualiza um valor de anuidade.
     * Usa REPLACE INTO ou lógica de INSERT OR UPDATE.
     * Como 'ano' é a PRIMARY KEY, o INSERT OR UPDATE é mais robusto.
     */
    public function save(array $data)
    {
        // O método salva o registro. Se o ano já existir, ele atualiza o valor.
        $sql = "INSERT INTO anuidade (ano, valor) 
                VALUES (:ano, :valor)
                ON DUPLICATE KEY UPDATE valor = :valor";

        // Validação simples
        if (!is_numeric($data['ano']) || !is_numeric($data['valor']) || $data['ano'] < 1900) {
            return false;
        }

        $valorNumerico = number_format((float)$data['valor'], 2, '.', ''); // Garante 2 casas decimais

        try {
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindParam(':ano', $data['ano'], \PDO::PARAM_INT);
            $stmt->bindParam(':valor', $valorNumerico);
            
            return $stmt->execute();
        } catch (\PDOException $e) {
            error_log("Erro ao salvar anuidade: " . $e->getMessage());
            return false;
        }
    }

}