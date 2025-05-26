<?php
// Controller/UsuarioController.php

if (!isset($_SESSION)) {
    session_start();
}

class UsuarioController {

    public function inserir($login, $senha) {
        require_once '../Model/Usuario.php';
        $usuario = new Usuario();

        $usuario->setLogin($login);
        $usuario->setSenha($senha); // A senha será hashada no Model
        if (isset($_SESSION['id_pessoa'])) {
            $usuario->setIdPessoa($_SESSION['id_pessoa']);
        } else {
            error_log("Erro: id_pessoa não encontrado na sessão para cadastro de usuário.");
            return false;
        }
        $usuario->setIdAcesso(1);

        $r = $usuario->inserirBD();
        return $r;
    }

    public function login($login, $senha) {
        require_once '../Model/Usuario.php';
        $usuario = new Usuario();

        if ($usuario->carregarUsuario($login)) {
            if (password_verify($senha, $usuario->getSenha())) {
                $_SESSION['Usuario'] = serialize($usuario);
                $_SESSION['id_pessoa'] = $usuario->getIdPessoa();
                return true;
            }
        }
        return false;
    }
}
?>