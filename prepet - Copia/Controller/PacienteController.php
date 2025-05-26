<?php
// Controller/PacienteController.php

require_once '../Model/Paciente.php'; // Inclui o Model Paciente
require_once '../Model/ConexaoBD.php'; // Inclui a ConexãoBD
// Pode ser necessário incluir o Model Tutor se for manipular tutores aqui
// require_once '../Model/Tutor.php'; 

class PacienteController {

    // Método para inserir um novo paciente
    public function inserir(
        $nome,
        $especie,
        $raca,
        $dataNasc,
        $idTutor // Recebe o ID do tutor (do cliente logado)
    ) {
        $paciente = new Paciente();
        $paciente->setNome($nome);
        $paciente->setEspecie($especie);
        $paciente->setRaca($raca);
        $paciente->setDataNasc($dataNasc);
        $paciente->setIdTutor($idTutor);

        // Tenta inserir no banco de dados
        return $paciente->inserirBD();
    }

    // Método para buscar um paciente pelo ID
    public function buscarPaciente($id) {
        $paciente = new Paciente();
        if ($paciente->carregarPaciente($id)) {
            return $paciente; // Retorna o objeto Paciente preenchido
        }
        return null; // Retorna null se não encontrar
    }

    // Método para listar pacientes de um tutor específico
    public function listarPacientes($idTutor) {
        $paciente = new Paciente(); // Cria uma instância para usar o método estático ou de instância
        return $paciente->listarPacientesPorTutor($idTutor);
    }

    // Método para atualizar um paciente
    public function atualizar(
        $id,
        $nome,
        $especie,
        $raca,
        $dataNasc,
        $idTutor // Passa o idTutor para a segurança da atualização
    ) {
        $paciente = new Paciente();
        $paciente->setId($id);
        $paciente->setNome($nome);
        $paciente->setEspecie($especie);
        $paciente->setRaca($raca);
        $paciente->setDataNasc($dataNasc);
        $paciente->setIdTutor($idTutor);

        return $paciente->atualizarBD();
    }

    public function listarTodosPacientes(): array {
        $con = new ConexaoBD();
        $conn = $con->conectar();

        $sql = "SELECT p.id, p.nome, p.especie, p.raca, p.data_nasc, t.id_pessoa AS id_tutor_pessoa_id, pes.nome AS nome_tutor
                FROM Paciente p
                JOIN Tutor t ON p.id_tutor = t.id
                JOIN Pessoa pes ON t.id_pessoa = pes.id
                ORDER BY p.nome ASC"; // Ordena pelo nome do paciente

        $resultado = $conn->query($sql);
        $pacientes = [];

        if ($resultado && $resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $pacientes[] = $row;
            }
        }
        $conn->close();
        return $pacientes;
    }

    // Método para excluir um paciente
    public function excluir($id, $idTutor) { // Adicionado idTutor para segurança
        $paciente = new Paciente();
        $paciente->setId($id);
        $paciente->setIdTutor($idTutor); // Define o idTutor no objeto para a exclusão segura
        return $paciente->excluirBD();
    }
}
?>