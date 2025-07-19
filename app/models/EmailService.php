<?php
/**
 * Classe para envio de emails
 */
class EmailService {
    private $from = 'naoresponder@minierp.com';
    private $fromName = 'Mini ERP';

    /**
     * Envia email de confirmação de pedido
     */
    public function enviarConfirmacaoPedido($email_cliente, $pedido) {
        $assunto = "Confirmação do Pedido #{$pedido['id']}";
        
        $corpo = $this->criarTemplateConfirmacao($pedido);
        
        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            "From: {$this->fromName} <{$this->from}>",
            "Reply-To: {$this->from}",
            'X-Mailer: PHP/' . phpversion()
        ];
        
        return mail($email_cliente, $assunto, $corpo, implode("\r\n", $headers));
    }

    /**
     * Cria template HTML para confirmação do pedido
     */
    private function criarTemplateConfirmacao($pedido) {
        $endereco = $pedido['endereco'];
        $itens_html = '';
        
        foreach ($pedido['itens'] as $item) {
            $subtotal_item = $item['preco_unitario'] * $item['quantidade'];
            $itens_html .= "
                <tr>
                    <td>{$item['produto_nome']} - {$item['nome_variacao']}</td>
                    <td>{$item['quantidade']}</td>
                    <td>R$ " . number_format($item['preco_unitario'], 2, ',', '.') . "</td>
                    <td>R$ " . number_format($subtotal_item, 2, ',', '.') . "</td>
                </tr>";
        }

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Confirmação do Pedido</title>
            <style>
                body { font-family: Arial, sans-serif; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #4f46e5; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; }
                table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
                th { background-color: #f8f9fa; }
                .total { font-weight: bold; font-size: 18px; }
                .address { background-color: #f8f9fa; padding: 15px; margin: 20px 0; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Pedido Confirmado!</h1>
                </div>
                
                <div class='content'>
                    <h2>Pedido #{$pedido['id']}</h2>
                    <p><strong>Data:</strong> " . date('d/m/Y H:i', strtotime($pedido['data'])) . "</p>
                    <p><strong>Status:</strong> {$pedido['status']}</p>
                    
                    <h3>Itens do Pedido:</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Qtd</th>
                                <th>Valor Unit.</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            {$itens_html}
                        </tbody>
                    </table>
                    
                    <table>
                        <tr>
                            <td><strong>Subtotal:</strong></td>
                            <td>R$ " . number_format($pedido['subtotal'], 2, ',', '.') . "</td>
                        </tr>
                        <tr>
                            <td><strong>Desconto:</strong></td>
                            <td>R$ " . number_format($pedido['desconto'], 2, ',', '.') . "</td>
                        </tr>
                        <tr>
                            <td><strong>Frete:</strong></td>
                            <td>R$ " . number_format($pedido['frete'], 2, ',', '.') . "</td>
                        </tr>
                        <tr class='total'>
                            <td><strong>Total:</strong></td>
                            <td>R$ " . number_format($pedido['total'], 2, ',', '.') . "</td>
                        </tr>
                    </table>
                    
                    <div class='address'>
                        <h3>Endereço de Entrega:</h3>
                        <p>
                            {$endereco['rua']}, {$endereco['numero']}<br>
                            " . (!empty($endereco['complemento']) ? $endereco['complemento'] . "<br>" : "") . "
                            {$endereco['bairro']}<br>
                            {$endereco['cidade']} - {$endereco['estado']}<br>
                            CEP: {$endereco['cep']}
                        </p>
                    </div>
                    
                    <p>Obrigado pela sua compra!</p>
                </div>
            </div>
        </body>
        </html>";
    }
}
?>
