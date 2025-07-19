<?php
// Debug: Verificar estrutura da tabela pedidos
require_once 'config/database.php';

try {
    $db = new Database();
    $pdo = $db->connect();
    
    echo "<h1>Debug - Tabela Pedidos</h1>";
    
    // Verificar estrutura da tabela
    $stmt = $pdo->query("DESCRIBE pedidos");
    $columns = $stmt->fetchAll();
    
    echo "<h2>Colunas na tabela 'pedidos':</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Chave</th><th>Padrão</th></tr>";
    
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . $column['Field'] . "</td>";
        echo "<td>" . $column['Type'] . "</td>";
        echo "<td>" . $column['Null'] . "</td>";
        echo "<td>" . $column['Key'] . "</td>";
        echo "<td>" . ($column['Default'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Verificar colunas necessárias
    $requiredColumns = ['cupom_codigo', 'email_cliente'];
    $existingColumns = array_column($columns, 'Field');
    
    echo "<h2>Status das colunas necessárias:</h2>";
    
    foreach ($requiredColumns as $requiredColumn) {
        if (in_array($requiredColumn, $existingColumns)) {
            echo "<p style='color: green'>✓ Coluna '$requiredColumn' existe na tabela</p>";
        } else {
            echo "<p style='color: red'>✗ Coluna '$requiredColumn' NÃO existe na tabela</p>";
        }
    }
    
    $missingColumns = array_diff($requiredColumns, $existingColumns);
    if (!empty($missingColumns)) {
        echo "<h3>SQL para adicionar as colunas faltantes:</h3>";
        echo "<pre>";
        foreach ($missingColumns as $column) {
            if ($column == 'cupom_codigo') {
                echo "ALTER TABLE pedidos ADD COLUMN cupom_codigo VARCHAR(50) NULL;\n";
            } elseif ($column == 'email_cliente') {
                echo "ALTER TABLE pedidos ADD COLUMN email_cliente VARCHAR(255) NULL;\n";
            }
        }
        echo "</pre>";
    } else {
        echo "<p style='color: green; font-weight: bold'>✓ Todas as colunas necessárias estão presentes!</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red'>Erro: " . $e->getMessage() . "</p>";
}
?>
