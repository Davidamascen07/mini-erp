<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Mini ERP'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4f46e5',
                        secondary: '#10b981'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
    <nav class="bg-primary shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="index.php" class="text-white text-xl font-bold">
                        <i class="fas fa-box mr-2"></i>Mini ERP
                    </a>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="index.php" class="text-white hover:text-gray-200 px-3 py-2 rounded-md transition">
                        <i class="fas fa-cube mr-1"></i>Produtos
                    </a>
                    <a href="cupons.php" class="text-white hover:text-gray-200 px-3 py-2 rounded-md transition">
                        <i class="fas fa-tag mr-1"></i>Cupons
                    </a>
                    <a href="pedidos.php" class="text-white hover:text-gray-200 px-3 py-2 rounded-md transition">
                        <i class="fas fa-shopping-cart mr-1"></i>Pedidos
                    </a>
                    <a href="carrinho.php" class="text-white hover:text-gray-200 px-3 py-2 rounded-md transition relative">
                        <i class="fas fa-shopping-bag mr-1"></i>Carrinho
                        <span id="cart-count" class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center">
                            <?php 
                            require_once 'app/models/Carrinho.php';
                            echo Carrinho::contarItens();
                            ?>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <?php if (isset($flash) && $flash): ?>
            <div class="mb-6">
                <div class="alert <?php echo $flash['type'] === 'success' ? 'alert-success' : 'alert-error'; ?>" id="flash-message">
                    <i class="fas <?php echo $flash['type'] === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'; ?> mr-2"></i>
                    <?php echo htmlspecialchars($flash['message']); ?>
                </div>
            </div>
        <?php endif; ?>
        
        <style>
            .alert {
                padding: 1rem;
                margin-bottom: 1rem;
                border-radius: 0.5rem;
                display: flex;
                align-items: center;
            }
            .alert-success {
                background-color: #d1fae5;
                border-color: #10b981;
                color: #065f46;
            }
            .alert-error {
                background-color: #fee2e2;
                border-color: #ef4444;
                color: #991b1b;
            }
        </style>
        
        <script>
            // Auto-hide flash messages
            const flashMessage = document.getElementById('flash-message');
            if (flashMessage) {
                setTimeout(() => {
                    flashMessage.style.opacity = '0';
                    flashMessage.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => {
                        flashMessage.remove();
                    }, 500);
                }, 5000);
            }
        </script>
