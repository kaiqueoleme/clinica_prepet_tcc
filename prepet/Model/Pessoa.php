<?php
class Pessoa {
    private $id;
    private $nome;
    private $dataNascimento;
    private $telefone;
    private $rg;
    private $cpf;

    // Getters e Setters
    public function setID($id) { $this->id = $id; }
    public function getID() { return $this->id; }

    public function setNome($nome) { $this->nome = $nome; }
    public function getNome() { return $this->nome; }

    public function setDataNascimento($dataNascimento) { $this->dataNascimento = $dataNascimento; }
    public function getDataNascimento() { return $this->dataNascimento; }

    public function setTelefone($telefone) { $this->telefone = $telefone; }
    public function getTelefone() { return $this->telefone; }

    public function setRG($rg) { $this->rg = $rg; }
    public function getRG() { return $this->rg; }

    public function setCPF($cpf) { $this->cpf = $cpf; }
    public function getCPF() { return $this->cpf; }

    // Métodos
    public function inserirBD() {
        require_once 'ConexaoBD.php';
        $con = new ConexaoBD();
        $conn = $con->conectar();
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "INSERT INTO pessoa (nome, dataNascimento, telefone, rg, cpf) VALUES ('{$this->nome}', '{$this->dataNascimento}', '{$this->telefone}', '{$this->rg}', '{$this->cpf}')";
        if ($conn->query($sql) === TRUE) {
            $this->id = mysqli_insert_id($conn);
            $conn->close();
            return true;
        } else {
            $conn->close();
            return false;
        }
    }

    public function carregarPessoa($cpf) {
        require_once 'ConexaoBD.php';
        $con = new ConexaoBD();
        $conn = $con->conectar();
        $sql = "SELECT * FROM Pessoa WHERE cpf = '$cpf'";
        $res = $conn->query($sql);

        if ($res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $this->id = $row['id'];
            $this->nome = $row['nome'];
            $this->dataNascimento = $row['dataNascimento'];
            $this->telefone = $row['telefone'];
            $this->rg = $row['rg'];
            $this->cpf = $row['cpf'];
            $conn->close();
            return true;
        } else {
            $conn->close();
            return false;
        }
    }

    // Atualizar dados da pessoa
    public function atualizarBD() {
        require_once 'ConexaoBD.php';
        $con = new ConexaoBD();
        $conn = $con->conectar();
        $sql = "UPDATE Pessoa SET 
                    nome = '{$this->nome}', 
                    dataNascimento = '{$this->dataNascimento}', 
                    telefone = '{$this->telefone}', 
                    rg = '{$this->rg}', 
                    cpf = '{$this->cpf}' 
                WHERE id = {$this->id}";
        $resultado = $conn->query($sql);
        $conn->close();
        return $resultado === TRUE;
    }

    // Excluir pessoa do banco de dados
    public function excluirBD() {
        require_once 'ConexaoBD.php';
        $con = new ConexaoBD();
        $conn = $con->conectar();

        $sql = "DELETE FROM Pessoa WHERE id = {$this->id}";
        $resultado = $conn->query($sql);
        $conn->close();
        return $resultado === TRUE;
    }

    public function carregarPessoaPorId($id) {
        require_once 'ConexaoBD.php';
        $con = new ConexaoBD();
        $conn = $con->conectar();
        if ($conn->connect_error) {
            // Em um cenário real, logar o erro em vez de usar die()
            error_log("Connection failed: " . $conn->connect_error);
            return false;
        }
        $sql = "SELECT * FROM Pessoa WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
            $conn->close();
            return false;
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $this->id = $row['id'];
            $this->nome = $row['nome'];
            $this->dataNascimento = $row['dataNascimento'];
            $this->telefone = $row['telefone'];
            $this->rg = $row['rg'];
            $this->cpf = $row['cpf'];
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
