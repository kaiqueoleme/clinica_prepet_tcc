<?php
// Model/Usuario.php

class Usuario {
    private $login;
    private $senha;
    private $id_pessoa;
    private $id_acesso;

    public function setLogin($login) { $this->login = $login; }
    public function getLogin() { return $this->login; }

    public function setSenha($senha) { $this->senha = $senha; }
    public function getSenha() { return $this->senha; }

    public function setIdPessoa($id_pessoa) { $this->id_pessoa = $id_pessoa; }
    public function getIdPessoa() { return $this->id_pessoa; }

    public function setIdAcesso($id_acesso) { $this->id_acesso = $id_acesso; }
    public function getIdAcesso() { return $this->id_acesso; }

    public function inserirBD() {
        require_once 'ConexaoBD.php';
        $con = new ConexaoBD();
        $conn = $con->conectar();

        $senhaHash = password_hash($this->senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuario (login, senha, id_pessoa, id_acesso) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        $stmt->bind_param("ssii", $this->login, $senhaHash, $this->id_pessoa, $this->id_acesso);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            return true;
        } else {
            $stmt->close();
            $conn->close();
            return false;
        }
    }

    public function carregarUsuario($login) {
        require_once 'ConexaoBD.php';
        $con = new ConexaoBD();
        $conn = $con->conectar();

        // Selecionamos as colunas que realmente existem na tabela
        $sql = "SELECT login, senha, id_pessoa, id_acesso FROM usuario WHERE login = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $row = $res->fetch_assoc();
            // REMOVIDO: $this->id = $row['id']; // Linha 64 antes
            // Agora, populamos as propriedades do objeto com o que foi encontrado no BD
            $this->setLogin($row['login']); // Define o login do objeto (já é o mesmo do parâmetro)
            $this->setSenha($row['senha']); // A senha aqui é o hash
            $this->setIdPessoa($row['id_pessoa']);
            $this->setIdAcesso($row['id_acesso']);
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