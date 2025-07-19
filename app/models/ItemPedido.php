<?php
require_once 'BaseModel.php';

/**
 * Modelo para gerenciar itens dos pedidos
 */
class ItemPedido extends BaseModel {
    protected $table = 'itens_pedido';

    /**
     * Adiciona item ao pedido
     */
    public function create($pedido_id, $produto_id, $variacao_id, $quantidade, $preco_unitario) {
        $stmt = $this->db->prepare("INSERT INTO itens_pedido (pedido_id, produto_id, variacao_id, quantidade, preco_unitario) VALUES (:pedido_id, :produto_id, :variacao_id, :quantidade, :preco_unitario)");
        $stmt->bindParam(':pedido_id', $pedido_id);
        $stmt->bindParam(':produto_id', $produto_id);
        $stmt->bindParam(':variacao_id', $variacao_id);
        $stmt->bindParam(':quantidade', $quantidade);
        $stmt->bindParam(':preco_unitario', $preco_unitario);
        
        if ($stmt->execute()) {
            return $this->lastInsertId();
        }
        return false;
    }

    /**
     * Busca itens por pedido
     */
    public function findByPedido($pedido_id) {
        $stmt = $this->db->prepare("SELECT ip.*, p.nome as produto_nome, v.nome_variacao 
                                   FROM itens_pedido ip 
                                   JOIN produtos p ON ip.produto_id = p.id 
                                   LEFT JOIN variacoes v ON ip.variacao_id = v.id 
                                   WHERE ip.pedido_id = :pedido_id");
        $stmt->bindParam(':pedido_id', $pedido_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Remove todos os itens de um pedido
     */
    public function deleteByPedido($pedido_id) {
        $stmt = $this->db->prepare("DELETE FROM itens_pedido WHERE pedido_id = :pedido_id");
        $stmt->bindParam(':pedido_id', $pedido_id);
        return $stmt->execute();
    }
}
?>
