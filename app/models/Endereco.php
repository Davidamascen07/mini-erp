<?php
require_once 'BaseModel.php';

/**
 * Modelo para gerenciar endereços dos pedidos
 */
class Endereco extends BaseModel {
    protected $table = 'enderecos';

    /**
     * Cria um novo endereço
     */
    public function create($pedido_id, $cep, $rua, $numero, $complemento, $bairro, $cidade, $estado) {
        $stmt = $this->db->prepare("INSERT INTO enderecos (pedido_id, cep, rua, numero, complemento, bairro, cidade, estado) VALUES (:pedido_id, :cep, :rua, :numero, :complemento, :bairro, :cidade, :estado)");
        $stmt->bindParam(':pedido_id', $pedido_id);
        $stmt->bindParam(':cep', $cep);
        $stmt->bindParam(':rua', $rua);
        $stmt->bindParam(':numero', $numero);
        $stmt->bindParam(':complemento', $complemento);
        $stmt->bindParam(':bairro', $bairro);
        $stmt->bindParam(':cidade', $cidade);
        $stmt->bindParam(':estado', $estado);
        
        if ($stmt->execute()) {
            return $this->lastInsertId();
        }
        return false;
    }

    /**
     * Busca endereço por pedido
     */
    public function findByPedido($pedido_id) {
        $stmt = $this->db->prepare("SELECT * FROM enderecos WHERE pedido_id = :pedido_id");
        $stmt->bindParam(':pedido_id', $pedido_id);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Consulta CEP via ViaCEP
     */
    public function consultarCEP($cep) {
        $cep = preg_replace('/\D/', '', $cep); // Remove caracteres não numéricos
        
        if (strlen($cep) != 8) {
            return false;
        }

        $url = "https://viacep.com.br/ws/{$cep}/json/";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200) {
            $data = json_decode($response, true);
            
            // Verifica se não retornou erro
            if (!isset($data['erro'])) {
                return [
                    'cep' => $data['cep'],
                    'logradouro' => $data['logradouro'],
                    'bairro' => $data['bairro'],
                    'localidade' => $data['localidade'],
                    'uf' => $data['uf']
                ];
            }
        }
        
        return false;
    }
}
?>
