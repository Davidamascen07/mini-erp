<?php
/**
 * Mini ERP - Webhook para atualização de status de pedidos
 * Endpoint para receber atualizações externas de status de pedidos
 */

// Headers para API
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type');

// Resposta para GET (documentação)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode([
        'webhook' => 'Mini ERP - Status de Pedidos',
        'method' => 'POST',
        'content-type' => 'application/json',
        'format' => [
            'pedido_id' => 'integer (required)',
            'status' => 'string (required)'
        ],
        'example' => [
            'pedido_id' => 1,
            'status' => 'processando'
        ],
        'possible_status' => [
            'pendente', 'processando', 'enviado', 'entregue', 'cancelado'
        ],
        'test_page' => 'http://localhost/mini/teste_webhook.php'
    ]);
    exit;
}

// Só aceita requisições POST para processamento
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'error' => 'Método não permitido',
        'allowed_methods' => ['POST', 'GET'],
        'test_page' => 'http://localhost/mini/teste_webhook.php'
    ]);
    exit;
}

require_once 'app/controllers/PedidoController.php';

$controller = new PedidoController();
$controller->webhook();
?>
