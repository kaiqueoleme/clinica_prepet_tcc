<?php

require_once __DIR__ . '/../Model/Procedimento.php';

class ProcedimentoController {

    public function registrarProcedimento(
        string $tipo,
        string $dataProcedimento,
        string $resultado,
        string $statusPaciente,
        string $diagnostico,
        int $idVet,
        int $idPac
    ): bool {
        $procedimento = new Procedimento();
        $procedimento->setTipo($tipo);
        $procedimento->setDataProcedimento($dataProcedimento);
        $procedimento->setResultado($resultado);
        $procedimento->setStatusPaciente($statusPaciente);
        $procedimento->setDiagnostico($diagnostico);
        $procedimento->setIdVet($idVet);
        $procedimento->setIdPac($idPac);

        return $procedimento->inserir();
    }

    public function buscarProcedimentoPorId(int $id): ?array {
        $procedimento = new Procedimento();
        return $procedimento->buscarPorId($id);
    }

    public function listarProcedimentosPaciente(int $idPac): array {
        $procedimento = new Procedimento();
        return $procedimento->listarProcedimentosPorPaciente($idPac);
    }
}
?>