<?php

class Tutor {
    private $id;
    private $idPessoa; // id_pessoa na tabela

    public function setId($id) { $this->id = $id; }
    public function getId() { return $this->id; }

    public function setIdPessoa($idPessoa) { $this->idPessoa = $idPessoa; }
    public function getIdPessoa() { return $this->idPessoa; }

    // Método para inserir um novo tutor
    public function inserirBD() {
        require_once 'ConexaoBD.php';
        $con = new ConexaoBD();
        $conn = $con->conectar();

        $sql = "INSERT INTO Tutor (id_pessoa) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $this->idPessoa);
        
        $resultado = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $resultado;
    }

    // Método para buscar um tutor pelo id_pessoa
    public function buscarTutorPorIdPessoa($idPessoa) {
        require_once 'ConexaoBD.php';
        $con = new ConexaoBD();
        $conn = $con->conectar();

        $sql = "SELECT id FROM Tutor WHERE id_pessoa = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idPessoa);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $this->id = $row['id']; // Define o ID do tutor encontrado
            $this->idPessoa = $idPessoa; // Mantém o idPessoa
            $stmt->close();
            $conn->close();
            return true; // Tutor encontrado
        } else {
            $stmt->close();
            $conn->close();
            return false; // Tutor não encontrado
        }
    }
    
    // Método para buscar um tutor pelo id (chave primária da tabela Tutor)
    public function buscarTutorPorId($id) {
        require_once 'ConexaoBD.php';
        $con = new ConexaoBD();
        $conn = $con->conectar();

        $sql = "SELECT id_pessoa FROM Tutor WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $this->id = $id; 
            $this->idPessoa = $row['id_pessoa'];
            $stmt->close();
            $conn->close();
            return true; 
        } else {
            $stmt->close();
            $conn->close();
            return false; 
        }
    }
}
?>