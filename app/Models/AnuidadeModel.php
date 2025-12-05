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

}