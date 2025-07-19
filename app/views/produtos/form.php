<?php $title = ($produto ? 'Editar' : 'Novo') . ' Produto'; ?>

<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-cube mr-2"></i><?php echo $produto ? 'Editar' : 'Novo'; ?> Produto
            </h1>
            <a href="index.php" class="text-gray-600 hover:text-gray-800 transition">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </div>

    <form method="POST" action="index.php?action=salvar" class="p-6">
        <?php if ($produto): ?>
            <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
        <?php endif; ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">Nome do Produto *</label>
                <input type="text" id="nome" name="nome" required
                       value="<?php echo $produto ? htmlspecialchars($produto['nome']) : ''; ?>"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            
            <div>
                <label for="preco" class="block text-sm font-medium text-gray-700 mb-2">Preço *</label>
                <div class="relative">
                    <span class="absolute left-3 top-2 text-gray-500">R$</span>
                    <input type="number" id="preco" name="preco" step="0.01" min="0" required
                           value="<?php echo $produto ? $produto['preco'] : ''; ?>"
                           class="w-full border border-gray-300 rounded-md pl-10 pr-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
            </div>
        </div>

        <div class="mb-6">
            <div class="flex justify-between items-center mb-4">
                <label class="block text-sm font-medium text-gray-700">Variações e Estoque</label>
                <button type="button" onclick="adicionarVariacao()" class="bg-secondary hover:bg-green-700 text-white px-3 py-1 rounded text-sm transition">
                    <i class="fas fa-plus mr-1"></i>Adicionar Variação
                </button>
            </div>
            
            <div id="variacoes-container">
                <?php if ($produto && !empty($produto['variacoes'])): ?>
                    <?php foreach ($produto['variacoes'] as $index => $variacao): ?>
                        <div class="variacao-item bg-gray-50 p-4 rounded-md mb-3">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome da Variação</label>
                                    <input type="text" name="variacoes[<?php echo $index; ?>][nome]" 
                                           value="<?php echo htmlspecialchars($variacao['nome']); ?>"
                                           class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="Ex: P, M, G">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantidade em Estoque</label>
                                    <input type="number" name="variacoes[<?php echo $index; ?>][estoque]" min="0" 
                                           value="<?php echo $variacao['estoque']; ?>"
                                           class="w-full border border-gray-300 rounded-md px-3 py-2">
                                </div>
                                <div class="flex items-end">
                                    <button type="button" onclick="removerVariacao(this)" 
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded transition">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="variacao-item bg-gray-50 p-4 rounded-md mb-3">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nome da Variação</label>
                                <input type="text" name="variacoes[0][nome]" 
                                       class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="Ex: P, M, G">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Quantidade em Estoque</label>
                                <input type="number" name="variacoes[0][estoque]" min="0" 
                                       class="w-full border border-gray-300 rounded-md px-3 py-2" value="0">
                            </div>
                            <div class="flex items-end">
                                <button type="button" onclick="removerVariacao(this)" 
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded transition">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="flex space-x-4">
            <button type="submit" class="bg-primary hover:bg-blue-700 text-white px-6 py-2 rounded-md transition">
                <i class="fas fa-save mr-2"></i><?php echo $produto ? 'Atualizar' : 'Salvar'; ?> Produto
            </button>
            <a href="index.php" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md transition">
                <i class="fas fa-times mr-2"></i>Cancelar
            </a>
        </div>
    </form>
</div>

<script>
let variacaoCounter = <?php echo $produto && !empty($produto['variacoes']) ? count($produto['variacoes']) : 1; ?>;

function adicionarVariacao() {
    const container = document.getElementById('variacoes-container');
    const novaVariacao = document.createElement('div');
    novaVariacao.className = 'variacao-item bg-gray-50 p-4 rounded-md mb-3';
    novaVariacao.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome da Variação</label>
                <input type="text" name="variacoes[${variacaoCounter}][nome]" 
                       class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="Ex: P, M, G">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Quantidade em Estoque</label>
                <input type="number" name="variacoes[${variacaoCounter}][estoque]" min="0" 
                       class="w-full border border-gray-300 rounded-md px-3 py-2" value="0">
            </div>
            <div class="flex items-end">
                <button type="button" onclick="removerVariacao(this)" 
                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded transition">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.appendChild(novaVariacao);
    variacaoCounter++;
}

function removerVariacao(button) {
    const container = document.getElementById('variacoes-container');
    if (container.children.length > 1) {
        button.closest('.variacao-item').remove();
    } else {
        alert('Deve haver pelo menos uma variação!');
    }
}
</script>
