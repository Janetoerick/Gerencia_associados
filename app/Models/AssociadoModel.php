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
     * Busca um único associado pelo ID.
     */
    public function getById(int $id)
    {
        try {
            // CORREÇÃO AQUI: Tabela 'associado' (minúscula)
            $sql = "SELECT * FROM associado WHERE id = :id";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            
            $stmt->execute();
            
            return $stmt->fetch(\PDO::FETCH_ASSOC);

        } catch (\PDOException $e) {
            echo "Erro ao buscar associado (ID: {$id}): " . $e->getMessage();
            return null; 
        }
    }

    /**
     * Busca todos os associados no banco de dados.
    */
    public function getAll()
    {
        try {
            $sql = "SELECT a.*, 
                SUM(CASE WHEN c.pago = 0 THEN 1 ELSE 0 END) AS total_pendencias 
                FROM associado AS a LEFT JOIN cobranca AS c ON a.id = c.Associado_id GROUP BY a.id;";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "Erro ao listar associados: " . $e->getMessage();
            return [];
        }
    }

    /**
     * Salva um novo associado no banco de dados.
     */
    public function save(array $data)
    {

        $erros = $this->verify_data($data);
        if(!empty($erros)){
            return $erros;
        }

        $sql = "INSERT INTO associado (nome, email, cpf, data_filiacao) VALUES (:nome, :email, :cpf, :data_filiacao)";
        
        try {
            $stmt = $this->db->getConnection()->prepare($sql);
            
            $stmt->bindParam(':nome', $data['nome']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':cpf', $data['cpf']);
            $stmt->bindParam(':data_filiacao', $data['data_filiacao']);
            
            $stmt->execute();

            $lastId = $this->db->getConnection()->lastInsertId();

            return $lastId;

        } catch (\PDOException $e) {
            $errorCode = $e->getCode();
            $errorMessage = $e->getMessage();
            
            // Verifica se é um erro de unicidade
            if ($errorCode === '23000') {
                // Unicidade de CPF
                if (strpos($errorMessage, 'cpf_UNIQUE') !== false) {
                    return ['cpf' => 'Este CPF já está cadastrado no sistema.'];
                }
                // Unicidade de E-MAIL
                if (strpos($errorMessage, 'uk_email') !== false || strpos($errorMessage, 'email_UNIQUE') !== false) {
                    return ['email' => 'Este e-mail já está cadastrado para outro associado.'];
                }
            }
            
            error_log("Erro SQL ao inserir associado: " . $errorMessage);
            return false;
        }
    }

    /*
     * Atualiza os dados de um associado a partir do ID.
     */
    public function update(int $id, array $data)
    {
        $erros = $this->verify_data($data, $id);
        if (!empty($erros)) {
            return $erros;
        }

        $cpf_limpo = $this->clean_numeric_data($data['cpf']); 

        $sql = "UPDATE associado SET 
                    nome = :nome, 
                    email = :email, 
                    cpf = :cpf_limpo, 
                    data_filiacao = :data_filiacao
                WHERE id = :id";
        
        try {
            $stmt = $this->db->getConnection()->prepare($sql);

            $stmt->bindParam(':nome', $data['nome']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':cpf_limpo', $cpf_limpo); 
            $stmt->bindParam(':data_filiacao', $data['data_filiacao']); 
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (\PDOException $e) {
            error_log("Erro SQL de Atualização: " . $e->getMessage()); 
            return false;
        }
    }

    /**
    * Exclui um associado pelo ID.
    */
    public function delete(int $id)
    {
        $sql = "DELETE FROM associado WHERE id = :id";

        try {
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (\PDOException $e) {
            error_log("Erro SQL ao deletar associado: " . $e->getMessage());
            return false;
        }
    }

    /*
     * Verifica se os dados passados são válidos
     */
    public function verify_data(array $data, ?int $ignoreId = null)
    {
        $erros = [];

        $cpf_limpo = $this->clean_numeric_data($data['cpf']);
    
        if (strlen($cpf_limpo) !== 11) {
            $erros['cpf'] = "O CPF deve conter exatamente 11 dígitos numéricos.";
        }
        
        $email = trim($data['email']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erros['email'] = "O e-mail fornecido é inválido.";
        }
        
        if (!$this->isEmailUnique($email, $ignoreId ?? null)) {
            $erros['email'] = "Este e-mail já está cadastrado para outro associado.";
        }

        return $erros;
    }

    /**
     * Remove pontos, traços e outros caracteres não numéricos do CPF/CNPJ.
     */
    protected function clean_numeric_data(string $data): string
    {
        // Remove tudo que não for dígito (0-9)
        return preg_replace('/[^0-9]/', '', $data);
    }

    /**
     * Verifica se um e-mail já existe no banco de dados.
     */
    private function isEmailUnique(string $email, ?int $ignoreId = null): bool
    {
        // Prepara a consulta para buscar qualquer registro com este e-mail
        $sql = "SELECT id FROM associado WHERE email = :email";

        // Se estivermos editando um associado (tem ID), ignoramos o registro atual
        if ($ignoreId !== null) {
            $sql .= " AND id != :ignore_id";
        }

        try {
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindParam(':email', $email);

            if ($ignoreId !== null) {
                $stmt->bindParam(':ignore_id', $ignoreId, \PDO::PARAM_INT);
            }

            $stmt->execute();

            // Se count for zero, é único (retorna true)
            return $stmt->rowCount() === 0;

        } catch (\PDOException $e) {
            error_log("Erro SQL ao verificar unicidade de e-mail: " . $e->getMessage());
            return false; 
        }
    }
    
}