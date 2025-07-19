<?php $title = 'Carrinho de Compras'; ?>

<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <h1 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-shopping-bag mr-2"></i>Carrinho de Compras
        </h1>
    </div>

    <div class="p-6">
        <?php if (empty($itens)): ?>
            <div class="text-center py-12">
                <i class="fas fa-shopping-bag text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-xl text-gray-600 mb-2">Seu carrinho está vazio</h3>
                <p class="text-gray-500 mb-6">Adicione alguns produtos para continuar</p>
                <a href="index.php" class="bg-primary hover:bg-blue-700 text-white px-6 py-3 rounded-md transition">
                    <i class="fas fa-shopping-cart mr-2"></i>Continuar Comprando
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Lista de Itens -->
                <div class="lg:col-span-2">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Itens do Carrinho</h2>
                    <div class="space-y-4">
                        <?php foreach ($itens as $key => $item): ?>
                            <?php 
                            list($produto_id, $variacao_id) = explode('_', $key);
                            $subtotal_item = $item['preco'] * $item['quantidade'];
                            ?>
                            <div class="bg-gray-50 p-4 rounded-md" id="item-<?php echo $key; ?>">
                                <div class="flex justify-between items-center">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-800">
                                            <?php echo htmlspecialchars($item['nome_produto']); ?>
                                        </h3>
                                        <p class="text-gray-600">
                                            Variação: <?php echo htmlspecialchars($item['nome_variacao']); ?>
                                        </p>
                                        <p class="text-primary font-semibold">
                                            R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?> cada
                                        </p>
                                    </div>
                                    
                                    <div class="flex items-center space-x-4">
                                        <div class="flex items-center space-x-2">
                                            <label class="text-sm text-gray-600">Qtd:</label>
                                            <input type="number" min="1" max="99" 
                                                   value="<?php echo $item['quantidade']; ?>"
                                                   class="w-16 border border-gray-300 rounded px-2 py-1 text-center"
                                                   onchange="atualizarQuantidade('<?php echo $produto_id; ?>', '<?php echo $variacao_id; ?>', this.value)">
                                        </div>
                                        
                                        <div class="text-right">
                                            <p class="font-semibold text-gray-800">
                                                R$ <?php echo number_format($subtotal_item, 2, ',', '.'); ?>
                                            </p>
                                        </div>
                                        
                                        <button onclick="removerItem('<?php echo $produto_id; ?>', '<?php echo $variacao_id; ?>')"
                                                class="text-red-500 hover:text-red-700 p-2">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Resumo do Pedido -->
                <div class="lg:col-span-1">
                    <div class="bg-gray-50 p-6 rounded-md sticky top-4">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Resumo do Pedido</h2>
                        
                        <div class="space-y-3 mb-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal:</span>
                                <span id="subtotal-display">R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></span>
                            </div>
                            
                            <?php if ($cupom): ?>
                                <div class="flex justify-between text-green-600">
                                    <span>Desconto (<?php echo htmlspecialchars($cupom['codigo']); ?>):</span>
                                    <span>- R$ <?php echo number_format($desconto, 2, ',', '.'); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">Frete:</span>
                                <span><?php echo $frete == 0 ? 'Grátis' : 'R$ ' . number_format($frete, 2, ',', '.'); ?></span>
                            </div>
                            
                            <hr class="border-gray-300">
                            
                            <div class="flex justify-between font-semibold text-lg">
                                <span>Total:</span>
                                <span id="total-display">R$ <?php echo number_format($total, 2, ',', '.'); ?></span>
                            </div>
                        </div>

                        <!-- Cupom de Desconto -->
                        <?php if (!$cupom): ?>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cupom de Desconto</label>
                                <div class="flex space-x-2">
                                    <input type="text" id="cupom-input" placeholder="Digite o código"
                                           class="flex-1 border border-gray-300 rounded-md px-3 py-2">
                                    <button onclick="aplicarCupom()" class="bg-secondary hover:bg-green-700 text-white px-4 py-2 rounded transition">
                                        Aplicar
                                    </button>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="mb-4 bg-green-50 p-3 rounded border border-green-200">
                                <div class="flex justify-between items-center">
                                    <span class="text-green-800">Cupom: <strong><?php echo htmlspecialchars($cupom['codigo']); ?></strong></span>
                                    <button onclick="removerCupom()" class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>

                        <a href="carrinho.php?action=finalizar" 
                           class="w-full bg-primary hover:bg-blue-700 text-white py-3 px-4 rounded-md text-center block transition">
                            <i class="fas fa-credit-card mr-2"></i>Finalizar Compra
                        </a>
                        
                        <a href="index.php" class="w-full text-center text-gray-600 hover:text-gray-800 py-2 block transition">
                            <i class="fas fa-arrow-left mr-2"></i>Continuar Comprando
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function atualizarQuantidade(produto_id, variacao_id, quantidade) {
    if (quantidade < 1) return;
    
    ajaxRequest('carrinho.php?action=atualizar-quantidade', {
        produto_id: produto_id,
        variacao_id: variacao_id,
        quantidade: quantidade
    }, function(response) {
        if (response.success) {
            // Mostra mensagem se cupom foi removido
            if (response.message) {
                showMessage(response.message, 'warning');
            }
            
            document.getElementById('subtotal-display').textContent = 'R$ ' + response.subtotal;
            updateCartCount(response.count);
            location.reload(); // Recarrega para recalcular frete e total
        }
    });
}

function removerItem(produto_id, variacao_id) {
    if (!confirm('Tem certeza que deseja remover este item?')) return;
    
    ajaxRequest('carrinho.php?action=remover-item', {
        produto_id: produto_id,
        variacao_id: variacao_id
    }, function(response) {
        if (response.success) {
            // Determina o tipo de mensagem baseado no conteúdo
            const messageType = response.message.includes('Cupom removido') ? 'warning' : 'success';
            showMessage(response.message, messageType);
            location.reload();
        }
    });
}

function aplicarCupom() {
    const cupom = document.getElementById('cupom-input').value.trim();
    if (!cupom) return;
    
    ajaxRequest('carrinho.php?action=aplicar-cupom', {
        cupom: cupom
    }, function(response) {
        if (response.success) {
            showMessage(response.message, 'success');
            location.reload();
        } else {
            showMessage(response.message, 'error');
        }
    });
}

function removerCupom() {
    ajaxRequest('carrinho.php?action=remover-cupom', {}, function(response) {
        if (response.success) {
            showMessage(response.message, 'success');
            location.reload();
        }
    });
}
</script>
