<?php
require_once 'BaseModel.php';

/**
 * Modelo para gerenciar variações de produtos
 */
class Variacao extends BaseModel {
    protected $table = 'variacoes';

    /**
     * Cria uma nova variação
     */
    public function create($produto_id, $nome_variacao) {
        $stmt = $this->db->prepare("INSERT INTO variacoes (produto_id, nome_variacao) VALUES (:produto_id, :nome_variacao)");
        $stmt->bindParam(':produto_id', $produto_id);
        $stmt->bindParam(':nome_variacao', $nome_variacao);
        
        if ($stmt->execute()) {
            return $this->lastInsertId();
        }
        return false;
    }

    /**
     * Atualiza uma variação
     */
    public function update($id, $nome_variacao) {
        $stmt = $this->db->prepare("UPDATE variacoes SET nome_variacao = :nome_variacao WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nome_variacao', $nome_variacao);
        return $stmt->execute();
    }

    /**
     * Busca variações por produto
     */
    public function findByProduto($produto_id) {
        $stmt = $this->db->prepare("SELECT * FROM variacoes WHERE produto_id = :produto_id ORDER BY id");
        $stmt->bindParam(':produto_id', $produto_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Delete variações por produto
     */
    public function deleteByProduto($produto_id) {
        $stmt = $this->db->prepare("DELETE FROM variacoes WHERE produto_id = :produto_id");
        $stmt->bindParam(':produto_id', $produto_id);
        return $stmt->execute();
    }
}
?>
