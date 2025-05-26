<?php
if (!isset($_SESSION)) {
    session_start();
}

class EnderecoController {

    public function inserir($logradouro, $numero, $bairro, $cidade, $cep) {
        require_once '../Model/Endereco.php';
        $endereco = new Endereco();

        $endereco->setLogradouro($logradouro);
        $endereco->setNumero($numero);
        $endereco->setBairro($bairro);
        $endereco->setCidade($cidade);
        $endereco->setCEP($cep);

        // Pega o id_pessoa da sessão
        if (isset($_SESSION['id_pessoa'])) {
            $endereco->setIdPessoa($_SESSION['id_pessoa']);
        } else {
            return false; // Falha: id da pessoa não definido
        }

        $r = $endereco->inserirBD();
        $_SESSION['Endereco'] = serialize($endereco);

        return $r;
    }

    public function atualizar($id, $logradouro, $numero, $bairro, $cidade, $cep) {
        require_once '../Model/Endereco.php';
        $endereco = new Endereco();

        $endereco->setID($id);
        $endereco->setLogradouro($logradouro);
        $endereco->setNumero($numero);
        $endereco->setBairro($bairro);
        $endereco->setCidade($cidade);
        $endereco->setCEP($cep);

        if (isset($_SESSION['id_pessoa'])) {
            $endereco->setIdPessoa($_SESSION['id_pessoa']);
        } else {
            return false;
        }

        $r = $endereco->atualizarBD();
        $_SESSION['Endereco'] = serialize($endereco);

        return $r;
    }

    public function carregarEnderecoPorIdPessoa($idPessoa) {
        require_once '../Model/Endereco.php';
        $endereco = new Endereco();
        if ($endereco->carregarPorPessoa($idPessoa)) {
            return $endereco;
        }
        return null;
    }
}
?>