<?php
/**
 * Mini ERP - Gerenciamento do carrinho de compras
 */

session_start();

require_once 'app/controllers/CarrinhoController.php';

$controller = new CarrinhoController();
$action = $_GET['action'] ?? 'index';

try {
    switch ($action) {
        case 'index':
            $controller->index();
            break;
            
        case 'atualizar-quantidade':
            $controller->atualizarQuantidade();
            break;
            
        case 'remover-item':
            $controller->removerItem();
            break;
            
        case 'aplicar-cupom':
            $controller->aplicarCupom();
            break;
            
        case 'remover-cupom':
            $controller->removerCupom();
            break;
            
        case 'finalizar':
            $controller->finalizar();
            break;
            
        case 'processar':
            $controller->processar();
            break;
            
        case 'consultar-cep':
            $controller->consultarCEP();
            break;
            
        default:
            $controller->index();
            break;
    }
} catch (Exception $e) {
    // Log do erro
    error_log("Erro no carrinho.php: " . $e->getMessage());
    
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
    
    header('Location: carrinho.php');
    exit;
}
?>
