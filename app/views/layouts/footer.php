    </main>

    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h3 class="text-lg font-semibold mb-2">Mini ERP</h3>
                <p class="text-gray-400">Sistema de controle de Pedidos, Produtos, Cupons e Estoque</p>
                <p class="text-gray-400 text-sm mt-2">
                    Desenvolvido com PHP puro + MySQL + Tailwind CSS
                </p>
            </div>
        </div>
    </footer>

    <!-- Scripts JavaScript -->
    <script>
        // Função para atualizar contador do carrinho
        function updateCartCount(count) {
            document.getElementById('cart-count').textContent = count;
        }

        // Função para exibir mensagens
        function showMessage(message, type = 'success') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.innerHTML = `
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'} mr-2"></i>
                ${message}
            `;
            
            const main = document.querySelector('main');
            main.insertBefore(alertDiv, main.firstChild);
            
            setTimeout(() => {
                alertDiv.style.opacity = '0';
                alertDiv.style.transition = 'opacity 0.5s ease';
                setTimeout(() => {
                    alertDiv.remove();
                }, 500);
            }, 3000);
        }

        // Função para formatar moeda
        function formatMoney(value) {
            return 'R$ ' + parseFloat(value).toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
        }

        // Função para requisições AJAX
        function ajaxRequest(url, data, callback) {
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(data)
            })
            .then(response => response.json())
            .then(callback)
            .catch(error => {
                console.error('Error:', error);
                showMessage('Erro na requisição', 'error');
            });
        }

        // Confirmar ações de exclusão
        function confirmDelete(message) {
            return confirm(message || 'Tem certeza que deseja excluir este item?');
        }
    </script>
</body>
</html>
