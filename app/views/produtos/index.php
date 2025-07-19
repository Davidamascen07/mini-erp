<?php $title = 'Gerenciar Produtos'; ?>

<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-cube mr-2"></i>Gerenciar Produtos
            </h1>
            <a href="index.php?action=novo" class="bg-primary hover:bg-blue-700 text-white px-4 py-2 rounded-md transition">
                <i class="fas fa-plus mr-2"></i>Novo Produto
            </a>
        </div>
    </div>

    <div class="p-6">
        <?php if (empty($produtos)): ?>
            <div class="text-center py-12">
                <i class="fas fa-box-open text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-xl text-gray-600 mb-2">Nenhum produto cadastrado</h3>
                <p class="text-gray-500">Comece criando seu primeiro produto!</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($produtos as $produto): ?>
                    <div class="bg-white border rounded-lg shadow hover:shadow-md transition">
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">
                                <?php echo htmlspecialchars($produto['nome']); ?>
                            </h3>
                            <p class="text-2xl font-bold text-primary mb-4">
                                R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                            </p>

                            <?php if (!empty($produto['variacoes'])): ?>
                                <div class="mb-4">
                                    <h4 class="font-medium text-gray-700 mb-2">Variações:</h4>
                                    <div class="space-y-2">
                                        <?php foreach ($produto['variacoes'] as $variacao): ?>
                                            <div class="flex justify-between items-center bg-gray-50 p-2 rounded">
                                                <span class="text-sm text-gray-600">
                                                    <?php echo htmlspecialchars($variacao['nome']); ?>
                                                </span>
                                                <span class="text-sm font-medium <?php echo $variacao['estoque'] > 0 ? 'text-green-600' : 'text-red-600'; ?>">
                                                    Estoque: <?php echo $variacao['estoque']; ?>
                                                </span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <!-- Seção de compra -->
                                <div class="border-t pt-4">
                                    <form onsubmit="comprarProduto(event, <?php echo $produto['id']; ?>)" class="space-y-3">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Variação:</label>
                                            <select name="variacao_id" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                                                <option value="">Selecione uma variação</option>
                                                <?php foreach ($produto['variacoes'] as $variacao): ?>
                                                    <?php if ($variacao['estoque'] > 0): ?>
                                                        <option value="<?php echo $variacao['id']; ?>">
                                                            <?php echo htmlspecialchars($variacao['nome']); ?> (<?php echo $variacao['estoque']; ?> disponível)
                                                        </option>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantidade:</label>
                                            <input type="number" name="quantidade" min="1" max="99" value="1" 
                                                   class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                                        </div>
                                        <button type="submit" class="w-full bg-secondary hover:bg-green-700 text-white py-2 rounded-md transition">
                                            <i class="fas fa-shopping-cart mr-2"></i>Comprar
                                        </button>
                                    </form>
                                </div>
                            <?php else: ?>
                                <p class="text-gray-500 text-sm mb-4">Sem variações cadastradas</p>
                            <?php endif; ?>

                            <!-- Botões de ação -->
                            <div class="flex space-x-2 mt-4 pt-4 border-t">
                                <a href="index.php?action=editar&id=<?php echo $produto['id']; ?>" 
                                   class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded text-center transition">
                                    <i class="fas fa-edit mr-1"></i>Editar
                                </a>
                                <a href="index.php?action=deletar&id=<?php echo $produto['id']; ?>" 
                                   onclick="return confirmDelete('Tem certeza que deseja excluir este produto?')"
                                   class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded text-center transition">
                                    <i class="fas fa-trash mr-1"></i>Excluir
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function comprarProduto(event, produto_id) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    formData.append('produto_id', produto_id);
    
    // Converte FormData para URLSearchParams
    const data = {};
    for (let [key, value] of formData.entries()) {
        data[key] = value;
    }
    
    ajaxRequest('index.php?action=comprar', data, function(response) {
        if (response.success) {
            showMessage(response.message, 'success');
            updateCartCount(response.carrinho_count);
            form.reset();
        } else {
            showMessage(response.message, 'error');
        }
    });
}
</script>
