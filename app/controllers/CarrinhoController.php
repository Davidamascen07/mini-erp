<?php
require_once 'BaseController.php';
require_once 'app/models/Carrinho.php';
require_once 'app/models/Cupom.php';
require_once 'app/models/Pedido.php';
require_once 'app/models/Endereco.php';

/**
 * Controller para gerenciar carrinho de compras
 */
class CarrinhoController extends BaseController {
    private $cupomModel;
    private $pedidoModel;
    private $enderecoModel;
    private $db;

    public function __construct() {
        $this->cupomModel = new Cupom();
        $this->pedidoModel = new Pedido();
        $this->enderecoModel = new Endereco();
        
        // Conecta ao banco para transações
        require_once 'config/database.php';
        $database = new Database();
        $this->db = $database->connect();
    }

    /**
     * Exibe carrinho de compras
     */
    public function index() {
        $itens = Carrinho::getItens();
        $subtotal = Carrinho::calcularSubtotal();
        $cupom = Carrinho::getCupom();
        
        // Valida cupom aplicado (se existir)
        if ($cupom) {
            $validacao = $this->cupomModel->validarCupom($cupom['codigo'], $subtotal);
            
            if (!$validacao['valido']) {
                // Remove cupom inválido
                Carrinho::removerCupom();
                $cupom = null;
                $this->setFlash('warning', 'Cupom foi removido: ' . $validacao['erro']);
            }
        }
        
        $desconto = $cupom ? $cupom['desconto'] : 0;
        $frete = $this->pedidoModel->calcularFrete($subtotal);
        $total = $subtotal - $desconto + $frete;

        $this->render('carrinho/index', [
            'itens' => $itens,
            'subtotal' => $subtotal,
            'cupom' => $cupom,
            'desconto' => $desconto,
            'frete' => $frete,
            'total' => $total,
            'flash' => $this->getFlash()
        ]);
    }

    /**
     * Atualiza quantidade de item no carrinho
     */
    public function atualizarQuantidade() {
        if (!$this->isPost()) {
            $this->json(['success' => false, 'message' => 'Método inválido']);
        }

        $produto_id = intval($_POST['produto_id']);
        $variacao_id = intval($_POST['variacao_id']);
        $quantidade = intval($_POST['quantidade']);

        Carrinho::atualizarQuantidade($produto_id, $variacao_id, $quantidade);
        
        // Recalcula subtotal após atualização
        $subtotal = Carrinho::calcularSubtotal();
        
        // Verifica se há cupom aplicado e se ainda é válido
        $cupom = Carrinho::getCupom();
        $cupomMessage = '';
        
        if ($cupom) {
            $validacao = $this->cupomModel->validarCupom($cupom['codigo'], $subtotal);
            
            if (!$validacao['valido']) {
                // Remove cupom se não for mais válido
                Carrinho::removerCupom();
                $cupomMessage = ' Cupom removido: ' . $validacao['erro'];
            }
        }

        $this->json([
            'success' => true,
            'message' => $cupomMessage ? 'Quantidade atualizada.' . $cupomMessage : '',
            'subtotal' => number_format($subtotal, 2, ',', '.'),
            'count' => Carrinho::contarItens()
        ]);
    }

    /**
     * Remove item do carrinho
     */
    public function removerItem() {
        if (!$this->isPost()) {
            $this->json(['success' => false, 'message' => 'Método inválido']);
        }

        $produto_id = intval($_POST['produto_id']);
        $variacao_id = intval($_POST['variacao_id']);

        Carrinho::remover($produto_id, $variacao_id);
        
        // Recalcula subtotal após remoção
        $subtotal = Carrinho::calcularSubtotal();
        
        // Verifica se há cupom aplicado e se ainda é válido
        $cupom = Carrinho::getCupom();
        $cupomMessage = '';
        
        if ($cupom) {
            $validacao = $this->cupomModel->validarCupom($cupom['codigo'], $subtotal);
            
            if (!$validacao['valido']) {
                // Remove cupom se não for mais válido
                Carrinho::removerCupom();
                $cupomMessage = ' Cupom removido: ' . $validacao['erro'];
            }
        }

        $this->json([
            'success' => true,
            'message' => 'Item removido do carrinho' . $cupomMessage,
            'subtotal' => number_format($subtotal, 2, ',', '.'),
            'count' => Carrinho::contarItens()
        ]);
    }

    /**
     * Aplica cupom de desconto
     */
    public function aplicarCupom() {
        if (!$this->isPost()) {
            $this->json(['success' => false, 'message' => 'Método inválido']);
        }

        $codigo = $this->sanitize($_POST['cupom']);
        $subtotal = Carrinho::calcularSubtotal();

        $validacao = $this->cupomModel->validarCupom($codigo, $subtotal);

        if (!$validacao['valido']) {
            $this->json(['success' => false, 'message' => $validacao['erro']]);
        }

        $cupom = $validacao['cupom'];
        $desconto = $this->cupomModel->calcularDesconto($subtotal, $cupom['desconto_percentual']);

        Carrinho::aplicarCupom($codigo, $desconto);

        $frete = $this->pedidoModel->calcularFrete($subtotal);
        $total = $subtotal - $desconto + $frete;

        $this->json([
            'success' => true,
            'message' => 'Cupom aplicado com sucesso!',
            'desconto' => number_format($desconto, 2, ',', '.'),
            'total' => number_format($total, 2, ',', '.')
        ]);
    }

    /**
     * Remove cupom aplicado
     */
    public function removerCupom() {
        Carrinho::removerCupom();

        $subtotal = Carrinho::calcularSubtotal();
        $frete = $this->pedidoModel->calcularFrete($subtotal);

        $this->json([
            'success' => true,
            'message' => 'Cupom removido',
            'total' => number_format($subtotal + $frete, 2, ',', '.')
        ]);
    }

    /**
     * Finaliza pedido
     */
    public function finalizar() {
        if (Carrinho::estaVazio()) {
            $this->setFlash('error', 'Carrinho vazio');
            $this->redirect('carrinho.php');
        }

        $this->render('carrinho/finalizar', [
            'flash' => $this->getFlash()
        ]);
    }

    /**
     * Processa finalização do pedido
     */
    public function processar() {
        if (!$this->isPost()) {
            $this->redirect('carrinho.php');
        }

        if (Carrinho::estaVazio()) {
            $this->setFlash('error', 'Carrinho vazio');
            $this->redirect('carrinho.php');
        }

        // Dados do cliente
        $email = $this->sanitize($_POST['email']);
        $cep = $this->sanitize($_POST['cep']);
        $rua = $this->sanitize($_POST['rua']);
        $numero = $this->sanitize($_POST['numero']);
        $complemento = $this->sanitize($_POST['complemento']);
        $bairro = $this->sanitize($_POST['bairro']);
        $cidade = $this->sanitize($_POST['cidade']);
        $estado = $this->sanitize($_POST['estado']);

        // Calcula valores
        $subtotal = Carrinho::calcularSubtotal();
        $cupom = Carrinho::getCupom();
        $desconto = $cupom ? $cupom['desconto'] : 0;
        $frete = $this->pedidoModel->calcularFrete($subtotal);
        $total = $subtotal - $desconto + $frete;

        try {
            $this->db->beginTransaction();

            // Cria pedido
            $pedido_id = $this->pedidoModel->create(
                $subtotal,
                $frete,
                $desconto,
                $total,
                $cupom ? $cupom['codigo'] : null,
                $email
            );

            // Adiciona endereço
            $this->enderecoModel->create(
                $pedido_id,
                $cep,
                $rua,
                $numero,
                $complemento,
                $bairro,
                $cidade,
                $estado
            );

            // Adiciona itens do pedido e atualiza estoque
            require_once 'app/models/ItemPedido.php';
            require_once 'app/models/Estoque.php';
            
            $itemPedidoModel = new ItemPedido();
            $estoqueModel = new Estoque();

            foreach (Carrinho::getItens() as $item) {
                // Verifica estoque
                if (!$estoqueModel->verificarEstoque($item['produto_id'], $item['variacao_id'], $item['quantidade'])) {
                    throw new Exception("Estoque insuficiente para {$item['nome_produto']}");
                }

                // Adiciona item ao pedido
                $itemPedidoModel->create(
                    $pedido_id,
                    $item['produto_id'],
                    $item['variacao_id'],
                    $item['quantidade'],
                    $item['preco']
                );

                // Diminui estoque
                $estoqueModel->diminuirEstoque(
                    $item['produto_id'],
                    $item['variacao_id'],
                    $item['quantidade']
                );
            }

            $this->db->commit();

            // Envia email de confirmação
            require_once 'app/models/EmailService.php';
            $emailService = new EmailService();
            $pedidoCompleto = $this->pedidoModel->findWithItems($pedido_id);
            $emailService->enviarConfirmacaoPedido($email, $pedidoCompleto);

            // Limpa carrinho
            Carrinho::limpar();

            $this->setFlash('success', "Pedido #{$pedido_id} realizado com sucesso! Verifique seu email.");
            $this->redirect('pedidos.php');

        } catch (Exception $e) {
            $this->db->rollback();
            $this->setFlash('error', 'Erro ao processar pedido: ' . $e->getMessage());
            $this->redirect('carrinho.php?action=finalizar');
        }
    }

    /**
     * Consulta CEP via AJAX
     */
    public function consultarCEP() {
        if (!$this->isPost()) {
            $this->json(['success' => false, 'message' => 'Método inválido']);
        }

        $cep = $this->sanitize($_POST['cep']);
        $endereco = $this->enderecoModel->consultarCEP($cep);

        if ($endereco) {
            $this->json(['success' => true, 'endereco' => $endereco]);
        } else {
            $this->json(['success' => false, 'message' => 'CEP não encontrado']);
        }
    }
}
?>
