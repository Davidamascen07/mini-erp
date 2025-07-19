<?php
require_once 'BaseModel.php';

/**
 * Modelo para gerenciar pedidos
 */
class Pedido extends BaseModel {
    protected $table = 'pedidos';

    /**
     * Cria um novo pedido
     */
    public function create($subtotal, $frete, $desconto, $total, $cupom_codigo = null, $email_cliente = null) {
        $stmt = $this->db->prepare("INSERT INTO pedidos (subtotal, frete, desconto, total, cupom_codigo, email_cliente) VALUES (:subtotal, :frete, :desconto, :total, :cupom_codigo, :email_cliente)");
        $stmt->bindParam(':subtotal', $subtotal);
        $stmt->bindParam(':frete', $frete);
        $stmt->bindParam(':desconto', $desconto);
        $stmt->bindParam(':total', $total);
        $stmt->bindParam(':cupom_codigo', $cupom_codigo);
        $stmt->bindParam(':email_cliente', $email_cliente);
        
        if ($stmt->execute()) {
            return $this->lastInsertId();
        }
        return false;
    }

    /**
     * Atualiza status do pedido
     */
    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE pedidos SET status = :status WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':status', $status);
        return $stmt->execute();
    }

    /**
     * Calcula frete baseado no subtotal
     */
    public function calcularFrete($subtotal) {
        if ($subtotal >= 200.00) {
            return 0; // Frete grátis
        } elseif ($subtotal >= 52.00 && $subtotal <= 166.59) {
            return 15.00;
        } else {
            return 20.00;
        }
    }

    /**
     * Busca pedidos com detalhes
     */
    public function findWithDetails() {
        $sql = "SELECT p.*, 
                       COUNT(ip.id) as total_itens,
                       GROUP_CONCAT(CONCAT(pr.nome, ' (', v.nome_variacao, ') x', ip.quantidade) SEPARATOR ', ') as itens
                FROM pedidos p
                LEFT JOIN itens_pedido ip ON p.id = ip.pedido_id
                LEFT JOIN produtos pr ON ip.produto_id = pr.id
                LEFT JOIN variacoes v ON ip.variacao_id = v.id
                GROUP BY p.id
                ORDER BY p.id DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Busca pedido com itens e endereço
     */
    public function findWithItems($id) {
        // Busca dados do pedido
        $pedido = $this->findById($id);
        if (!$pedido) return null;

        // Busca itens do pedido
        $stmt = $this->db->prepare("SELECT ip.*, p.nome as produto_nome, v.nome_variacao 
                                   FROM itens_pedido ip 
                                   JOIN produtos p ON ip.produto_id = p.id 
                                   LEFT JOIN variacoes v ON ip.variacao_id = v.id 
                                   WHERE ip.pedido_id = :pedido_id");
        $stmt->bindParam(':pedido_id', $id);
        $stmt->execute();
        $pedido['itens'] = $stmt->fetchAll();

        // Busca endereço
        $stmt = $this->db->prepare("SELECT * FROM enderecos WHERE pedido_id = :pedido_id");
        $stmt->bindParam(':pedido_id', $id);
        $stmt->execute();
        $pedido['endereco'] = $stmt->fetch();

        return $pedido;
    }
}
?>
