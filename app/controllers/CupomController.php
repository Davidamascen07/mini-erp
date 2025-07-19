<?php
require_once 'BaseController.php';
require_once 'app/models/Cupom.php';

/**
 * Controller para gerenciar cupons de desconto
 */
class CupomController extends BaseController {
    private $cupomModel;

    public function __construct() {
        $this->cupomModel = new Cupom();
    }

    /**
     * Lista cupons
     */
    public function index() {
        $cupons = $this->cupomModel->findAll();
        
        // Garante que todos os cupons tenham a propriedade 'ativo'
        foreach ($cupons as &$cupom) {
            if (!isset($cupom['ativo'])) {
                $cupom['ativo'] = 1; // Define como ativo por padrão se não existir
            }
        }

        $this->render('cupons/index', [
            'cupons' => $cupons,
            'flash' => $this->getFlash()
        ]);
    }

    /**
     * Exibe formulário para novo cupom
     */
    public function novo() {
        $this->render('cupons/form', [
            'cupom' => null,
            'flash' => $this->getFlash()
        ]);
    }

    /**
     * Exibe formulário para editar cupom
     */
    public function editar($id) {
        $cupom = $this->cupomModel->findById($id);

        if (!$cupom) {
            $this->setFlash('error', 'Cupom não encontrado');
            $this->redirect('cupons.php');
        }

        $this->render('cupons/form', [
            'cupom' => $cupom,
            'flash' => $this->getFlash()
        ]);
    }

    /**
     * Salva cupom (criar ou atualizar)
     */
    public function salvar() {
        if (!$this->isPost()) {
            $this->redirect('cupons.php');
        }

        $id = $_POST['id'] ?? null;
        $codigo = strtoupper($this->sanitize($_POST['codigo']));
        $desconto_percentual = floatval($_POST['desconto_percentual']);
        $validade = $this->sanitize($_POST['validade']);
        $valor_minimo = floatval($_POST['valor_minimo'] ?? 0);

        // Validações
        if (empty($codigo) || $desconto_percentual <= 0) {
            $this->setFlash('error', 'Dados inválidos');
            $this->redirect($id ? "cupons.php?action=editar&id={$id}" : 'cupons.php?action=novo');
        }

        try {
            if ($id) {
                // Atualizar cupom existente
                $this->cupomModel->update($id, $codigo, $desconto_percentual, $validade, $valor_minimo);
                $this->setFlash('success', 'Cupom atualizado com sucesso!');
            } else {
                // Criar novo cupom
                $this->cupomModel->create($codigo, $desconto_percentual, $validade, $valor_minimo);
                $this->setFlash('success', 'Cupom criado com sucesso!');
            }
        } catch (Exception $e) {
            $this->setFlash('error', 'Erro ao salvar cupom: ' . $e->getMessage());
        }

        $this->redirect('cupons.php');
    }

    /**
     * Ativa/Desativa cupom
     */
    public function toggleStatus() {
        if (!$this->isPost()) {
            $this->json(['success' => false, 'message' => 'Método inválido']);
        }

        $id = intval($_POST['id']);
        $ativo = intval($_POST['ativo']);

        try {
            if ($ativo) {
                $this->cupomModel->ativar($id);
                $message = 'Cupom ativado com sucesso!';
            } else {
                $this->cupomModel->desativar($id);
                $message = 'Cupom desativado com sucesso!';
            }

            $this->json(['success' => true, 'message' => $message]);
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => 'Erro ao alterar status do cupom']);
        }
    }

    /**
     * Remove cupom
     */
    public function deletar($id) {
        try {
            $this->cupomModel->delete($id);
            $this->setFlash('success', 'Cupom removido com sucesso!');
        } catch (Exception $e) {
            $this->setFlash('error', 'Erro ao remover cupom');
        }

        $this->redirect('cupons.php');
    }
}
?>
