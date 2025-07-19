<?php $title = 'Finalizar Compra'; ?>

<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-credit-card mr-2"></i>Finalizar Compra
            </h1>
            <a href="carrinho.php" class="text-gray-600 hover:text-gray-800 transition">
                <i class="fas fa-arrow-left mr-2"></i>Voltar ao Carrinho
            </a>
        </div>
    </div>

    <form method="POST" action="carrinho.php?action=processar" class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Dados do Cliente -->
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Dados do Cliente</h2>
                
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">E-mail *</label>
                    <input type="email" id="email" name="email" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                </div>

                <h3 class="text-md font-semibold text-gray-800 mb-3 mt-6">Endereço de Entrega</h3>
                
                <div class="mb-4">
                    <label for="cep" class="block text-sm font-medium text-gray-700 mb-2">CEP *</label>
                    <div class="flex space-x-2">
                        <input type="text" id="cep" name="cep" required maxlength="8"
                               class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                               placeholder="00000000">
                        <button type="button" onclick="consultarCEP()" class="bg-secondary hover:bg-green-700 text-white px-4 py-2 rounded transition">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <small class="text-gray-500">Digite apenas números</small>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div class="md:col-span-2">
                        <label for="rua" class="block text-sm font-medium text-gray-700 mb-2">Logradouro *</label>
                        <input type="text" id="rua" name="rua" required
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label for="numero" class="block text-sm font-medium text-gray-700 mb-2">Número *</label>
                        <input type="text" id="numero" name="numero" required
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="complemento" class="block text-sm font-medium text-gray-700 mb-2">Complemento</label>
                    <input type="text" id="complemento" name="complemento"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="bairro" class="block text-sm font-medium text-gray-700 mb-2">Bairro *</label>
                        <input type="text" id="bairro" name="bairro" required
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label for="cidade" class="block text-sm font-medium text-gray-700 mb-2">Cidade *</label>
                        <input type="text" id="cidade" name="cidade" required
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">Estado *</label>
                    <select id="estado" name="estado" required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">Selecione o estado</option>
                        <option value="AC">Acre</option>
                        <option value="AL">Alagoas</option>
                        <option value="AP">Amapá</option>
                        <option value="AM">Amazonas</option>
                        <option value="BA">Bahia</option>
                        <option value="CE">Ceará</option>
                        <option value="DF">Distrito Federal</option>
                        <option value="ES">Espírito Santo</option>
                        <option value="GO">Goiás</option>
                        <option value="MA">Maranhão</option>
                        <option value="MT">Mato Grosso</option>
                        <option value="MS">Mato Grosso do Sul</option>
                        <option value="MG">Minas Gerais</option>
                        <option value="PA">Pará</option>
                        <option value="PB">Paraíba</option>
                        <option value="PR">Paraná</option>
                        <option value="PE">Pernambuco</option>
                        <option value="PI">Piauí</option>
                        <option value="RJ">Rio de Janeiro</option>
                        <option value="RN">Rio Grande do Norte</option>
                        <option value="RS">Rio Grande do Sul</option>
                        <option value="RO">Rondônia</option>
                        <option value="RR">Roraima</option>
                        <option value="SC">Santa Catarina</option>
                        <option value="SP">São Paulo</option>
                        <option value="SE">Sergipe</option>
                        <option value="TO">Tocantins</option>
                    </select>
                </div>
            </div>

            <!-- Resumo do Pedido -->
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Resumo do Pedido</h2>
                
                <div class="bg-gray-50 p-6 rounded-md">
                    <?php
                    require_once 'app/models/Carrinho.php';
                    require_once 'app/models/Pedido.php';
                    
                    $itens = Carrinho::getItens();
                    $subtotal = Carrinho::calcularSubtotal();
                    $cupom = Carrinho::getCupom();
                    $desconto = $cupom ? $cupom['desconto'] : 0;
                    $pedidoModel = new Pedido();
                    $frete = $pedidoModel->calcularFrete($subtotal);
                    $total = $subtotal - $desconto + $frete;
                    ?>
                    
                    <!-- Lista de Itens -->
                    <div class="space-y-2 mb-4">
                        <?php foreach ($itens as $item): ?>
                            <div class="flex justify-between text-sm">
                                <span><?php echo htmlspecialchars($item['nome_produto']); ?> (<?php echo htmlspecialchars($item['nome_variacao']); ?>) x<?php echo $item['quantidade']; ?></span>
                                <span>R$ <?php echo number_format($item['preco'] * $item['quantidade'], 2, ',', '.'); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <hr class="border-gray-300 mb-4">
                    
                    <!-- Totais -->
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>Subtotal:</span>
                            <span>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></span>
                        </div>
                        
                        <?php if ($desconto > 0): ?>
                            <div class="flex justify-between text-green-600">
                                <span>Desconto:</span>
                                <span>- R$ <?php echo number_format($desconto, 2, ',', '.'); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="flex justify-between">
                            <span>Frete:</span>
                            <span><?php echo $frete == 0 ? 'Grátis' : 'R$ ' . number_format($frete, 2, ',', '.'); ?></span>
                        </div>
                        
                        <hr class="border-gray-300">
                        
                        <div class="flex justify-between font-semibold text-lg">
                            <span>Total:</span>
                            <span>R$ <?php echo number_format($total, 2, ',', '.'); ?></span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="w-full bg-primary hover:bg-blue-700 text-white py-3 px-4 rounded-md transition">
                            <i class="fas fa-check mr-2"></i>Confirmar Pedido
                        </button>
                    </div>

                    <div class="mt-4 text-center">
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-lock mr-1"></i>Compra segura e protegida
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function consultarCEP() {
    const cep = document.getElementById('cep').value.replace(/\D/g, '');
    
    if (cep.length !== 8) {
        alert('CEP deve ter 8 dígitos');
        return;
    }
    
    // Bloqueia o botão durante a consulta
    const btn = event.target;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    
    ajaxRequest('carrinho.php?action=consultar-cep', {cep: cep}, function(response) {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-search"></i>';
        
        if (response.success) {
            const endereco = response.endereco;
            document.getElementById('rua').value = endereco.logradouro;
            document.getElementById('bairro').value = endereco.bairro;
            document.getElementById('cidade').value = endereco.localidade;
            document.getElementById('estado').value = endereco.uf;
            
            showMessage('CEP encontrado!', 'success');
        } else {
            showMessage(response.message, 'error');
        }
    });
}

// Máscara para CEP
document.getElementById('cep').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length <= 8) {
        e.target.value = value;
    }
});
</script>
