<?php

// Inclui o Model Documento.php para interagir com o banco de dados
require_once __DIR__ . '/../Model/Documento.php';

class DocumentoController {

    public function registrarDocumento(
        string $tipo,
        string $conteudo,
        int $idVet,
        int $idPac
    ): bool {
        if (empty($tipo) || empty($conteudo) || $idVet <= 0 || $idPac <= 0) {
            error_log("Erro: Dados incompletos para registrar documento.");
            return false;
        }

        $documento = new Documento();
        $documento->setTipo($tipo);
        $documento->setConteudo($conteudo);
        $documento->setIdVet($idVet);
        $documento->setIdPac($idPac);

        return $documento->inserir();
    }

    public function buscarDocumentoPorId(int $id): ?array {
        if ($id <= 0) {
            error_log("Erro: ID de documento inválido para busca.");
            return null;
        }

        $documento = new Documento();
        if ($documento->buscarPorId($id)) {
            return [
                'id' => $documento->getId(),
                'tipo' => $documento->getTipo(),
                'conteudo' => $documento->getConteudo(),
                'id_vet' => $documento->getIdVet(),
                'id_pac' => $documento->getIdPac()
            ];
        }
        return null;
    }

    public function listarTodosDocumentos(): array {
        $documentoModel = new Documento();
        return $documentoModel->listarTodos();
    }

    public function listarDocumentosPorVeterinario(int $idVet): array {
        if ($idVet <= 0) {
            error_log("Erro: ID de veterinário inválido para listar documentos.");
            return [];
        }
        $documentoModel = new Documento();
        return $documentoModel->listarPorVeterinario($idVet);
    }

    public function listarDocumentosPorPaciente(int $idPac): array {
        if ($idPac <= 0) {
            error_log("Erro: ID de paciente inválido para listar documentos.");
            return [];
        }
        $documentoModel = new Documento();
        return $documentoModel->listarPorPaciente($idPac);
    }

    public function atualizarDocumento(
        int $id,
        string $tipo,
        string $conteudo,
        int $idVet,
        int $idPac
    ): bool {
        if ($id <= 0 || empty($tipo) || empty($conteudo) || $idVet <= 0 || $idPac <= 0) {
            error_log("Erro: Dados incompletos ou inválidos para atualizar documento.");
            return false;
        }

        $documento = new Documento();
        $documento->setId($id);
        $documento->setTipo($tipo);
        $documento->setConteudo($conteudo);
        $documento->setIdVet($idVet);
        $documento->setIdPac($idPac);

        return $documento->atualizar();
    }

    public function excluirDocumento(int $id): bool {
        if ($id <= 0) {
            error_log("Erro: ID de documento inválido para exclusão.");
            return false;
        }

        $documento = new Documento();
        $documento->setId($id);
        return $documento->excluir();
    }
}
?>