<?php
require_once 'config/database.php';

/**
 * Classe base para todos os modelos
 */
abstract class BaseModel {
    protected $db;
    protected $table;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    /**
     * Busca todos os registros da tabela
     */
    public function findAll() {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Busca um registro por ID
     */
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Deleta um registro por ID
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    /**
     * Executa uma query personalizada
     */
    protected function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt;
    }

    /**
     * Retorna o último ID inserido
     */
    protected function lastInsertId() {
        return $this->db->lastInsertId();
    }
}
?>
