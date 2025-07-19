<?php
/**
 * Script de teste para verificar a estrutura da tabela cupons
 */

echo "<h2>🔧 Teste da Tabela Cupons</h2>";

try {
    require_once 'config/database.php';
    $database = new Database();
    $connection = $database->connect();
    
    // Verifica se a tabela cupons existe
    $stmt = $connection->prepare("DESCRIBE cupons");
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>📋 Estrutura da Tabela 'cupons':</h3>";
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr style='background-color: #f0f0f0;'><th>Coluna</th><th>Tipo</th><th>Null</th><th>Chave</th><th>Default</th></tr>";
    
    $hasAtivoColumn = false;
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($column['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Default']) . "</td>";
        echo "</tr>";
        
        if ($column['Field'] === 'ativo') {
            $hasAtivoColumn = true;
        }
    }
    echo "</table>";
    
    if ($hasAtivoColumn) {
        echo "<p style='color: green; font-weight: bold;'>✅ Coluna 'ativo' encontrada!</p>";
    } else {
        echo "<p style='color: red; font-weight: bold;'>❌ Coluna 'ativo' NÃO encontrada!</p>";
        echo "<p>🛠️ <strong>Solução:</strong> Execute o script SQL abaixo no phpMyAdmin:</p>";
        echo "<pre style='background-color: #f5f5f5; padding: 10px; border-radius: 5px;'>";
        echo "ALTER TABLE cupons ADD COLUMN ativo BOOLEAN DEFAULT TRUE AFTER valor_minimo;";
        echo "</pre>";
    }
    
    // Testa busca dos cupons
    echo "<h3>📊 Cupons no Banco:</h3>";
    $stmt = $connection->prepare("SELECT * FROM cupons LIMIT 5");
    $stmt->execute();
    $cupons = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($cupons)) {
        echo "<p>Nenhum cupom encontrado.</p>";
    } else {
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr style='background-color: #f0f0f0;'>";
        foreach (array_keys($cupons[0]) as $key) {
            echo "<th>" . htmlspecialchars($key) . "</th>";
        }
        echo "</tr>";
        
        foreach ($cupons as $cupom) {
            echo "<tr>";
            foreach ($cupom as $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erro: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "<hr>";
echo "<h3>🔧 Para resolver o problema:</h3>";
echo "<ol>";
echo "<li>Execute o SQL no phpMyAdmin: <code>ALTER TABLE cupons ADD COLUMN ativo BOOLEAN DEFAULT TRUE;</code></li>";
echo "<li>Ou execute o arquivo <code>database/fix_cupons.sql</code></li>";
echo "<li>Depois acesse <a href='cupons.php'>cupons.php</a> novamente</li>";
echo "</ol>";

echo "<p><a href='cupons.php' style='background-color: #4f46e5; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;'>🔙 Testar Cupons</a></p>";
?>
