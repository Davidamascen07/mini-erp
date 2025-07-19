<?php
require_once 'BaseController.php';
require_once 'app/models/Produto.php';
require_once 'app/models/Variacao.php';
require_once 'app/models/Estoque.php';
require_once 'app/models/Carrinho.php';

/**
 * Controller para gerenciar produtos
 */
class ProdutoController extends BaseController {
    private $produtoModel;
    private $variacaoModel;
    private $estoqueModel;

    public function __construct() {
        $this->produtoModel = new Produto();
        $this->variacaoModel = new Variacao();
        $this->estoqueModel = new Estoque();
    }

    /**
     * Lista produtos com opção de cadastro/edição
     */
    public function index() {
        $produtos = $this->produtoModel->findWithVariacoes();
        
        // Agrupa produtos por ID
        $produtosAgrupados = [];
        foreach ($produtos as $produto) {
            $id = $produto['id'];
            if (!isset($produtosAgrupados[$id])) {
                $produtosAgrupados[$id] = [
                    'id' => $produto['id'],
                    'nome' => $produto['nome'],
                    'preco' => $produto['preco'],
                    'variacoes' => []
                ];
            }
            
            if ($produto['variacao_id']) {
                $produtosAgrupados[$id]['variacoes'][] = [
                    'id' => $produto['variacao_id'],
                    'nome' => $produto['nome_variacao'],
                    'estoque' => $produto['quantidade'] ?? 0
                ];
            }
        }

        $this->render('produtos/index', [
            'produtos' => $produtosAgrupados,
            'flash' => $this->getFlash()
        ]);
    }

    /**
     * Salva produto (criar ou atualizar)
     */
    public function salvar() {
        if (!$this->isPost()) {
            $this->redirect('index.php');
        }

        $id = $_POST['id'] ?? null;
        $nome = $this->sanitize($_POST['nome']);
        $preco = floatval($_POST['preco']);
        $variacoes = $_POST['variacoes'] ?? [];

        try {
            if ($id) {
                // Atualizar produto existente
                $this->produtoModel->update($id, $nome, $preco);
                
                // Remove variações antigas
                $this->estoqueModel->deleteByProduto($id);
                $this->variacaoModel->deleteByProduto($id);
            } else {
                // Criar novo produto
                $id = $this->produtoModel->create($nome, $preco);
            }

            // Adiciona variações
            foreach ($variacoes as $variacao) {
                if (!empty($variacao['nome'])) {
                    $variacao_id = $this->variacaoModel->create($id, $variacao['nome']);
                    $this->estoqueModel->create($id, $variacao_id, intval($variacao['estoque'] ?? 0));
                }
            }

            $this->setFlash('success', 'Produto salvo com sucesso!');
        } catch (Exception $e) {
            $this->setFlash('error', 'Erro ao salvar produto: ' . $e->getMessage());
        }

        $this->redirect('index.php');
    }

    /**
     * Adiciona produto ao carrinho
     */
    public function comprar() {
        if (!$this->isPost()) {
            $this->json(['success' => false, 'message' => 'Método inválido']);
        }

        $produto_id = intval($_POST['produto_id']);
        $variacao_id = intval($_POST['variacao_id']);
        $quantidade = intval($_POST['quantidade']);

        // Verifica se há estoque suficiente
        if (!$this->estoqueModel->verificarEstoque($produto_id, $variacao_id, $quantidade)) {
            $this->json(['success' => false, 'message' => 'Estoque insuficiente']);
        }

        // Busca dados do produto
        $produto = $this->produtoModel->findById($produto_id);
        $variacao = $this->variacaoModel->findById($variacao_id);

        if (!$produto || !$variacao) {
            $this->json(['success' => false, 'message' => 'Produto não encontrado']);
        }

        // Adiciona ao carrinho
        Carrinho::adicionar(
            $produto_id,
            $variacao_id,
            $quantidade,
            $produto['preco'],
            $produto['nome'],
            $variacao['nome_variacao']
        );

        $this->json([
            'success' => true, 
            'message' => 'Produto adicionado ao carrinho!',
            'carrinho_count' => Carrinho::contarItens()
        ]);
    }

    /**
     * Busca produto específico para edição
     */
    public function editar($id) {
        $produto = $this->produtoModel->findWithVariacoesById($id);
        
        if (empty($produto)) {
            $this->setFlash('error', 'Produto não encontrado');
            $this->redirect('index.php');
        }

        // Agrupa variações
        $produtoData = [
            'id' => $produto[0]['id'],
            'nome' => $produto[0]['nome'],
            'preco' => $produto[0]['preco'],
            'variacoes' => []
        ];

        foreach ($produto as $item) {
            if ($item['variacao_id']) {
                $produtoData['variacoes'][] = [
                    'id' => $item['variacao_id'],
                    'nome' => $item['nome_variacao'],
                    'estoque' => $item['quantidade'] ?? 0
                ];
            }
        }

        $this->render('produtos/form', [
            'produto' => $produtoData,
            'flash' => $this->getFlash()
        ]);
    }

    /**
     * Exibe formulário para novo produto
     */
    public function novo() {
        $this->render('produtos/form', [
            'produto' => null,
            'flash' => $this->getFlash()
        ]);
    }

    /**
     * Remove produto
     */
    public function deletar($id) {
        try {
            $this->produtoModel->delete($id);
            $this->setFlash('success', 'Produto removido com sucesso!');
        } catch (Exception $e) {
            $this->setFlash('error', 'Erro ao remover produto');
        }

        $this->redirect('index.php');
    }
}
?>
