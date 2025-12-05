<?php

namespace App\Models;

use App\Core\Database;

class AssociadoModel
{
    protected Database $db; 

    public function __construct()
    {
        $this->db = \App\Core\Database::getInstance();
    }

    /**
     * Busca todos os associados no banco de dados.
    */
    public function getAll()
    {
        try {
            $sql = "SELECT a.*, 
                SUM(CASE WHEN c.pago = 0 THEN 1 ELSE 0 END) AS total_pendencias 
                FROM associado AS a INNER JOIN cobranca AS c ON a.id = c.Associado_id GROUP BY a.id;";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "Erro ao listar associados: " . $e->getMessage();
            return [];
        }
    }
    
}