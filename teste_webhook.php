<?php
/**
 * Teste do Webhook - Mini ERP
 * Página para testar o funcionamento do webhook
 */

// Headers para página HTML
header('Content-Type: text/html; charset=utf-8');

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste Webhook - Mini ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-webhook mr-2"></i>Teste do Webhook
                </h1>

                <!-- Informações do Webhook -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-blue-800 mb-2">Endpoint do Webhook:</h3>
                    <code class="text-sm bg-gray-800 text-white px-2 py-1 rounded">
                        POST http://localhost/mini/webhook.php
                    </code>
                </div>

                <!-- Formato JSON Esperado -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-green-800 mb-2">Formato JSON Esperado:</h3>
                    <pre class="text-sm bg-gray-800 text-white p-3 rounded overflow-x-auto"><code>{
  "pedido_id": 1,
  "status": "processando"
}</code></pre>
                </div>

                <!-- Formulário de Teste -->
                <div class="border rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Teste Manual:</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ID do Pedido:</label>
                            <input type="number" id="pedido_id" value="1" min="1" 
                                   class="w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Novo Status:</label>
                            <select id="status" class="w-full border border-gray-300 rounded-md px-3 py-2">
                                <option value="processando">Processando</option>
                                <option value="enviado">Enviado</option>
                                <option value="entregue">Entregue</option>
                                <option value="cancelado">Cancelado</option>
                            </select>
                        </div>

                        <button onclick="testarWebhook()" 
                                class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md transition">
                            <i class="fas fa-play mr-2"></i>Testar Webhook
                        </button>
                    </div>
                </div>

                <!-- Resultado -->
                <div id="resultado" class="hidden">
                    <h3 class="font-semibold text-gray-800 mb-2">Resultado:</h3>
                    <pre id="resultado-conteudo" class="bg-gray-800 text-white p-3 rounded text-sm overflow-x-auto"></pre>
                </div>

                <!-- Status Possíveis -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <h3 class="font-semibold text-yellow-800 mb-2">Status Possíveis:</h3>
                    <ul class="text-sm text-yellow-700 space-y-1">
                        <li><strong>pendente</strong> - Pedido criado, aguardando processamento</li>
                        <li><strong>processando</strong> - Pedido em processamento</li>
                        <li><strong>enviado</strong> - Pedido enviado para entrega</li>
                        <li><strong>entregue</strong> - Pedido entregue</li>
                        <li><strong>cancelado</strong> - Pedido cancelado (será removido)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
    function testarWebhook() {
        const pedidoId = document.getElementById('pedido_id').value;
        const status = document.getElementById('status').value;
        
        const data = {
            pedido_id: parseInt(pedidoId),
            status: status
        };

        fetch('/mini/webhook.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text().then(text => ({
            status: response.status,
            statusText: response.statusText,
            body: text
        })))
        .then(result => {
            document.getElementById('resultado').classList.remove('hidden');
            document.getElementById('resultado-conteudo').textContent = 
                `Status: ${result.status} ${result.statusText}\n\n${result.body}`;
        })
        .catch(error => {
            document.getElementById('resultado').classList.remove('hidden');
            document.getElementById('resultado-conteudo').textContent = 
                `Erro: ${error.message}`;
        });
    }
    </script>
</body>
</html>
