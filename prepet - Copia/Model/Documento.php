<?php
// C:\xampp\htdocs\prepet\Model\Documento.php

// Inclui o arquivo de conexão com o banco de dados
require_once 'ConexaoBD.php';

class Documento {
    // Propriedades da classe, que representam as colunas da tabela 'documento'
    private $id;
    private $tipo;
    private $conteudo;
    private $id_vet;      // Chave Estrangeira para a tabela 'veterinario'
    private $id_pac;      // Chave Estrangeira para a tabela 'paciente'

    // Construtor (opcional, mas pode ser útil para inicializar)
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

    /**
     * Retorna o ID do documento.
     * @return int O ID do documento.
     */
    public function getId(): ?int {
        return $this->id;
    }

    /**
     * Define o ID do documento.
     * @param int $id O ID a ser definido.
     */
    public function setId(int $id): void {
        $this->id = $id;
    }

    /**
     * Retorna o tipo do documento.
     * @return string O tipo do documento (ex: 'Exame', 'Atestado', 'Receita').
     */
    public function getTipo(): ?string {
        return $this->tipo;
    }

    /**
     * Define o tipo do documento.
     * @param string $tipo O tipo a ser definido.
     */
    public function setTipo(string $tipo): void {
        $this->tipo = $tipo;
    }

    /**
     * Retorna o conteúdo do documento (pode ser texto, caminho para arquivo, etc.).
     * @return string O conteúdo do documento.
     */
    public function getConteudo(): ?string {
        return $this->conteudo;
    }

    /**
     * Define o conteúdo do documento.
     * @param string $conteudo O conteúdo a ser definido.
     */
    public function setConteudo(string $conteudo): void {
        $this->conteudo = $conteudo;
    }

    /**
     * Retorna o ID do veterinário associado ao documento.
     * @return int O ID do veterinário.
     */
    public function getIdVet(): ?int {
        return $this->id_vet;
    }

    /**
     * Define o ID do veterinário associado ao documento.
     * @param int $id_vet O ID do veterinário a ser definido.
     */
    public function setIdVet(int $id_vet): void {
        $this->id_vet = $id_vet;
    }

    /**
     * Retorna o ID do paciente associado ao documento.
     * @return int O ID do paciente.
     */
    public function getIdPac(): ?int {
        return $this->id_pac;
    }

    /**
     * Define o ID do paciente associado ao documento.
     * @param int $id_pac O ID do paciente a ser definido.
     */
    public function setIdPac(int $id_pac): void {
        $this->id_pac = $id_pac;
    }

    // --- Métodos de interação com o banco de dados (CRUD) ---

    /**
     * Insere um novo documento no banco de dados.
     * @return bool True se a inserção for bem-sucedida, false caso contrário.
     */
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

        // 'ssii' para string (tipo), string (conteudo), inteiro (id_vet), inteiro (id_pac)
        $stmt->bind_param("ssii", $this->tipo, $this->conteudo, $this->id_vet, $this->id_pac);
        
        $resultado = $stmt->execute();

        // Opcional: Se quiser pegar o ID do documento recém-inserido
        if ($resultado) {
            $this->id = $conn->insert_id;
        }

        $stmt->close();
        $conn->close();
        return $resultado;
    }

    /**
     * Busca um documento pelo ID e popula as propriedades da classe.
     * @param int $id O ID do documento a ser buscado.
     * @return bool True se o documento for encontrado e as propriedades populadas, false caso contrário.
     */
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

        $stmt->bind_param("i", $id); // 'i' para inteiro
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

    /**
     * Lista todos os documentos do banco de dados, com nomes de veterinário e paciente.
     * @return array Um array de arrays associativos, onde cada sub-array representa um documento.
     */
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

    /**
     * Lista documentos associados a um veterinário específico.
     * @param int $idVet O ID do veterinário.
     * @return array Uma lista de documentos do veterinário.
     */
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

    /**
     * Lista documentos associados a um paciente específico.
     * @param int $idPac O ID do paciente.
     * @return array Uma lista de documentos do paciente.
     */
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