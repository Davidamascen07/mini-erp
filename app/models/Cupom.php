<?php
require_once 'BaseModel.php';

/**
 * Modelo para gerenciar cupons de desconto
 */
class Cupom extends BaseModel {
    protected $table = 'cupons';

    /**
     * Cria um novo cupom
     */
    public function create($codigo, $desconto_percentual, $validade, $valor_minimo = 0) {
        $stmt = $this->db->prepare("INSERT INTO cupons (codigo, desconto_percentual, validade, valor_minimo) VALUES (:codigo, :desconto_percentual, :validade, :valor_minimo)");
        $stmt->bindParam(':codigo', $codigo);
        $stmt->bindParam(':desconto_percentual', $desconto_percentual);
        $stmt->bindParam(':validade', $validade);
        $stmt->bindParam(':valor_minimo', $valor_minimo);
        
        if ($stmt->execute()) {
            return $this->lastInsertId();
        }
        return false;
    }

    /**
     * Atualiza um cupom
     */
    public function update($id, $codigo, $desconto_percentual, $validade, $valor_minimo = 0) {
        $stmt = $this->db->prepare("UPDATE cupons SET codigo = :codigo, desconto_percentual = :desconto_percentual, validade = :validade, valor_minimo = :valor_minimo WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':codigo', $codigo);
        $stmt->bindParam(':desconto_percentual', $desconto_percentual);
        $stmt->bindParam(':validade', $validade);
        $stmt->bindParam(':valor_minimo', $valor_minimo);
        return $stmt->execute();
    }

    /**
     * Busca cupom por código
     */
    public function findByCodigo($codigo) {
        $stmt = $this->db->prepare("SELECT * FROM cupons WHERE codigo = :codigo AND ativo = 1");
        $stmt->bindParam(':codigo', $codigo);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Valida cupom
     */
    public function validarCupom($codigo, $subtotal) {
        $cupom = $this->findByCodigo($codigo);
        
        if (!$cupom) {
            return ['valido' => false, 'erro' => 'Cupom não encontrado'];
        }

        // Verifica se está ativo
        if (!$cupom['ativo']) {
            return ['valido' => false, 'erro' => 'Cupom inativo'];
        }

        // Verifica validade
        $hoje = date('Y-m-d');
        if ($cupom['validade'] < $hoje) {
            return ['valido' => false, 'erro' => 'Cupom expirado'];
        }

        // Verifica valor mínimo
        if ($subtotal < $cupom['valor_minimo']) {
            return ['valido' => false, 'erro' => 'Valor mínimo do pedido não atingido'];
        }

        return ['valido' => true, 'cupom' => $cupom];
    }

    /**
     * Calcula desconto
     */
    public function calcularDesconto($subtotal, $desconto_percentual) {
        return ($subtotal * $desconto_percentual) / 100;
    }

    /**
     * Desativa cupom
     */
    public function desativar($id) {
        $stmt = $this->db->prepare("UPDATE cupons SET ativo = 0 WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    /**
     * Ativa cupom
     */
    public function ativar($id) {
        $stmt = $this->db->prepare("UPDATE cupons SET ativo = 1 WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
