<?php

require_once 'ConexaoBD.php';

class Agendamento {
    private $id;
    private $id_pac;
    private $id_vet;
    private $data_agend;
    private $hora_consulta; 
    private $tipo_servico;
    private $observacoes; 
    private $status;

    // Getters e Setters
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getIdPac() { return $this->id_pac; }
    public function setIdPac($id_pac) { $this->id_pac = $id_pac; }

    public function getIdVet() { return $this->id_vet; }
    public function setIdVet($id_vet) { $this->id_vet = $id_vet; }

    public function getDataAgend() { return $this->data_agend; }
    public function setDataAgend($data_agend) { $this->data_agend = $data_agend; }

    public function getHoraConsulta() { return $this->hora_consulta; }
    public function setHoraConsulta($hora_consulta) { $this->hora_consulta = $hora_consulta; }

    public function getTipoServico() { return $this->tipo_servico; }
    public function setTipoServico($tipo_servico) { $this->tipo_servico = $tipo_servico; }

    public function getObservacoes() { return $this->observacoes; }
    public function setObservacoes($observacoes) { $this->observacoes = $observacoes; }

    public function getStatus() { return $this->status; }
    public function setStatus($status) { $this->status = $status; }

    // Métodos CRUD (inserir)
    public function inserirBD(): bool {
        $con = new ConexaoBD();
        $conn = $con->conectar();

        $sql = "INSERT INTO Agendamento (id_pac, id_vet, data_agend, hora_consulta, tipo_servico, observacoes, status)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "iisssss",
            $this->id_pac,
            $this->id_vet,
            $this->data_agend,
            $this->hora_consulta,
            $this->tipo_servico, 
            $this->observacoes,  
            $this->status
        );

        $resultado = $stmt->execute();
        $this->id = $conn->insert_id;
        $stmt->close();
        $conn->close();
        return $resultado;
    }

    public function carregarAgendamento(int $id): bool {
        $con = new ConexaoBD();
        $conn = $con->conectar();
        $sql = "SELECT id, data_agend, hora_consulta, tipo_servico, observacoes, status, id_vet, id_pac FROM Agendamento WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $dados = $resultado->fetch_assoc();
            $this->id = $dados['id'];
            $this->data_agend = $dados['data_agend'];
            $this->hora_consulta = $dados['hora_consulta'];
            $this->tipo_servico = $dados['tipo_servico'];
            $this->observacoes = $dados['observacoes'];
            $this->status = $dados['status'];
            $this->id_vet = $dados['id_vet'];
            $this->id_pac = $dados['id_pac'];
            $stmt->close();
            $conn->close();
            return true;
        }
        $stmt->close();
        $conn->close();
        return false;
    }

    public function atualizar(): bool {
        $con = new ConexaoBD();
        $conn = $con->conectar();
        $sql = "UPDATE Agendamento SET data_agend = ?, hora_consulta = ?, tipo_servico = ?, observacoes = ?, status = ?, id_vet = ?, id_pac = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sssssiii", // Data, Hora, Tipo, Obs, Status, ID_Vet, ID_Pac, ID
            $this->data_agend,
            $this->hora_consulta,
            $this->tipo_servico,
            $this->observacoes,
            $this->status,
            $this->id_vet,
            $this->id_pac,
            $this->id 
        );
        $executou = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $executou;
    }

    public function atualizarStatus(string $novoStatus): bool {
        $con = new ConexaoBD();
        $conn = $con->conectar();
        $sql = "UPDATE Agendamento SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $novoStatus, $this->id);
        $executou = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $executou;
    }

}
?>