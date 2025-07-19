<?php $title = ($cupom ? 'Editar' : 'Novo') . ' Cupom'; ?>

<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-tag mr-2"></i><?php echo $cupom ? 'Editar' : 'Novo'; ?> Cupom
            </h1>
            <a href="cupons.php" class="text-gray-600 hover:text-gray-800 transition">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </div>

    <form method="POST" action="cupons.php?action=salvar" class="p-6">
        <?php if ($cupom): ?>
            <input type="hidden" name="id" value="<?php echo $cupom['id']; ?>">
        <?php endif; ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="codigo" class="block text-sm font-medium text-gray-700 mb-2">Código do Cupom *</label>
                <input type="text" id="codigo" name="codigo" required maxlength="50"
                       value="<?php echo $cupom ? htmlspecialchars($cupom['codigo']) : ''; ?>"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary uppercase"
                       placeholder="Ex: DESCONTO10">
                <small class="text-gray-500">Use apenas letras, números e símbolos básicos</small>
            </div>
            
            <div>
                <label for="desconto_percentual" class="block text-sm font-medium text-gray-700 mb-2">Desconto (%) *</label>
                <div class="relative">
                    <input type="number" id="desconto_percentual" name="desconto_percentual" 
                           step="0.1" min="0.1" max="100" required
                           value="<?php echo $cupom ? $cupom['desconto_percentual'] : ''; ?>"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-primary">
                    <span class="absolute right-3 top-2 text-gray-500">%</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="valor_minimo" class="block text-sm font-medium text-gray-700 mb-2">Valor Mínimo do Pedido</label>
                <div class="relative">
                    <span class="absolute left-3 top-2 text-gray-500">R$</span>
                    <input type="number" id="valor_minimo" name="valor_minimo" 
                           step="0.01" min="0"
                           value="<?php echo $cupom ? $cupom['valor_minimo'] : '0'; ?>"
                           class="w-full border border-gray-300 rounded-md pl-10 pr-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
                <small class="text-gray-500">Deixe 0 para sem valor mínimo</small>
            </div>
            
            <div>
                <label for="validade" class="block text-sm font-medium text-gray-700 mb-2">Data de Validade *</label>
                <input type="date" id="validade" name="validade" required
                       value="<?php echo $cupom ? $cupom['validade'] : ''; ?>"
                       min="<?php echo date('Y-m-d'); ?>"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
        </div>

        <!-- Preview do Cupom -->
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-800 mb-3">Preview do Cupom</h3>
            <div class="bg-gradient-to-r from-primary to-purple-600 text-white p-6 rounded-lg">
                <div class="text-center">
                    <div class="text-2xl font-bold mb-2" id="preview-codigo">
                        <?php echo $cupom ? htmlspecialchars($cupom['codigo']) : 'SEU-CUPOM'; ?>
                    </div>
                    <div class="text-lg mb-2">
                        <span id="preview-desconto"><?php echo $cupom ? $cupom['desconto_percentual'] : '0'; ?></span>% de desconto
                    </div>
                    <div class="text-sm opacity-90">
                        Válido até <span id="preview-validade"><?php echo $cupom ? date('d/m/Y', strtotime($cupom['validade'])) : '__/__/____'; ?></span>
                        <br>
                        <span id="preview-minimo">
                            <?php if ($cupom && $cupom['valor_minimo'] > 0): ?>
                                Valor mínimo: R$ <?php echo number_format($cupom['valor_minimo'], 2, ',', '.'); ?>
                            <?php else: ?>
                                <span id="preview-minimo-text">Sem valor mínimo</span>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex space-x-4">
            <button type="submit" class="bg-primary hover:bg-blue-700 text-white px-6 py-2 rounded-md transition">
                <i class="fas fa-save mr-2"></i><?php echo $cupom ? 'Atualizar' : 'Criar'; ?> Cupom
            </button>
            <a href="cupons.php" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md transition">
                <i class="fas fa-times mr-2"></i>Cancelar
            </a>
        </div>
    </form>
</div>

<script>
// Atualiza preview em tempo real
document.getElementById('codigo').addEventListener('input', function() {
    document.getElementById('preview-codigo').textContent = this.value || 'SEU-CUPOM';
});

document.getElementById('desconto_percentual').addEventListener('input', function() {
    document.getElementById('preview-desconto').textContent = this.value || '0';
});

document.getElementById('validade').addEventListener('input', function() {
    if (this.value) {
        const data = new Date(this.value);
        const dataFormatada = data.toLocaleDateString('pt-BR');
        document.getElementById('preview-validade').textContent = dataFormatada;
    } else {
        document.getElementById('preview-validade').textContent = '__/__/____';
    }
});

document.getElementById('valor_minimo').addEventListener('input', function() {
    const valor = parseFloat(this.value) || 0;
    const previewElement = document.getElementById('preview-minimo');
    
    if (valor > 0) {
        previewElement.innerHTML = `Valor mínimo: R$ ${valor.toFixed(2).replace('.', ',')}`;
    } else {
        previewElement.innerHTML = '<span id="preview-minimo-text">Sem valor mínimo</span>';
    }
});

// Converte código para maiúsculo
document.getElementById('codigo').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
});
</script>
