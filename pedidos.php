<?php
/**
 * Mini ERP - Gerenciamento de pedidos
 */

session_start();

require_once 'app/controllers/PedidoController.php';

$controller = new PedidoController();
$action = $_GET['action'] ?? 'index';

try {
    switch ($action) {
        case 'index':
            $controller->index();
            break;
            
        case 'visualizar':
            $id = $_GET['id'] ?? null;
            if (!$id) {
                header('Location: pedidos.php');
                exit;
            }
            $controller->visualizar($id);
            break;
            
        case 'atualizar-status':
            $controller->atualizarStatus();
            break;
            
        default:
            $controller->index();
            break;
    }
} catch (Exception $e) {
    // Log do erro
    error_log("Erro no pedidos.php: " . $e->getMessage());
    
    // Se for uma requisição AJAX, retorna JSON
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Erro interno do servidor']);
        exit;
    }
    
    // Redireciona com mensagem de erro
    session_start();
    $_SESSION['flash'] = [
        'type' => 'error',
        'message' => 'Ocorreu um erro inesperado. Tente novamente.'
    ];
    
    header('Location: pedidos.php');
    exit;
}
?>
