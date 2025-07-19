<?php
/**
 * Classe base para todos os controllers
 */
abstract class BaseController {
    
    /**
     * Renderiza uma view
     */
    protected function render($view, $data = []) {
        // Extrai variáveis do array $data
        extract($data);
        
        // Inclui o header
        include_once 'app/views/layouts/header.php';
        
        // Inclui a view específica
        include_once "app/views/{$view}.php";
        
        // Inclui o footer
        include_once 'app/views/layouts/footer.php';
    }

    /**
     * Redireciona para uma URL
     */
    protected function redirect($url) {
        header("Location: {$url}");
        exit;
    }

    /**
     * Retorna JSON
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Valida se é uma requisição POST
     */
    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Valida se é uma requisição GET
     */
    protected function isGet() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    /**
     * Limpa e valida input
     */
    protected function sanitize($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Define mensagem flash
     */
    protected function setFlash($type, $message) {
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }

    /**
     * Obtém e limpa mensagem flash
     */
    protected function getFlash() {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        
        return null;
    }
}
?>
