<?php $title = 'Gerenciar Cupons'; ?>

<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-tag mr-2"></i>Gerenciar Cupons
            </h1>
            <a href="cupons.php?action=novo" class="bg-primary hover:bg-blue-700 text-white px-4 py-2 rounded-md transition">
                <i class="fas fa-plus mr-2"></i>Novo Cupom
            </a>
        </div>
    </div>

    <div class="p-6">
        <?php if (empty($cupons)): ?>
            <div class="text-center py-12">
                <i class="fas fa-tags text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-xl text-gray-600 mb-2">Nenhum cupom cadastrado</h3>
                <p class="text-gray-500">Comece criando seu primeiro cupom de desconto!</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Código
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Desconto
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Valor Mínimo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Validade
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
                        <?php foreach ($cupons as $cupom): ?>
                            <?php 
                            $hoje = date('Y-m-d');
                            $expirado = $cupom['validade'] < $hoje;
                            $ativo = isset($cupom['ativo']) ? $cupom['ativo'] : 1; // Define como ativo por padrão
                            ?>
                            <tr class="<?php echo $expirado ? 'bg-red-50' : ''; ?>">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary text-white">
                                            <?php echo htmlspecialchars($cupom['codigo']); ?>
                                        </span>
                                        <?php if ($expirado): ?>
                                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Expirado
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo number_format($cupom['desconto_percentual'], 1); ?>%
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    R$ <?php echo number_format($cupom['valor_minimo'], 2, ',', '.'); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo date('d/m/Y', strtotime($cupom['validade'])); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button onclick="toggleStatus(<?php echo $cupom['id']; ?>, <?php echo $ativo ? 0 : 1; ?>)"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $ativo ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200'; ?> cursor-pointer transition">
                                        <?php echo $ativo ? 'Ativo' : 'Inativo'; ?>
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="cupons.php?action=editar&id=<?php echo $cupom['id']; ?>"
                                       class="text-blue-600 hover:text-blue-900 transition">
                                        <i class="fas fa-edit mr-1"></i>Editar
                                    </a>
                                    <a href="cupons.php?action=deletar&id=<?php echo $cupom['id']; ?>"
                                       onclick="return confirmDelete('Tem certeza que deseja excluir este cupom?')"
                                       class="text-red-600 hover:text-red-900 transition">
                                        <i class="fas fa-trash mr-1"></i>Excluir
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function toggleStatus(id, ativo) {
    ajaxRequest('cupons.php?action=toggle-status', {
        id: id,
        ativo: ativo
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
