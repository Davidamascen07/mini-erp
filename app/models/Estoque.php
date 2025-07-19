<?php
require_once 'BaseModel.php';

/**
 * Modelo para gerenciar estoque
 */
class Estoque extends BaseModel {
    protected $table = 'estoques';

    /**
     * Cria um registro de estoque
     */
    public function create($produto_id, $variacao_id, $quantidade) {
        $stmt = $this->db->prepare("INSERT INTO estoques (produto_id, variacao_id, quantidade) VALUES (:produto_id, :variacao_id, :quantidade)");
        $stmt->bindParam(':produto_id', $produto_id);
        $stmt->bindParam(':variacao_id', $variacao_id);
        $stmt->bindParam(':quantidade', $quantidade);
        
        if ($stmt->execute()) {
            return $this->lastInsertId();
        }
        return false;
    }

    /**
     * Atualiza quantidade em estoque
     */
    public function updateQuantidade($produto_id, $variacao_id, $quantidade) {
        $stmt = $this->db->prepare("UPDATE estoques SET quantidade = :quantidade WHERE produto_id = :produto_id AND variacao_id = :variacao_id");
        $stmt->bindParam(':produto_id', $produto_id);
        $stmt->bindParam(':variacao_id', $variacao_id);
        $stmt->bindParam(':quantidade', $quantidade);
        return $stmt->execute();
    }

    /**
     * Diminui quantidade em estoque
     */
    public function diminuirEstoque($produto_id, $variacao_id, $quantidade) {
        $stmt = $this->db->prepare("UPDATE estoques SET quantidade = quantidade - :quantidade WHERE produto_id = :produto_id AND variacao_id = :variacao_id AND quantidade >= :quantidade");
        $stmt->bindParam(':produto_id', $produto_id);
        $stmt->bindParam(':variacao_id', $variacao_id);
        $stmt->bindParam(':quantidade', $quantidade);
        return $stmt->execute() && $stmt->rowCount() > 0;
    }

    /**
     * Verifica se há estoque suficiente
     */
    public function verificarEstoque($produto_id, $variacao_id, $quantidade) {
        $stmt = $this->db->prepare("SELECT quantidade FROM estoques WHERE produto_id = :produto_id AND variacao_id = :variacao_id");
        $stmt->bindParam(':produto_id', $produto_id);
        $stmt->bindParam(':variacao_id', $variacao_id);
        $stmt->execute();
        
        $resultado = $stmt->fetch();
        return $resultado && $resultado['quantidade'] >= $quantidade;
    }

    /**
     * Busca estoque por produto e variação
     */
    public function findByProdutoVariacao($produto_id, $variacao_id) {
        $stmt = $this->db->prepare("SELECT * FROM estoques WHERE produto_id = :produto_id AND variacao_id = :variacao_id");
        $stmt->bindParam(':produto_id', $produto_id);
        $stmt->bindParam(':variacao_id', $variacao_id);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Delete estoque por produto
     */
    public function deleteByProduto($produto_id) {
        $stmt = $this->db->prepare("DELETE FROM estoques WHERE produto_id = :produto_id");
        $stmt->bindParam(':produto_id', $produto_id);
        return $stmt->execute();
    }
}
?>
