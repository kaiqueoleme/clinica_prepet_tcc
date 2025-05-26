<?php
// Model/Veterinario.php

require_once 'ConexaoBD.php';

class Veterinario {
    private $id;
    private $id_pessoa;
    private $crmv;
    private $especialidade; // Manter se você tiver essa coluna, ou remover
    private $despesa; // <<< ADICIONE ESTA LINHA
    private $salario; // <<< ADICIONE ESTA LINHA

    // Getters e Setters
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getIdPessoa() { return $this->id_pessoa; }
    public function setIdPessoa($id_pessoa) { $this->id_pessoa = $id_pessoa; }

    public function getCrmv() { return $this->crmv; }
    public function setCrmv($crmv) { $this->crmv = $crmv; }

    // Mantenha especialidade SE existir na sua tabela Veterinario.
    // Se não existir, remova-a da classe e da query listarVeterinarios.
    public function getEspecialidade() { return $this->especialidade; }
    public function setEspecialidade($especialidade) { $this->especialidade = $especialidade; }

    // Getters e Setters para despesa e salario
    public function getDespesa() { return $this->despesa; }
    public function setDespesa($despesa) { $this->despesa = $despesa; }

    public function getSalario() { return $this->salario; }
    public function setSalario($salario) { $this->salario = $salario; }

    // Método para listar veterinários
    public function listarVeterinarios(): array {
        $con = new ConexaoBD();
        $conn = $con->conectar();

        // AQUI: Ajuste o SELECT para o que você realmente quer listar
        // 'v.especialidade' só se a coluna existir
        $sql = "SELECT v.id, p.nome, v.crmv, v.especialidade FROM Veterinario v JOIN Pessoa p ON v.id_pessoa = p.id ORDER BY p.nome";

        $resultado = $conn->query($sql);
        $veterinarios = [];

        if ($resultado && $resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $veterinarios[] = $row;
            }
        }
        $conn->close();
        return $veterinarios;
    }
    
    // Método para buscar veterinário por ID
    public function buscarVeterinario(int $id): bool {
        $con = new ConexaoBD();
        $conn = $con->conectar();

        // AJUSTE A QUERY PARA AS COLUNAS REAIS DA SUA TABELA VETERINARIO
        $sql = "SELECT id, id_pessoa, crmv, despesa, salario FROM Veterinario WHERE id = ?"; // <<< MUDANÇA AQUI
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $row = $resultado->fetch_assoc();
            $this->id = $row['id'];
            $this->id_pessoa = $row['id_pessoa'];
            $this->crmv = $row['crmv'];
            $this->despesa = $row['despesa']; // <<< ADICIONE ESTA LINHA
            $this->salario = $row['salario']; // <<< ADICIONE ESTA LINHA
            $stmt->close();
            $conn->close();
            return true;
        }
        $stmt->close();
        $conn->close();
        return false;
    }

     public function buscarPorIdPessoa(int $idPessoa): ?array {
        $con = new ConexaoBD();
        $conn = $con->conectar();

        $sql = "SELECT id, crmv, especialidade, despesa, salario, id_pessoa FROM veterinario WHERE id_pessoa = ?";
        
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            // Loga o erro se a preparação da query falhar
            error_log("Erro na preparação da query (Veterinario::buscarPorIdPessoa): " . $conn->error);
            $conn->close();
            return null; // Retorna nulo em caso de erro
        }

        $stmt->bind_param("i", $idPessoa); // 'i' para inteiro, bind do id_pessoa
        $stmt->execute();
        $resultado = $stmt->get_result(); // Pega o resultado da query

        $veterinario = $resultado->fetch_assoc(); // Transforma o resultado em um array associativo

        $stmt->close();
        $conn->close();
        
        return $veterinario; // Retorna o array de dados do veterinário (ou null se não achou)
    }
}
?>