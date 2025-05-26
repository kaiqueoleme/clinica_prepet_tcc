<?php

require_once __DIR__ . '/../Model/Estoque.php'; // Inclui o Model do Estoque

class EstoqueController {

    public function listarItensEstoque(): array {
        $estoqueModel = new Estoque(); // Instancia o Model Estoque
        return $estoqueModel->listarTodosItens(); // Chama o método de listagem e retorna os resultados
    }

}
?>