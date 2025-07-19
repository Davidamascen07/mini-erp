<?php
/**
 * Arquivo de teste para verificar o sistema
 */

echo "<h1>Mini ERP - Teste de Instalação</h1>";
echo "<p>PHP Version: " . PHP_VERSION . "</p>";

// Teste de conexão com banco
try {
    require_once 'config/database.php';
    $database = new Database();
    $connection = $database->connect();
    
    if ($connection) {
        echo "<p style='color: green;'>✅ Conexão com banco de dados: OK</p>";
        
        // Testa se as tabelas existem
        $tables = ['produtos', 'variacoes', 'estoques', 'cupons', 'pedidos', 'itens_pedido', 'enderecos'];
        $tablesExist = 0;
        
        foreach ($tables as $table) {
            $stmt = $connection->prepare("SHOW TABLES LIKE '$table'");
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $tablesExist++;
                echo "<p style='color: green;'>✅ Tabela '$table': OK</p>";
            } else {
                echo "<p style='color: red;'>❌ Tabela '$table': NÃO ENCONTRADA</p>";
            }
        }
        
        if ($tablesExist === count($tables)) {
            echo "<p style='color: green; font-weight: bold;'>🎉 Sistema pronto para uso!</p>";
            echo "<p><a href='index.php' style='background-color: #4f46e5; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Acessar Sistema</a></p>";
        } else {
            echo "<p style='color: orange; font-weight: bold;'>⚠️ Execute o script database/schema.sql no phpMyAdmin</p>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ Falha na conexão com banco de dados</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erro de conexão: " . $e->getMessage() . "</p>";
}

// Verifica permissões de escrita
$logsDir = 'logs/';
if (is_writable($logsDir)) {
    echo "<p style='color: green;'>✅ Diretório de logs: OK</p>";
} else {
    echo "<p style='color: orange;'>⚠️ Diretório de logs sem permissão de escrita</p>";
}

// Verifica extensões PHP necessárias
$extensions = ['pdo', 'pdo_mysql', 'curl', 'openssl'];
foreach ($extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<p style='color: green;'>✅ Extensão PHP '$ext': OK</p>";
    } else {
        echo "<p style='color: red;'>❌ Extensão PHP '$ext': NÃO ENCONTRADA</p>";
    }
}

echo "<hr>";
echo "<p><strong>Para começar a usar:</strong></p>";
echo "<ol>";
echo "<li>Acesse o phpMyAdmin (http://localhost/phpmyadmin)</li>";
echo "<li>Execute o script <code>database/schema.sql</code></li>";
echo "<li>Acesse <a href='index.php'>index.php</a> para começar a usar o sistema</li>";
echo "</ol>";

echo "<hr>";
echo "<p><strong>Funcionalidades disponíveis:</strong></p>";
echo "<ul>";
echo "<li><a href='index.php'>Produtos</a> - Cadastro e gestão de produtos</li>";
echo "<li><a href='carrinho.php'>Carrinho</a> - Carrinho de compras</li>";
echo "<li><a href='cupons.php'>Cupons</a> - Gestão de cupons de desconto</li>";
echo "<li><a href='pedidos.php'>Pedidos</a> - Visualização de pedidos</li>";
echo "<li><a href='webhook.php'>Webhook</a> - API para atualização de pedidos</li>";
echo "</ul>";
?>

<style>
body {
    font-family: Arial, sans-serif;
    margin: 20px;
    background-color: #f5f5f5;
}
h1 {
    color: #4f46e5;
}
p {
    margin: 5px 0;
}
</style>
