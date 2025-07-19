<?php
/**
 * Classe para gerenciar carrinho de compras em sessão
 */
class Carrinho {

    /**
     * Adiciona produto ao carrinho
     */
    public static function adicionar($produto_id, $variacao_id, $quantidade, $preco, $nome_produto, $nome_variacao) {
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }

        $key = $produto_id . '_' . $variacao_id;
        
        if (isset($_SESSION['carrinho'][$key])) {
            $_SESSION['carrinho'][$key]['quantidade'] += $quantidade;
        } else {
            $_SESSION['carrinho'][$key] = [
                'produto_id' => $produto_id,
                'variacao_id' => $variacao_id,
                'quantidade' => $quantidade,
                'preco' => $preco,
                'nome_produto' => $nome_produto,
                'nome_variacao' => $nome_variacao
            ];
        }
    }

    /**
     * Remove produto do carrinho
     */
    public static function remover($produto_id, $variacao_id) {
        $key = $produto_id . '_' . $variacao_id;
        
        if (isset($_SESSION['carrinho'][$key])) {
            unset($_SESSION['carrinho'][$key]);
        }
    }

    /**
     * Atualiza quantidade de um item
     */
    public static function atualizarQuantidade($produto_id, $variacao_id, $quantidade) {
        $key = $produto_id . '_' . $variacao_id;
        
        if (isset($_SESSION['carrinho'][$key])) {
            if ($quantidade <= 0) {
                self::remover($produto_id, $variacao_id);
            } else {
                $_SESSION['carrinho'][$key]['quantidade'] = $quantidade;
            }
        }
    }

    /**
     * Retorna itens do carrinho
     */
    public static function getItens() {
        return $_SESSION['carrinho'] ?? [];
    }

    /**
     * Calcula subtotal do carrinho
     */
    public static function calcularSubtotal() {
        $subtotal = 0;
        $itens = self::getItens();
        
        foreach ($itens as $item) {
            $subtotal += $item['preco'] * $item['quantidade'];
        }
        
        return $subtotal;
    }

    /**
     * Conta total de itens
     */
    public static function contarItens() {
        $total = 0;
        $itens = self::getItens();
        
        foreach ($itens as $item) {
            $total += $item['quantidade'];
        }
        
        return $total;
    }

    /**
     * Verifica se o carrinho está vazio
     */
    public static function estaVazio() {
        return empty($_SESSION['carrinho']);
    }

    /**
     * Limpa o carrinho
     */
    public static function limpar() {
        $_SESSION['carrinho'] = [];
    }

    /**
     * Aplica cupom de desconto
     */
    public static function aplicarCupom($codigo_cupom, $desconto) {
        $_SESSION['cupom'] = [
            'codigo' => $codigo_cupom,
            'desconto' => $desconto
        ];
    }

    /**
     * Remove cupom
     */
    public static function removerCupom() {
        unset($_SESSION['cupom']);
    }

    /**
     * Retorna cupom aplicado
     */
    public static function getCupom() {
        return $_SESSION['cupom'] ?? null;
    }
}
?>
