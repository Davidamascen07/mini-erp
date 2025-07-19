<?php
require_once 'BaseModel.php';

/**
 * Modelo para gerenciar produtos
 */
class Produto extends BaseModel {
    protected $table = 'produtos';

    /**
     * Cria um novo produto
     */
    public function create($nome, $preco) {
        $stmt = $this->db->prepare("INSERT INTO produtos (nome, preco) VALUES (:nome, :preco)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':preco', $preco);
        
        if ($stmt->execute()) {
            return $this->lastInsertId();
        }
        return false;
    }

    /**
     * Atualiza um produto
     */
    public function update($id, $nome, $preco) {
        $stmt = $this->db->prepare("UPDATE produtos SET nome = :nome, preco = :preco WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':preco', $preco);
        return $stmt->execute();
    }

    /**
     * Busca produtos com suas variações e estoque
     */
    public function findWithVariacoes() {
        $sql = "SELECT p.*, v.id as variacao_id, v.nome_variacao, e.quantidade 
                FROM produtos p
                LEFT JOIN variacoes v ON p.id = v.produto_id
                LEFT JOIN estoques e ON v.id = e.variacao_id
                ORDER BY p.id, v.id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Busca um produto específico com suas variações
     */
    public function findWithVariacoesById($id) {
        $sql = "SELECT p.*, v.id as variacao_id, v.nome_variacao, e.quantidade 
                FROM produtos p
                LEFT JOIN variacoes v ON p.id = v.produto_id
                LEFT JOIN estoques e ON v.id = e.variacao_id
                WHERE p.id = :id
                ORDER BY v.id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>
