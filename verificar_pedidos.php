<?php
// Verifica se existem pedidos no banco para testar o webhook
require_once 'config/database.php';

try {
    $db = new Database();
    $pdo = $db->connect();
    
    echo "<h1>Verificação de Pedidos - Webhook Test</h1>";
    
    // Lista pedidos existentes
    $stmt = $pdo->query("SELECT id, data, subtotal, total, status, email_cliente FROM pedidos ORDER BY id DESC LIMIT 10");
    $pedidos = $stmt->fetchAll();
    
    if (empty($pedidos)) {
        echo "<div style='color: red; padding: 20px; border: 1px solid red; margin: 10px;'>";
        echo "<h2>⚠️ NENHUM PEDIDO ENCONTRADO</h2>";
        echo "<p>Para testar o webhook, você precisa ter pelo menos um pedido no banco.</p>";
        echo "<p><strong>Como criar um pedido:</strong></p>";
        echo "<ol>";
        echo "<li>Acesse: <a href='index.php'>http://localhost/mini/</a></li>";
        echo "<li>Adicione produtos ao carrinho</li>";
        echo "<li>Finalize uma compra</li>";
        echo "<li>Volte aqui e atualize a página</li>";
        echo "</ol>";
        echo "</div>";
    } else {
        echo "<div style='color: green; padding: 20px; border: 1px solid green; margin: 10px;'>";
        echo "<h2>✅ PEDIDOS ENCONTRADOS</h2>";
        echo "<p>Use qualquer um destes IDs para testar o webhook:</p>";
        echo "</div>";
        
        echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 10px;'>";
        echo "<tr style='background: #f0f0f0;'>";
        echo "<th style='padding: 10px;'>ID</th>";
        echo "<th style='padding: 10px;'>Data</th>";
        echo "<th style='padding: 10px;'>Total</th>";
        echo "<th style='padding: 10px;'>Status Atual</th>";
        echo "<th style='padding: 10px;'>Email</th>";
        echo "<th style='padding: 10px;'>Teste Webhook</th>";
        echo "</tr>";
        
        foreach ($pedidos as $pedido) {
            echo "<tr>";
            echo "<td style='padding: 10px; font-weight: bold;'>" . $pedido['id'] . "</td>";
            echo "<td style='padding: 10px;'>" . date('d/m/Y H:i', strtotime($pedido['data'])) . "</td>";
            echo "<td style='padding: 10px;'>R$ " . number_format($pedido['total'], 2, ',', '.') . "</td>";
            echo "<td style='padding: 10px;'>" . $pedido['status'] . "</td>";
            echo "<td style='padding: 10px;'>" . ($pedido['email_cliente'] ?: 'N/A') . "</td>";
            echo "<td style='padding: 10px;'>";
            echo "<code>{\"pedido_id\": " . $pedido['id'] . ", \"status\": \"processando\"}</code>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<div style='background: #e8f4fd; padding: 15px; margin: 10px; border-left: 4px solid #2196f3;'>";
        echo "<h3>🔧 Como usar no Postman:</h3>";
        echo "<ol>";
        echo "<li><strong>Method:</strong> POST</li>";
        echo "<li><strong>URL:</strong> http://localhost/mini/webhook.php</li>";
        echo "<li><strong>Headers:</strong> Content-Type: application/json</li>";
        echo "<li><strong>Body:</strong> raw → JSON → Cole um dos códigos da tabela acima</li>";
        echo "</ol>";
        echo "</div>";
    }
    
    // Mostra logs recentes se existir
    $logFile = 'logs/webhook.log';
    if (file_exists($logFile)) {
        echo "<h2>📋 Últimos Logs do Webhook</h2>";
        $logs = file_get_contents($logFile);
        $lines = array_slice(explode("\n", $logs), -10); // Últimas 10 linhas
        echo "<pre style='background: #f5f5f5; padding: 10px; overflow: auto; max-height: 300px;'>";
        echo htmlspecialchars(implode("\n", $lines));
        echo "</pre>";
        
        echo "<p><a href='?clear_logs=1'>🗑️ Limpar Logs</a></p>";
        
        if (isset($_GET['clear_logs'])) {
            file_put_contents($logFile, '');
            echo "<p style='color: green;'>✅ Logs limpos!</p>";
            echo "<script>setTimeout(() => window.location.href = 'verificar_pedidos.php', 1000);</script>";
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Erro: " . $e->getMessage() . "</p>";
}
?>
