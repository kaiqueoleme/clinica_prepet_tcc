<?php
// Model/Paciente.php

class Paciente { // Nome da classe alterado para Paciente
    private $id;
    private $nome;
    private $especie;
    private $raca;
    private $dataNasc;
    private $idTutor;

    // Getters e Setters
    public function setId($id) { $this->id = $id; }
    public function getId() { return $this->id; }

    public function setNome($nome) { $this->nome = $nome; }
    public function getNome() { return $this->nome; }

    public function setEspecie($especie) { $this->especie = $especie; }
    public function getEspecie() { return $this->especie; }

    public function setRaca($raca) { $this->raca = $raca; }
    public function getRaca() { return $this->raca; }

    public function setDataNasc($dataNasc) { $this->dataNasc = $dataNasc; }
    public function getDataNasc() { return $this->dataNasc; }

    public function setIdTutor($idTutor) { $this->idTutor = $idTutor; }
    public function getIdTutor() { return $this->idTutor; }

    public function inserirBD() {
        require_once 'ConexaoBD.php';
        $con = new ConexaoBD();
        $conn = $con->conectar();

        $sql = "INSERT INTO Paciente (nome, especie, raca, data_nasc, id_tutor) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", 
            $this->nome, 
            $this->especie, 
            $this->raca, 
            $this->dataNasc, 
            $this->idTutor
        );
        
        $resultado = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $resultado;
    }

    public function carregarPaciente($id) {
        require_once 'ConexaoBD.php';
        $con = new ConexaoBD();
        $conn = $con->conectar();

        $sql = "SELECT * FROM Paciente WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $this->id = $row['id'];
            $this->nome = $row['nome'];
            $this->especie = $row['especie'];
            $this->raca = $row['raca'];
            $this->dataNasc = $row['data_nasc'];
            $this->idTutor = $row['id_tutor'];
            $stmt->close();
            $conn->close();
            return true;
        } else {
            $stmt->close();
            $conn->close();
            return false;
        }
    }

    public function listarPacientesPorTutor($idTutor) {
        require_once 'ConexaoBD.php';
        $con = new ConexaoBD();
        $conn = $con->conectar();

        $sql = "SELECT * FROM Paciente WHERE id_tutor = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idTutor);
        $stmt->execute();
        $res = $stmt->get_result();

        $pacientes = [];
        if ($res->num_rows > 0) {
            while($row = $res->fetch_assoc()) {
                $pacientes[] = $row;
            }
        }
        $stmt->close();
        $conn->close();
        return $pacientes;
    }

    public function atualizarBD() {
        require_once 'ConexaoBD.php';
        $con = new ConexaoBD();
        $conn = $con->conectar();

        $sql = "UPDATE Paciente SET 
                nome = ?, 
                especie = ?, 
                raca = ?, 
                data_nasc = ? 
                WHERE id = ? AND id_tutor = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssii", 
            $this->nome, 
            $this->especie, 
            $this->raca, 
            $this->dataNasc, 
            $this->id, 
            $this->idTutor
        );
        
        $resultado = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $resultado;
    }

    public function excluirBD() {
        require_once 'ConexaoBD.php';
        $con = new ConexaoBD();
        $conn = $con->conectar();

        // Tabela Paciente
        $sql = "DELETE FROM Paciente WHERE id = ? AND id_tutor = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $this->id, $this->idTutor);
        
        $resultado = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $resultado;
    }
}
?>