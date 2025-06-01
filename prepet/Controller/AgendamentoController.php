<?php
// Controller/ConsultaController.php

require_once __DIR__ . '/../Model/ConexaoBD.php';
require_once __DIR__ . '/../Model/Agendamento.php';
require_once __DIR__ . '/../Model/Pessoa.php';
require_once __DIR__ . '/../Model/Paciente.php';
require_once __DIR__ . '/../Model/Veterinario.php';
require_once __DIR__ . '/../Model/Tutor.php';
require_once __DIR__ . '/../Controller/TutorController.php';

class AgendamentoController {

    public function agendarConsulta(
        int $id_pac,
        int $id_vet,
        string $data_agend,
        string $hora_consulta,
        string $tipo_servico,
        ?string $observacoes 
    ): bool {
        $agendamento = new Agendamento(); 
        $agendamento->setIdPac($id_pac);
        $agendamento->setIdVet($id_vet);
        $agendamento->setDataAgend($data_agend);
        $agendamento->setHoraConsulta($hora_consulta);
        $agendamento->setTipoServico($tipo_servico);
        $agendamento->setObservacoes($observacoes);
        $agendamento->setStatus('Agendado'); 

        return $agendamento->inserirBD();
    }

    public function listarTodosAgendamentos(): array {
        $con = new ConexaoBD();
        $conn = $con->conectar();

        $sql = "SELECT
                    a.id,
                    a.data_agend,
                    a.hora_consulta,
                    a.tipo_servico,
                    a.observacoes,
                    a.status,
                    p.nome AS nome_paciente,
                    vpessoa.nome AS nome_veterinario
                FROM Agendamento a
                JOIN Paciente pac ON a.id_pac = pac.id
                JOIN Tutor t ON pac.id_tutor = t.id  
                JOIN Pessoa p ON t.id_pessoa = p.id  
                JOIN Veterinario v ON a.id_vet = v.id
                JOIN Pessoa vpessoa ON v.id_pessoa = vpessoa.id
                ORDER BY a.data_agend DESC, a.hora_consulta DESC";

        $resultado = $conn->query($sql);
        $agendamentos = [];

        if ($resultado && $resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $agendamentos[] = $row;
            }
        }
        $conn->close();
        return $agendamentos;
    }

    public function buscarAgendamentoPorId(int $idAgendamento): ?array {
        $agendamento = new Agendamento();
        if ($agendamento->carregarAgendamento($idAgendamento)) {
            return [
                'id' => $agendamento->getId(),
                'data_agend' => $agendamento->getDataAgend(),
                'hora_consulta' => $agendamento->getHoraConsulta(),
                'tipo_servico' => $agendamento->getTipoServico(),
                'observacoes' => $agendamento->getObservacoes(),
                'status' => $agendamento->getStatus(),
                'id_vet' => $agendamento->getIdVet(),
                'id_pac' => $agendamento->getIdPac()
            ];
        }
        return null;
    }

    public function atualizarAgendamento(
        int $id,
        string $dataAgend,
        string $horaConsulta,
        string $tipoServico,
        string $observacoes,
        string $status,
        int $idVet,
        int $idPac
    ): bool {
        $agendamento = new Agendamento();
        $agendamento->setId($id);
        $agendamento->setDataAgend($dataAgend);
        $agendamento->setHoraConsulta($horaConsulta);
        $agendamento->setTipoServico($tipoServico);
        $agendamento->setObservacoes($observacoes);
        $agendamento->setStatus($status);
        $agendamento->setIdVet($idVet);
        $agendamento->setIdPac($idPac);

        return $agendamento->atualizar();
    }

   public function listarAgendamentosPorVeterinario(int $idVet): array {
    $con = new ConexaoBD();
    $conn = $con->conectar();

    $sql = "SELECT
        a.id,
        a.data_agend,
        a.hora_consulta,
        a.tipo_servico,
        a.observacoes,
        a.status,
        pac.nome AS nome_paciente,
        t_p.nome AS nome_tutor,
        vp.nome AS nome_veterinario
    FROM Agendamento a
    JOIN Paciente pac ON a.id_pac = pac.id
    JOIN Tutor t ON pac.id_tutor = t.id
    JOIN Pessoa t_p ON t.id_pessoa = t_p.id
    JOIN Veterinario v ON a.id_vet = v.id
    JOIN Pessoa vp ON v.id_pessoa = vp.id
    WHERE a.id_vet = ?
    ORDER BY a.data_agend ASC, a.hora_consulta ASC";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Erro na preparação da query: " . $conn->error);
        $conn->close();
        return [];
    }

    $stmt->bind_param("i", $idVet);
    $stmt->execute();

    $resultado = $stmt->get_result();
    $agendamentos = [];

    if ($resultado && $resultado->num_rows > 0) {
        while ($row = $resultado->fetch_assoc()) {
            $agendamentos[] = $row;
        }
    }

    $stmt->close();
    $conn->close();
    return $agendamentos;
    }

    public function excluirAgendamento(int $idAgendamento): bool {
        return $this->agendamentoModel->excluir($idAgendamento);
    }

    public function listarAgendamentosDoClienteLogado(): array {
        if (!isset($_SESSION['id_pessoa'])) {
            return [];
        }
        $idPessoaLogada = $_SESSION['id_pessoa'];

        $tutorController = new TutorController();
        $idTutor = $tutorController->garantirTutor($idPessoaLogada); //

        if ($idTutor > 0) {
            $agendamentoModel = new Agendamento();
            return $agendamentoModel->listarAgendamentosPorTutor($idTutor);
        }

        return [];
    }

}
?>