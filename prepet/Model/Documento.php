<?php

require_once 'ConexaoBD.php';

class Documento {
    // Propriedades da classe, que representam as colunas da tabela 'documento'
    private $id;
    private $tipo;
    private $conteudo;
    private $id_vet;      // Chave Estrangeira para a tabela 'veterinario'
    private $id_pac;      // Chave Estrangeira para a tabela 'paciente'

    public function __construct(
        $id = null,
        $tipo = null,
        $conteudo = null,
        $id_vet = null,
        $id_pac = null
    ) {
        $this->id = $id;
        $this->tipo = $tipo;
        $this->conteudo = $conteudo;
        $this->id_vet = $id_vet;
        $this->id_pac = $id_pac;
    }

    // --- Getters e Setters para cada propriedade ---

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getTipo(): ?string {
        return $this->tipo;
    }

    public function setTipo(string $tipo): void {
        $this->tipo = $tipo;
    }

    public function getConteudo(): ?string {
        return $this->conteudo;
    }

    public function setConteudo(string $conteudo): void {
        $this->conteudo = $conteudo;
    }

    public function getIdVet(): ?int {
        return $this->id_vet;
    }

    public function setIdVet(int $id_vet): void {
        $this->id_vet = $id_vet;
    }

    public function getIdPac(): ?int {
        return $this->id_pac;
    }

    public function setIdPac(int $id_pac): void {
        $this->id_pac = $id_pac;
    }

    // --- Métodos de interação com o banco de dados (CRUD) ---

    public function inserir(): bool {
        $con = new ConexaoBD();
        $conn = $con->conectar();

        $sql = "INSERT INTO documento (tipo, conteudo, id_vet, id_pac) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            error_log("Erro na preparação da query de inserção (Documento::inserir): " . $conn->error);
            $conn->close();
            return false;
        }

        $stmt->bind_param("ssii", $this->tipo, $this->conteudo, $this->id_vet, $this->id_pac);
        
        $resultado = $stmt->execute();

        if ($resultado) {
            $this->id = $conn->insert_id;
        }

        $stmt->close();
        $conn->close();
        return $resultado;
    }

    public function buscarPorId(int $id): bool {
        $con = new ConexaoBD();
        $conn = $con->conectar();

        $sql = "SELECT id, tipo, conteudo, id_vet, id_pac FROM documento WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            error_log("Erro na preparação da query de busca (Documento::buscarPorId): " . $conn->error);
            $conn->close();
            return false;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $row = $resultado->fetch_assoc();
            $this->id = $row['id'];
            $this->tipo = $row['tipo'];
            $this->conteudo = $row['conteudo'];
            $this->id_vet = $row['id_vet'];
            $this->id_pac = $row['id_pac'];
            
            $stmt->close();
            $conn->close();
            return true;
        }
        
        $stmt->close();
        $conn->close();
        return false;
    }

    public function listarTodos(): array {
        $con = new ConexaoBD();
        $conn = $con->conectar();

        $sql = "SELECT d.id, d.tipo, d.conteudo, d.id_vet, d.id_pac, 
                       pv.nome AS nome_veterinario, 
                       pa.nome AS nome_paciente
                FROM documento d
                JOIN veterinario v ON d.id_vet = v.id
                JOIN pessoa pv ON v.id_pessoa = pv.id
                JOIN paciente pa ON d.id_pac = pa.id
                ORDER BY d.id DESC"; // Ordena pelo ID mais recente

        $resultado = $conn->query($sql);

        $documentos = [];
        if ($resultado && $resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $documentos[] = $row;
            }
        }
        $conn->close();
        return $documentos;
    }

    public function listarPorVeterinario(int $idVet): array {
        $con = new ConexaoBD();
        $conn = $con->conectar();

        $sql = "SELECT d.id, d.tipo, d.conteudo, d.id_vet, d.id_pac, 
                       pa.nome AS nome_paciente
                FROM documento d
                JOIN paciente pa ON d.id_pac = pa.id
                WHERE d.id_vet = ?
                ORDER BY d.id DESC";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            error_log("Erro na preparação da query listarPorVeterinario (Documento::listarPorVeterinario): " . $conn->error);
            $conn->close();
            return [];
        }
        $stmt->bind_param("i", $idVet);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $documentos = [];
        if ($resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $documentos[] = $row;
            }
        }
        $stmt->close();
        $conn->close();
        return $documentos;
    }

    public function listarPorPaciente(int $idPac): array {
        $con = new ConexaoBD();
        $conn = $con->conectar();

        $sql = "SELECT d.id, d.tipo, d.conteudo, d.id_vet, d.id_pac, 
                       pv.nome AS nome_veterinario
                FROM documento d
                JOIN veterinario v ON d.id_vet = v.id
                JOIN pessoa pv ON v.id_pessoa = pv.id
                WHERE d.id_pac = ?
                ORDER BY d.id DESC";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            error_log("Erro na preparação da query listarPorPaciente (Documento::listarPorPaciente): " . $conn->error);
            $conn->close();
            return [];
        }
        $stmt->bind_param("i", $idPac);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $documentos = [];
        if ($resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $documentos[] = $row;
            }
        }
        $stmt->close();
        $conn->close();
        return $documentos;
    }

    /**
     * Atualiza os dados de um documento existente no banco de dados.
     * @return bool True se a atualização for bem-sucedida, false caso contrário.
     */
    public function atualizar(): bool {
        if ($this->id === null) {
            error_log("Erro (Documento::atualizar): ID do documento não definido para atualização.");
            return false; // Não pode atualizar sem um ID
        }

        $con = new ConexaoBD();
        $conn = $con->conectar();

        $sql = "UPDATE documento SET tipo = ?, conteudo = ?, id_vet = ?, id_pac = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            error_log("Erro na preparação da query de atualização (Documento::atualizar): " . $conn->error);
            $conn->close();
            return false;
        }

        // 'ssiii' para string (tipo), string (conteudo), inteiro (id_vet), inteiro (id_pac), inteiro (id do documento)
        $stmt->bind_param("ssiii", $this->tipo, $this->conteudo, $this->id_vet, $this->id_pac, $this->id);
        
        $resultado = $stmt->execute();

        $stmt->close();
        $conn->close();
        return $resultado;
    }

    /**
     * Exclui um documento do banco de dados pelo seu ID.
     * @return bool True se a exclusão for bem-sucedida, false caso contrário.
     */
    public function excluir(): bool {
        if ($this->id === null) {
            error_log("Erro (Documento::excluir): ID do documento não definido para exclusão.");
            return false; // Não pode excluir sem um ID
        }
        
        $con = new ConexaoBD();
        $conn = $con->conectar();

        $sql = "DELETE FROM documento WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            error_log("Erro na preparação da query de exclusão (Documento::excluir): " . $conn->error);
            $conn->close();
            return false;
        }

        $stmt->bind_param("i", $this->id); // 'i' para inteiro
        
        $resultado = $stmt->execute();

        $stmt->close();
        $conn->close();
        return $resultado;
    }
}
?>