<?php
class Endereco {
    private $id;
    private $logradouro;
    private $numero;
    private $bairro;
    private $cidade;
    private $cep;
    private $id_pessoa;

    // Getters e Setters
    public function setId($id) { $this->id = $id; }
    public function getId() { return $this->id; }

    public function setLogradouro($logradouro) { $this->logradouro = $logradouro; }
    public function getLogradouro() { return $this->logradouro; }

    public function setNumero($numero) { $this->numero = $numero; }
    public function getNumero() { return $this->numero; }

    public function setBairro($bairro) { $this->bairro = $bairro; }
    public function getBairro() { return $this->bairro; }

    public function setCidade($cidade) { $this->cidade = $cidade; }
    public function getCidade() { return $this->cidade; }

    public function setCep($cep) { $this->cep = $cep; }
    public function getCep() { return $this->cep; }

    public function setIdPessoa($id_pessoa) { $this->id_pessoa = $id_pessoa; }
    public function getIdPessoa() { return $this->id_pessoa; }

    // Inserir endereço no banco
    public function inserirBD() {
        if (!isset($_SESSION['id_pessoa'])) {
            return false; // Pessoa não logada ou não definida
        }

        $this->id_pessoa = $_SESSION['id_pessoa'];

        require_once 'ConexaoBD.php';
        $con = new ConexaoBD();
        $conn = $con->conectar();

        $sql = "INSERT INTO endereco (logradouro, numero, bairro, cidade, cep, id_pessoa) 
                VALUES ('{$this->logradouro}', '{$this->numero}', '{$this->bairro}', '{$this->cidade}', '{$this->cep}', '{$this->id_pessoa}')";

        if ($conn->query($sql) === TRUE) {
            $this->id = $conn->insert_id;
            $conn->close();
            return true;
        } else {
            $conn->close();
            return false;
        }
    }

    public function carregarPorPessoa($id_pessoa) {
        require_once 'ConexaoBD.php';
        $con = new ConexaoBD();
        $conn = $con->conectar();

        $sql = "SELECT * FROM endereco WHERE id_pessoa = '$id_pessoa'";
        $res = $conn->query($sql);

        if ($res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $this->id = $row['id'];
            $this->logradouro = $row['logradouro'];
            $this->numero = $row['numero'];
            $this->bairro = $row['bairro'];
            $this->cidade = $row['cidade'];
            $this->cep = $row['cep'];
            $this->id_pessoa = $row['id_pessoa'];
            $conn->close();
            return true;
        } else {
            $conn->close();
            return false;
        }
    }

    // Atualizar endereço
    public function atualizarBD() {
        require_once 'ConexaoBD.php';
        $con = new ConexaoBD();
        $conn = $con->conectar();

        $sql = "UPDATE endereco SET 
                logradouro = '{$this->logradouro}', 
                numero = '{$this->numero}', 
                bairro = '{$this->bairro}', 
                cidade = '{$this->cidade}', 
                cep = '{$this->cep}' 
                WHERE id = {$this->id}";

        $resultado = $conn->query($sql);
        $conn->close();
        return $resultado === TRUE;
    }

    // Excluir endereço
    public function excluirBD() {
        require_once 'ConexaoBD.php';
        $con = new ConexaoBD();
        $conn = $con->conectar();

        $sql = "DELETE FROM endereco WHERE id = {$this->id}";
        $resultado = $conn->query($sql);
        $conn->close();
        return $resultado === TRUE;
    }
}
?>
