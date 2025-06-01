<?php
if(!isset($_SESSION))
    {
        session_start();
    }
require_once '../Model/Pessoa.php';
require_once '../Model/ConexaoBD.php';
require_once '../Model/Usuario.php';

class PessoaController{

    public function inserir($nome, $dataNascimento, $telefone, $rg, $cpf) {
        require_once '../Model/Pessoa.php';
        $pessoa = new Pessoa();
        $pessoa->setNome($nome);
        $pessoa->setDataNascimento($dataNascimento);
        $pessoa->setTelefone($telefone);
        $pessoa->setRG($rg);
        $pessoa->setCPF($cpf);
        $r = $pessoa->inserirBD();
        if ($r) {
            $_SESSION['Pessoa'] = serialize($pessoa);
            $_SESSION['id_pessoa'] = $pessoa->getID();
        }
        return $r;
    }

    public function atualizar($id, $nome, $cpf, $email, $dataNascimento) {
        require_once '../Model/Pessoa.php';
        $pessoa = new Pessoa();
        $pessoa->setId($id);
        $pessoa->setNome($nome);
        $pessoa->setCPF($cpf);
        $pessoa->setEmail($email);
        $pessoa->setDataNascimento($dataNascimento);
        $r = $pessoa->atualizarBD();
        $_SESSION['pessoa'] = serialize($pessoa);
        return $r;
    }

    public function login($cpf, $senha) {
        require_once '../Model/Pessoa.php';
        $pessoa = new Pessoa();
        $pessoa->carregarPessoa($cpf);
        $verSenha = $pessoa->getSenha();

        if ($senha == $verSenha) {
            $_SESSION['Pessoa'] = serialize($pessoa);
            return true;
        } else {
            return false;
        }
    }

    public function listarPessoasClientes(): array {
        $con = new ConexaoBD();
        $conn = $con->conectar();

        $sql = "SELECT p.id, p.nome, p.dataNascimento, p.telefone, p.rg, p.cpf 
                FROM Pessoa p
                JOIN Usuario u ON p.id = u.id_pessoa
                WHERE u.id_acesso = 1";

        $resultado = $conn->query($sql);
        $pessoas = [];

        if ($resultado && $resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $pessoas[] = $row;
            }
        }
        $conn->close();
        return $pessoas;
    }

    public function listarTodasAsPessoasParaSelecao(): array {
        $con = new ConexaoBD();
        $conn = $con->conectar();
        $pessoas = [];

        $sql = "SELECT p.id, p.nome, p.cpf 
                FROM Pessoa p
                ORDER BY p.nome ASC";

        $resultado = $conn->query($sql);

        if ($resultado && $resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $pessoas[] = $row;
            }
        }
        $conn->close();
        return $pessoas;
    }


}
