<?php
/**
 * Arquivo de configurações gerais do sistema
 */

// Configurações de timezone
date_default_timezone_set('America/Sao_Paulo');

// Configurações de erro (para desenvolvimento)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configurações de sessão
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0); // Mude para 1 em HTTPS
ini_set('session.use_only_cookies', 1);

// Configurações gerais da aplicação
define('APP_NAME', 'Mini ERP');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/mini');

// Configurações de email
define('MAIL_FROM', 'naoresponder@minierp.com');
define('MAIL_FROM_NAME', 'Mini ERP');

// Configurações de debug
define('DEBUG_MODE', true); // Mude para false em produção

// Função para autoload de classes
function autoloadClasses($className) {
    $paths = [
        'app/models/',
        'app/controllers/',
        'config/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
}

spl_autoload_register('autoloadClasses');
?>
