<?php
// Model/Estoque.php

require_once 'ConexaoBD.php';

class Estoque {
    private $id;
    private $nome_produto;
    private $quantidade;
    private $fornecedor;

    // Getters
    public function getId() { return $this->id; }
    public function getNomeProduto() { return $this->nome_produto; }
    public function getQuantidade() { return $this->quantidade; }
    public function getFornecedor() { return $this->fornecedor; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setNomeProduto($nome_produto) { $this->nome_produto = $nome_produto; }
    public function setQuantidade($quantidade) { $this->quantidade = $quantidade; }
    public function setFornecedor($fornecedor) { $this->fornecedor = $fornecedor; }

    public function listarTodosItens(): array {
        $con = new ConexaoBD();
        $conn = $con->conectar();

        $sql = "SELECT id, nome_produto, quantidade, fornecedor
                FROM Estoque
                ORDER BY nome_produto ASC";

        $resultado = $conn->query($sql);
        $itensEstoque = [];

        if ($resultado && $resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $itensEstoque[] = $row;
            }
        }
        $conn->close();
        return $itensEstoque;
    }

    public function buscarPorId(int $id): ?array {
        $con = new ConexaoBD();
        $conn = $con->conectar();

        $sql = "SELECT id, nome_produto, quantidade, fornecedor
                FROM Estoque
                WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $itemEstoque = null;
        if ($resultado->num_rows > 0) {
            $itemEstoque = $resultado->fetch_assoc();
        }

        $stmt->close();
        $conn->close();
        return $itemEstoque;
    }

    public function adicionarItem(string $nomeProduto, int $quantidade, string $fornecedor): bool {
        $con = new ConexaoBD();
        $conn = $con->conectar();


        $sql = "INSERT INTO Estoque (nome_produto, quantidade, fornecedor) VALUES (?, ?, ?)";

        $stmt = $conn->prepare($sql);

        // Verifica se a preparação da query falhou
        if ($stmt === false) {
            error_log("Erro ao preparar a query: " . $conn->error); // Registra o erro para depuração
            $conn->close();
            return false;
        }

        $stmt->bind_param("sis", $nomeProduto, $quantidade, $fornecedor);
        $sucesso = $stmt->execute();

        $stmt->close();
        $conn->close();

        return $sucesso;
    }

    public function atualizarItem(int $id, string $nomeProduto, int $quantidade, string $fornecedor): bool {
        $con = new ConexaoBD();
        $conn = $con->conectar();

        if ($conn->connect_error) {
            error_log("Falha na conexão (Estoque::atualizarItem): " . $conn->connect_error);
            return false;
        }

        $sql = "UPDATE Estoque SET nome_produto = ?, quantidade = ?, fornecedor = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            error_log("Erro ao preparar a query (Estoque::atualizarItem): " . $conn->error);
            $conn->close();
            return false;
        }

        $stmt->bind_param("sisi", $nomeProduto, $quantidade, $fornecedor, $id);
        $sucesso = $stmt->execute();

        if (!$sucesso) {
            error_log("Erro ao executar a query (Estoque::atualizarItem): " . $stmt->error);
        }

        $stmt->close();
        $conn->close();
        return $sucesso;
    }
}
?>