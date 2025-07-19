<?php
require_once 'BaseController.php';
require_once 'app/models/Pedido.php';

/**
 * Controller para gerenciar pedidos
 */
class PedidoController extends BaseController {
    private $pedidoModel;

    public function __construct() {
        $this->pedidoModel = new Pedido();
    }

    /**
     * Lista pedidos
     */
    public function index() {
        $pedidos = $this->pedidoModel->findWithDetails();

        $this->render('pedidos/index', [
            'pedidos' => $pedidos,
            'flash' => $this->getFlash()
        ]);
    }

    /**
     * Visualiza pedido específico
     */
    public function visualizar($id) {
        $pedido = $this->pedidoModel->findWithItems($id);

        if (!$pedido) {
            $this->setFlash('error', 'Pedido não encontrado');
            $this->redirect('pedidos.php');
        }

        $this->render('pedidos/visualizar', [
            'pedido' => $pedido,
            'flash' => $this->getFlash()
        ]);
    }

    /**
     * Atualiza status do pedido
     */
    public function atualizarStatus() {
        if (!$this->isPost()) {
            $this->json(['success' => false, 'message' => 'Método inválido']);
        }

        $id = intval($_POST['id']);
        $status = $this->sanitize($_POST['status']);

        try {
            $this->pedidoModel->updateStatus($id, $status);
            $this->json(['success' => true, 'message' => 'Status atualizado com sucesso!']);
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => 'Erro ao atualizar status']);
        }
    }

    /**
     * Webhook para atualizar status do pedido
     */
    public function webhook() {
        // Verifica se é uma requisição POST
        if (!$this->isPost()) {
            http_response_code(405);
            echo json_encode(['error' => 'Método não permitido']);
            exit;
        }

        // Lê dados JSON do corpo da requisição
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        // Log para debug - informações detalhadas
        $debugInfo = [
            'method' => $_SERVER['REQUEST_METHOD'],
            'content_type' => $_SERVER['CONTENT_TYPE'] ?? 'not set',
            'input_length' => strlen($input),
            'input_content' => $input,
            'json_decode_result' => $data,
            'json_last_error' => json_last_error_msg()
        ];
        $this->logWebhook(0, 'debug', 'Debug Info: ' . json_encode($debugInfo));
        
        // Validação melhorada com detalhes do erro
        if (!$data) {
            http_response_code(400);
            echo json_encode([
                'error' => 'JSON inválido', 
                'received' => $input,
                'length' => strlen($input),
                'json_error' => json_last_error_msg(),
                'content_type' => $_SERVER['CONTENT_TYPE'] ?? 'not set',
                'format_expected' => '{"pedido_id": 1, "status": "novo_status"}',
                'postman_help' => [
                    'step1' => 'Selecione Body > raw > JSON',
                    'step2' => 'Adicione Header: Content-Type: application/json',
                    'step3' => 'Cole o JSON no body (não nos params)'
                ]
            ]);
            exit;
        }

        if (!isset($data['pedido_id'])) {
            http_response_code(400);
            echo json_encode([
                'error' => 'Campo pedido_id é obrigatório',
                'received' => $data,
                'format_expected' => '{"pedido_id": 1, "status": "novo_status"}'
            ]);
            exit;
        }

        if (!isset($data['status'])) {
            http_response_code(400);
            echo json_encode([
                'error' => 'Campo status é obrigatório',
                'received' => $data,
                'format_expected' => '{"pedido_id": 1, "status": "novo_status"}'
            ]);
            exit;
        }

        $pedido_id = intval($data['pedido_id']);
        $status = $this->sanitize($data['status']);

        // Verifica se pedido existe
        $pedido = $this->pedidoModel->findById($pedido_id);
        if (!$pedido) {
            http_response_code(404);
            echo json_encode(['error' => 'Pedido não encontrado', 'pedido_id' => $pedido_id]);
            exit;
        }

        try {
            if ($status === 'cancelado') {
                // Remove pedido cancelado
                $this->pedidoModel->delete($pedido_id);
                $message = 'Pedido cancelado e removido';
            } else {
                // Atualiza status do pedido
                $this->pedidoModel->updateStatus($pedido_id, $status);
                $message = 'Status do pedido atualizado';
            }

            // Log da operação
            $this->logWebhook($pedido_id, $status, $message);

            http_response_code(200);
            echo json_encode([
                'success' => true, 
                'message' => $message,
                'pedido_id' => $pedido_id,
                'new_status' => $status
            ]);
        } catch (Exception $e) {
            $this->logWebhook($pedido_id, $status, 'Erro: ' . $e->getMessage());
            
            http_response_code(500);
            echo json_encode(['error' => 'Erro interno do servidor', 'details' => $e->getMessage()]);
        }
    }

    /**
     * Log das operações do webhook
     */
    private function logWebhook($pedido_id, $status, $message) {
        $log = date('Y-m-d H:i:s') . " - Pedido ID: {$pedido_id}, Status: {$status}, Resultado: {$message}\n";
        file_put_contents('logs/webhook.log', $log, FILE_APPEND | LOCK_EX);
    }
}
?>
