<?php
/**
 * Mini ERP - Gerenciamento de cupons de desconto
 */

session_start();

require_once 'app/controllers/CupomController.php';

$controller = new CupomController();
$action = $_GET['action'] ?? 'index';

try {
    switch ($action) {
        case 'index':
            $controller->index();
            break;
            
        case 'novo':
            $controller->novo();
            break;
            
        case 'editar':
            $id = $_GET['id'] ?? null;
            if (!$id) {
                header('Location: cupons.php');
                exit;
            }
            $controller->editar($id);
            break;
            
        case 'salvar':
            $controller->salvar();
            break;
            
        case 'toggle-status':
            $controller->toggleStatus();
            break;
            
        case 'deletar':
            $id = $_GET['id'] ?? null;
            if (!$id) {
                header('Location: cupons.php');
                exit;
            }
            $controller->deletar($id);
            break;
            
        default:
            $controller->index();
            break;
    }
} catch (Exception $e) {
    // Log do erro
    error_log("Erro no cupons.php: " . $e->getMessage());
    
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
    
    header('Location: cupons.php');
    exit;
}
?>
