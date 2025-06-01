<?php

require_once 'ConexaoBD.php';

class Procedimento {
    private $id;
    private $tipo;
    private $data_procedimento;
    private $resultado;
    private $status_paciente;
    private $diagnostico;
    private $id_vet;
    private $id_pac;

    // Getters
    public function getId() { return $this->id; }
    public function getTipo() { return $this->tipo; }
    public function getDataProcedimento() { return $this->data_procedimento; }
    public function getResultado() { return $this->resultado; }
    public function getStatusPaciente() { return $this->status_paciente; }
    public function getDiagnostico() { return $this->diagnostico; }
    public function getIdVet() { return $this->id_vet; }
    public function getIdPac() { return $this->id_pac; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setTipo($tipo) { $this->tipo = $tipo; }
    public function setDataProcedimento($data_procedimento) { $this->data_procedimento = $data_procedimento; }
    public function setResultado($resultado) { $this->resultado = $resultado; }
    public function setStatusPaciente($status_paciente) { $this->status_paciente = $status_paciente; }
    public function setDiagnostico($diagnostico) { $this->diagnostico = $diagnostico; }
    public function setIdVet($id_vet) { $this->id_vet = $id_vet; }
    public function setIdPac($id_pac) { $this->id_pac = $id_pac; }

    public function inserir(): bool {
        $con = new ConexaoBD();
        $conn = $con->conectar();

        $sql = "INSERT INTO procedimento (tipo, data_procedimento, resultado, status_paciente, diagnostico, id_vet, id_pac)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            error_log("Erro na preparação da query de inserção de procedimento: " . $conn->error);
            $conn->close();
            return false;
        }

        $stmt->bind_param(
            "sssssii",
            $this->tipo,
            $this->data_procedimento,
            $this->resultado,
            $this->status_paciente,
            $this->diagnostico,
            $this->id_vet,
            $this->id_pac
        );

        $executou = $stmt->execute();
        
        if (!$executou) {
            error_log("Erro na execução da query de inserção de procedimento: " . $stmt->error);
        }

        $stmt->close();
        $conn->close();
        return $executou;
    }

    public function buscarPorId(int $id): ?array {
        $con = new ConexaoBD();
        $conn = $con->conectar();

        $sql = "SELECT * FROM procedimento WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $procedimento = $resultado->fetch_assoc();

        $stmt->close();
        $conn->close();
        return $procedimento;
    }

    public function listarProcedimentosPorPaciente(int $idPac): array {
        $con = new ConexaoBD();
        $conn = $con->conectar();

        $sql = "SELECT p.*, v.crmv, p_vet.nome AS nome_veterinario, pac.nome AS nome_paciente
                FROM procedimento p
                JOIN Veterinario v ON p.id_vet = v.id
                JOIN Pessoa p_vet ON v.id_pessoa = p_vet.id
                JOIN Paciente pac ON p.id_pac = pac.id
                WHERE p.id_pac = ?
                ORDER BY p.data_procedimento DESC";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idPac);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $procedimentos = [];
        if ($resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $procedimentos[] = $row;
            }
        }
        $stmt->close();
        $conn->close();
        return $procedimentos;
    }

}
?>