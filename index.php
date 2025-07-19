<?php
/**
 * Mini ERP - Ponto de entrada principal
 * Gerencia produtos e suas funcionalidades
 */

session_start();

require_once 'app/controllers/ProdutoController.php';

$controller = new ProdutoController();
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
                header('Location: index.php');
                exit;
            }
            $controller->editar($id);
            break;
            
        case 'salvar':
            $controller->salvar();
            break;
            
        case 'comprar':
            $controller->comprar();
            break;
            
        case 'deletar':
            $id = $_GET['id'] ?? null;
            if (!$id) {
                header('Location: index.php');
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
    error_log("Erro no index.php: " . $e->getMessage());
    
    // Redireciona com mensagem de erro
    session_start();
    $_SESSION['flash'] = [
        'type' => 'error',
        'message' => 'Ocorreu um erro inesperado. Tente novamente.'
    ];
    
    header('Location: index.php');
    exit;
}
?>
