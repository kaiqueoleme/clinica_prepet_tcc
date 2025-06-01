<?php
// Controller/UsuarioController.php

if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__ . '/../Model/Pessoa.php';

class UsuarioController {

    public function inserir($login, $senha, $id_acesso) {
        require_once '../Model/Usuario.php';
        $usuario = new Usuario();
        $usuario->setLogin($login);
        $usuario->setSenha($senha); // A senha será hashada no Model
        $usuario->setIdAcesso($id_acesso);
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

    public function buscarDadosUsuarioLogado($login) {
        require_once __DIR__ . '/../Model/Usuario.php';

        $usuario = new Usuario();
        if (!$usuario->carregarUsuario($login)) {
            $this->mensagemErro = "Usuário não encontrado.";
            return null;
        }

        $pessoa = new Pessoa();
        if (!$pessoa->carregarPessoaPorId($usuario->getIdPessoa())) {
            $this->mensagemErro = "Dados pessoais não encontrados para o usuário.";
        }

        return ['usuario' => $usuario, 'pessoa' => $pessoa];
    }

    public function alterarDadosUsuario($loginAtual, $novoLogin, $senha, $idPessoa, $nome, $dataNascimento, $telefone, $rg, $cpf) {
        require_once __DIR__ . '/../Model/Usuario.php';

        $pessoaModel = new Pessoa();
        if (!$pessoaModel->carregarPessoaPorId($idPessoa)) {
            $this->mensagemErro = "Pessoa não encontrada para atualização.";
            return false;
        }

        $pessoaModel->setNome($nome);
        $pessoaModel->setDataNascimento($dataNascimento);
        $pessoaModel->setTelefone($telefone);
        $pessoaModel->setRG($rg);
        $pessoaModel->setCPF($cpf);

        if (!$pessoaModel->atualizarBD()) {
            $this->mensagemErro = "Erro ao atualizar dados pessoais.";
            return false;
        }

        $usuarioModel = new Usuario();

        $resultadoUpdateUsuario = $usuarioModel->atualizarBD($loginAtual, $novoLogin, $senha);

        if ($resultadoUpdateUsuario === "ERRO_LOGIN_EXISTENTE") {
            $this->mensagemErro = "O novo login informado ('" . htmlspecialchars($novoLogin) . "') já está em uso. Escolha outro.";
            return false;
        } elseif (!$resultadoUpdateUsuario) {
            $this->mensagemErro = "Erro ao atualizar dados de acesso (login/senha).";
            return false;
        }
        
        if ($loginAtual !== $novoLogin && $resultadoUpdateUsuario === true) {
            $_SESSION['login_usuario'] = $novoLogin;
            if (isset($_SESSION['usuario_logado'])) {
                 $_SESSION['usuario_logado'] = $novoLogin;
            }
        }

        return true;
    }
}
?>