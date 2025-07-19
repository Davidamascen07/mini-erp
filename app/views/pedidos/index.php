<?php $title = 'Pedidos'; ?>

<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <h1 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-shopping-cart mr-2"></i>Pedidos
        </h1>
    </div>

    <div class="p-6">
        <?php if (empty($pedidos)): ?>
            <div class="text-center py-12">
                <i class="fas fa-shopping-cart text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-xl text-gray-600 mb-2">Nenhum pedido encontrado</h3>
                <p class="text-gray-500 mb-6">Os pedidos aparecerão aqui quando forem realizados</p>
                <a href="index.php" class="bg-primary hover:bg-blue-700 text-white px-6 py-3 rounded-md transition">
                    <i class="fas fa-shopping-bag mr-2"></i>Fazer Compras
                </a>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pedido
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Data
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Itens
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($pedidos as $pedido): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        #<?php echo $pedido['id']; ?>
                                    </div>
                                    <?php if ($pedido['email_cliente']): ?>
                                        <div class="text-sm text-gray-500">
                                            <?php echo htmlspecialchars($pedido['email_cliente']); ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo date('d/m/Y H:i', strtotime($pedido['data'])); ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        <?php echo $pedido['total_itens']; ?> item(s)
                                    </div>
                                    <?php if ($pedido['itens']): ?>
                                        <div class="text-sm text-gray-500 max-w-xs truncate" title="<?php echo htmlspecialchars($pedido['itens']); ?>">
                                            <?php echo htmlspecialchars($pedido['itens']); ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Subtotal: R$ <?php echo number_format($pedido['subtotal'], 2, ',', '.'); ?>
                                        <?php if ($pedido['desconto'] > 0): ?>
                                            <br>Desconto: R$ <?php echo number_format($pedido['desconto'], 2, ',', '.'); ?>
                                        <?php endif; ?>
                                        <br>Frete: <?php echo $pedido['frete'] == 0 ? 'Grátis' : 'R$ ' . number_format($pedido['frete'], 2, ',', '.'); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <select onchange="atualizarStatus(<?php echo $pedido['id']; ?>, this.value)"
                                            class="text-sm border-0 rounded-full px-3 py-1 font-medium cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary 
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
                                        <option value="pendente" <?php echo $pedido['status'] === 'pendente' ? 'selected' : ''; ?>>Pendente</option>
                                        <option value="processando" <?php echo $pedido['status'] === 'processando' ? 'selected' : ''; ?>>Processando</option>
                                        <option value="enviado" <?php echo $pedido['status'] === 'enviado' ? 'selected' : ''; ?>>Enviado</option>
                                        <option value="entregue" <?php echo $pedido['status'] === 'entregue' ? 'selected' : ''; ?>>Entregue</option>
                                        <option value="cancelado" <?php echo $pedido['status'] === 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                                    </select>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="pedidos.php?action=visualizar&id=<?php echo $pedido['id']; ?>"
                                       class="text-blue-600 hover:text-blue-900 transition">
                                        <i class="fas fa-eye mr-1"></i>Ver Detalhes
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Estatísticas dos Pedidos -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-4">
                <?php
                $stats = [
                    'total' => count($pedidos),
                    'pendente' => 0,
                    'entregue' => 0,
                    'receita' => 0
                ];
                
                foreach ($pedidos as $pedido) {
                    if ($pedido['status'] === 'pendente') $stats['pendente']++;
                    if ($pedido['status'] === 'entregue') $stats['entregue']++;
                    if ($pedido['status'] !== 'cancelado') $stats['receita'] += $pedido['total'];
                }
                ?>
                
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600"><?php echo $stats['total']; ?></div>
                    <div class="text-sm text-blue-800">Total de Pedidos</div>
                </div>
                
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-yellow-600"><?php echo $stats['pendente']; ?></div>
                    <div class="text-sm text-yellow-800">Pendentes</div>
                </div>
                
                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-green-600"><?php echo $stats['entregue']; ?></div>
                    <div class="text-sm text-green-800">Entregues</div>
                </div>
                
                <div class="bg-purple-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600">R$ <?php echo number_format($stats['receita'], 2, ',', '.'); ?></div>
                    <div class="text-sm text-purple-800">Receita Total</div>
                </div>
            </div>
        <?php endif; ?>
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
            location.reload();
        } else {
            showMessage(response.message, 'error');
        }
    });
}
</script>
