<?php

require_once __DIR__ . '/../Model/Estoque.php'; 

class EstoqueController {
    private $mensagemErro = ''; 

    public function getMensagemErro() {
        $error = $this->mensagemErro;
        $this->mensagemErro = '';
        return $error;
    }

    public function listarItensEstoque(): array {
        $estoqueModel = new Estoque();
        return $estoqueModel->listarTodosItens(); 
    }

    public function adicionarItemEstoque(string $nomeProduto, int $quantidade, string $fornecedor): bool {
        $estoqueModel = new Estoque();
        $sucesso = $estoqueModel->adicionarItem($nomeProduto, $quantidade, $fornecedor);
        return $sucesso;
    }

    public function buscarItemEstoquePorId(int $id): ?array {
        $estoqueModel = new Estoque();
        $item = $estoqueModel->buscarPorId($id);
        if (!$item) {
            $this->mensagemErro = "Item de estoque com ID " . $id . " não encontrado.";
        }
        return $item;
    }

    public function atualizarItemEstoque(int $id, string $nomeProduto, int $quantidade, string $fornecedor): bool {
        $estoqueModel = new Estoque();
        $sucesso = $estoqueModel->atualizarItem($id, $nomeProduto, $quantidade, $fornecedor);
        if (!$sucesso) {
            $this->mensagemErro = "Não foi possível atualizar o item de estoque ID " . $id . " no controller.";
        }
        return $sucesso;
    }

    
}
?>