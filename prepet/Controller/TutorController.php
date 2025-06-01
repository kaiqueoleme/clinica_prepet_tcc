<?php
// Controller/TutorController.php

require_once '../Model/Tutor.php';
require_once '../Model/ConexaoBD.php';

class TutorController {

    public function garantirTutor(int $idPessoa): int {
        $tutor = new Tutor();
        // Tenta buscar o tutor pela id_pessoa
        if ($tutor->buscarTutorPorIdPessoa($idPessoa)) {
            return $tutor->getId(); // Retorna o ID do tutor existente
        } else {
            // Se não encontrou, insere um novo registro de tutor
            $tutor->setIdPessoa($idPessoa);
            if ($tutor->inserirBD()) {
                if ($tutor->buscarTutorPorIdPessoa($idPessoa)) {
                    return $tutor->getId();
                }
            }
        }
        return 0;
    }
    
    public function buscarIdPessoaDoTutor(int $idTutor): int {
        $tutor = new Tutor();
        if ($tutor->buscarTutorPorId($idTutor)) {
            return $tutor->getIdPessoa();
        }
        return 0;
    }
}
?>