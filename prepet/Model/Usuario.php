<?php
// Model/Usuario.php

class Usuario
{
    private $login;
    private $senha;
    private $id_pessoa;
    private $id_acesso;

    public function setLogin($login)
    {
        $this->login = $login;
    }
    public function getLogin()
    {
        return $this->login;
    }

    public function setSenha($senha)
    {
        $this->senha = $senha;
    }
    public function getSenha()
    {
        return $this->senha;
    }

    public function setIdPessoa($id_pessoa)
    {
        $this->id_pessoa = $id_pessoa;
    }
    public function getIdPessoa()
    {
        return $this->id_pessoa;
    }

    public function setIdAcesso($id_acesso)
    {
        $this->id_acesso = $id_acesso;
    }
    public function getIdAcesso()
    {
        return $this->id_acesso;
    }

    public function inserirBD()
    {
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

    public function carregarUsuario($login)
    {
        require_once 'ConexaoBD.php';
        $con = new ConexaoBD();
        $conn = $con->conectar();

        $sql = "SELECT login, senha, id_pessoa, id_acesso FROM usuario WHERE login = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $this->setLogin($row['login']);
            $this->setSenha($row['senha']);
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

    public function atualizarBD($loginAtual, $novoLogin, $novaSenha = null)
    {
        require_once 'ConexaoBD.php';
        $con = new ConexaoBD();
        $conn = $con->conectar();
        if ($conn->connect_error) {
            error_log("Connection failed: " . $conn->connect_error);
            return false;
        }

        if ($novoLogin !== $loginAtual && !empty($novoLogin)) {
            $sqlCheck = "SELECT login FROM usuario WHERE login = ? AND login != ?";
            $stmtCheck = $conn->prepare($sqlCheck);
            $stmtCheck->bind_param("ss", $novoLogin, $loginAtual);
            $stmtCheck->execute();
            $resCheck = $stmtCheck->get_result();
            if ($resCheck->num_rows > 0) {
                $stmtCheck->close();
                $conn->close();
                return "ERRO_LOGIN_EXISTENTE";
            }
            $stmtCheck->close();
        }

        if (!empty($novaSenha)) {
            $novaSenhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
            $sql = "UPDATE usuario SET login = ?, senha = ? WHERE login = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                error_log("Prepare failed (update com senha): (" . $conn->errno . ") " . $conn->error);
                $conn->close();
                return false;
            }
            $stmt->bind_param("sss", $novoLogin, $novaSenhaHash, $loginAtual);
        } else {
            $sql = "UPDATE usuario SET login = ? WHERE login = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                error_log("Prepare failed (update sem senha): (" . $conn->errno . ") " . $conn->error);
                $conn->close();
                return false;
            }
            $stmt->bind_param("ss", $novoLogin, $loginAtual);
        }

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            return true;
        } else {
            error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            $stmt->close();
            $conn->close();
            return false;
        }
    }
}
