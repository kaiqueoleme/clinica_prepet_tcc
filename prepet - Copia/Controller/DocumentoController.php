<?php
// C:\xampp\htdocs\prepet\Controller\DocumentoController.php

// Inclui o Model Documento.php para interagir com o banco de dados
require_once __DIR__ . '/../Model/Documento.php';

class DocumentoController {

    /**
     * Registra um novo documento no sistema.
     * @param string $tipo Tipo do documento (ex: 'Exame', 'Atestado', 'Receita').
     * @param string $conteudo Conteúdo textual ou referência do documento.
     * @param int $idVet ID do veterinário que gerou o documento.
     * @param int $idPac ID do paciente ao qual o documento se refere.
     * @return bool True se o documento foi registrado com sucesso, false caso contrário.
     */
    public function registrarDocumento(
        string $tipo,
        string $conteudo,
        int $idVet,
        int $idPac
    ): bool {
        // Validações básicas (você pode adicionar mais complexidade aqui)
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

    /**
     * Busca um documento pelo seu ID.
     * @param int $id ID do documento a ser buscado.
     * @return array|null Um array associativo com os dados do documento, ou null se não encontrado.
     */
    public function buscarDocumentoPorId(int $id): ?array {
        if ($id <= 0) {
            error_log("Erro: ID de documento inválido para busca.");
            return null;
        }

        $documento = new Documento();
        if ($documento->buscarPorId($id)) {
            // Retorna os dados como um array para facilitar o uso na View
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

    /**
     * Lista todos os documentos registrados no sistema.
     * @return array Um array de arrays associativos, cada um representando um documento.
     */
    public function listarTodosDocumentos(): array {
        $documentoModel = new Documento();
        return $documentoModel->listarTodos();
    }

    /**
     * Lista documentos associados a um veterinário específico.
     * @param int $idVet ID do veterinário.
     * @return array Uma lista de documentos.
     */
    public function listarDocumentosPorVeterinario(int $idVet): array {
        if ($idVet <= 0) {
            error_log("Erro: ID de veterinário inválido para listar documentos.");
            return [];
        }
        $documentoModel = new Documento();
        return $documentoModel->listarPorVeterinario($idVet);
    }

    /**
     * Lista documentos associados a um paciente específico.
     * @param int $idPac ID do paciente.
     * @return array Uma lista de documentos.
     */
    public function listarDocumentosPorPaciente(int $idPac): array {
        if ($idPac <= 0) {
            error_log("Erro: ID de paciente inválido para listar documentos.");
            return [];
        }
        $documentoModel = new Documento();
        return $documentoModel->listarPorPaciente($idPac);
    }

    /**
     * Atualiza os dados de um documento existente.
     * @param int $id ID do documento a ser atualizado.
     * @param string $tipo Novo tipo do documento.
     * @param string $conteudo Novo conteúdo do documento.
     * @param int $idVet Novo ID do veterinário.
     * @param int $idPac Novo ID do paciente.
     * @return bool True se a atualização foi bem-sucedida, false caso contrário.
     */
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

    /**
     * Exclui um documento do sistema.
     * @param int $id ID do documento a ser excluído.
     * @return bool True se a exclusão foi bem-sucedida, false caso contrário.
     */
    public function excluirDocumento(int $id): bool {
        if ($id <= 0) {
            error_log("Erro: ID de documento inválido para exclusão.");
            return false;
        }

        $documento = new Documento();
        $documento->setId($id); // Define o ID para o método de exclusão
        return $documento->excluir();
    }
}
?>