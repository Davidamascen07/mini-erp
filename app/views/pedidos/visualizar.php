<?php $title = 'Pedido #' . $pedido['id']; ?>

<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-receipt mr-2"></i>Pedido #<?php echo $pedido['id']; ?>
            </h1>
            <div class="flex items-center space-x-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                           <?php 
                           switch($pedido['status']) {
                               case 'pendente':
                                   echo 'bg-yellow-100 text-yellow-800';
                                   break;
                               case 'processando':
                                   echo 'bg-blue-100 text-blue-800';
                                   break;
                               case 'enviado':
                                   echo 'bg-purple-100 text-purple-800';
                                   break;
                               case 'entregue':
                                   echo 'bg-green-100 text-green-800';
                                   break;
                               case 'cancelado':
                                   echo 'bg-red-100 text-red-800';
                                   break;
                               default:
                                   echo 'bg-gray-100 text-gray-800';
                           }
                           ?>">
                    <?php echo ucfirst($pedido['status']); ?>
                </span>
                <a href="pedidos.php" class="text-gray-600 hover:text-gray-800 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Voltar
                </a>
            </div>
        </div>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informações do Pedido -->
            <div class="lg:col-span-2">
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Informações do Pedido</h2>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Data do Pedido</label>
                                <p class="text-sm text-gray-900">
                                    <?php echo date('d/m/Y H:i:s', strtotime($pedido['data'])); ?>
                                </p>
                            </div>
                            <?php if ($pedido['email_cliente']): ?>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">E-mail do Cliente</label>
                                    <p class="text-sm text-gray-900">
                                        <?php echo htmlspecialchars($pedido['email_cliente']); ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                            <?php if ($pedido['cupom_codigo']): ?>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Cupom Utilizado</label>
                                    <p class="text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <?php echo htmlspecialchars($pedido['cupom_codigo']); ?>
                                        </span>
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Itens do Pedido -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Itens do Pedido</h2>
                    <div class="bg-white border border-gray-200 rounded-md overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Produto
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Quantidade
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Preço Unit.
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Subtotal
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($pedido['itens'] as $item): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?php echo htmlspecialchars($item['produto_nome']); ?>
                                            </div>
                                            <?php if ($item['nome_variacao']): ?>
                                                <div class="text-sm text-gray-500">
                                                    Variação: <?php echo htmlspecialchars($item['nome_variacao']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo $item['quantidade']; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            R$ <?php echo number_format($item['preco_unitario'], 2, ',', '.'); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            R$ <?php echo number_format($item['preco_unitario'] * $item['quantidade'], 2, ',', '.'); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Endereço de Entrega -->
                <?php if ($pedido['endereco']): ?>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Endereço de Entrega</h2>
                        <div class="bg-gray-50 p-4 rounded-md">
                            <?php $endereco = $pedido['endereco']; ?>
                            <p class="text-sm text-gray-900">
                                <?php echo htmlspecialchars($endereco['rua']); ?>, <?php echo htmlspecialchars($endereco['numero']); ?>
                                <?php if ($endereco['complemento']): ?>
                                    <br><?php echo htmlspecialchars($endereco['complemento']); ?>
                                <?php endif; ?>
                                <br><?php echo htmlspecialchars($endereco['bairro']); ?>
                                <br><?php echo htmlspecialchars($endereco['cidade']); ?> - <?php echo htmlspecialchars($endereco['estado']); ?>
                                <br>CEP: <?php echo htmlspecialchars($endereco['cep']); ?>
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Resumo Financeiro -->
            <div class="lg:col-span-1">
                <div class="bg-gray-50 p-6 rounded-md sticky top-4">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Resumo Financeiro</h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="text-gray-900">R$ <?php echo number_format($pedido['subtotal'], 2, ',', '.'); ?></span>
                        </div>
                        
                        <?php if ($pedido['desconto'] > 0): ?>
                            <div class="flex justify-between text-green-600">
                                <span>Desconto:</span>
                                <span>- R$ <?php echo number_format($pedido['desconto'], 2, ',', '.'); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Frete:</span>
                            <span class="text-gray-900">
                                <?php echo $pedido['frete'] == 0 ? 'Grátis' : 'R$ ' . number_format($pedido['frete'], 2, ',', '.'); ?>
                            </span>
                        </div>
                        
                        <hr class="border-gray-300">
                        
                        <div class="flex justify-between font-semibold text-lg">
                            <span>Total:</span>
                            <span>R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></span>
                        </div>
                    </div>

                    <!-- Ações -->
                    <div class="mt-6 space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Atualizar Status</label>
                            <select onchange="atualizarStatus(<?php echo $pedido['id']; ?>, this.value)"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="pendente" <?php echo $pedido['status'] === 'pendente' ? 'selected' : ''; ?>>Pendente</option>
                                <option value="processando" <?php echo $pedido['status'] === 'processando' ? 'selected' : ''; ?>>Processando</option>
                                <option value="enviado" <?php echo $pedido['status'] === 'enviado' ? 'selected' : ''; ?>>Enviado</option>
                                <option value="entregue" <?php echo $pedido['status'] === 'entregue' ? 'selected' : ''; ?>>Entregue</option>
                                <option value="cancelado" <?php echo $pedido['status'] === 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                            </select>
                        </div>

                        <button onclick="window.print()" class="w-full bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded transition">
                            <i class="fas fa-print mr-2"></i>Imprimir Pedido
                        </button>
                    </div>

                    <!-- Webhook Info -->
                    <div class="mt-6 p-3 bg-blue-50 rounded border border-blue-200">
                        <h4 class="text-sm font-medium text-blue-800 mb-2">Webhook</h4>
                        <p class="text-xs text-blue-600">
                            Para atualizar este pedido via webhook, use:<br>
                            <code class="bg-blue-100 px-1 rounded">POST /webhook.php</code><br>
                            <code class="bg-blue-100 px-1 rounded">{"pedido_id": <?php echo $pedido['id']; ?>, "status": "novo_status"}</code>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function atualizarStatus(id, status) {
    ajaxRequest('pedidos.php?action=atualizar-status', {
        id: id,
        status: status
    }, function(response) {
        if (response.success) {
            showMessage(response.message, 'success');
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showMessage(response.message, 'error');
        }
    });
}

// Estilos para impressão
const printStyle = `
    @media print {
        .no-print { display: none !important; }
        body { font-size: 12px; }
        .bg-gray-50 { background-color: #f9fafb !important; }
        .bg-gray-100 { background-color: #f3f4f6 !important; }
    }
`;

const style = document.createElement('style');
style.textContent = printStyle;
document.head.appendChild(style);
</script>
