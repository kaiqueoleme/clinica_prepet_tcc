<?php
// View/editarAgendamento.php

if (!isset($_SESSION)) {
    session_start();
}

// Redireciona se o usuário não estiver logado ou não for atendente
if (!isset($_SESSION['usuario_logado']) || $_SESSION['nivel_acesso'] != 2) {
    header("Location: ../View/loginFuncionario.php");
    exit();
}

require_once '../Controller/AgendamentoController.php';
require_once '../Controller/PacienteController.php'; // Para listar pacientes no dropdown
require_once '../Model/Veterinario.php'; // Para listar veterinários no dropdown

$nomeFuncionario = $_SESSION['usuario_logado'];

$idAgendamento = $_GET['id'] ?? null; // Obtém o ID do agendamento da URL

$agendamentoParaEditar = null;
$todosPacientes = [];
$todosVeterinarios = [];

if ($idAgendamento) {
    $agendamentoController = new AgendamentoController();
    $agendamentoParaEditar = $agendamentoController->buscarAgendamentoPorId($idAgendamento);

    // Se o agendamento não for encontrado, redireciona de volta para o dashboard
    if (!$agendamentoParaEditar) {
        $_SESSION['mensagem'] = "Agendamento não encontrado.";
        header("Location: ../View/dashboardAtendente.php");
        exit();
    }

    // Carrega listas para os dropdowns (Pacientes e Veterinários)
    $pacienteController = new PacienteController();
    $todosPacientes = $pacienteController->listarTodosPacientes();

    $veterinarioModel = new Veterinario();
    $todosVeterinarios = $veterinarioModel->listarVeterinarios();

} else {
    // Se nenhum ID for fornecido na URL, redireciona
    $_SESSION['mensagem'] = "ID do agendamento não fornecido.";
    header("Location: ../View/dashboardAtendente.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Agendamento - PrePet</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: "Montserrat", sans-serif;
            background-image: linear-gradient(
                to right bottom,
                rgba(126,214,223, 0.5),
                rgba(22,160,133, 0.6)
            ),
            url('https://picsum.photos/id/1048/3016/1500');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .w3-card-4 {
            margin-top: 50px;
            margin-bottom: 50px;
        }
    </style>
</head>
<body class="w3-light-grey">

    <div class="w3-container w3-padding-64 w3-center">
        <div class="w3-card-4 w3-white w3-padding-large w3-round-large w3-display-middle" style="width:90%;max-width:600px;">
            <h2><i class="fa fa-pencil"></i> Editar Agendamento</h2>
            <p>Altere os detalhes do agendamento selecionado.</p>

            <?php
            if (isset($_SESSION['mensagem'])) {
                echo '<div class="w3-panel w3-green w3-round-large w3-padding-16">
                            <h3>Sucesso!</h3>
                            <p>' . htmlspecialchars($_SESSION['mensagem']) . '</p>
                        </div>';
                unset($_SESSION['mensagem']); // Limpa a mensagem após exibir
            }
            if (isset($_SESSION['erro'])) {
                echo '<div class="w3-panel w3-red w3-round-large w3-padding-16">
                            <h3>Erro!</h3>
                            <p>' . htmlspecialchars($_SESSION['erro']) . '</p>
                        </div>';
                unset($_SESSION['erro']); // Limpa o erro após exibir
            }
            ?>

            <form action="/prepet/Controller/Navegacao.php" method="post" class="w3-container w3-margin-top">
                <input type="hidden" name="id_agendamento" value="<?php echo htmlspecialchars($agendamentoParaEditar['id']); ?>">

                <p>
                    <label for="data_agend" class="w3-left">Data:</label>
                    <input type="date" id="data_agend" name="data_agend" class="w3-input w3-border w3-round-large"
                           value="<?php echo htmlspecialchars($agendamentoParaEditar['data_agend']); ?>" required>
                </p>

                <p>
                    <label for="hora_consulta" class="w3-left">Hora:</label>
                    <input type="time" id="hora_consulta" name="hora_consulta" class="w3-input w3-border w3-round-large"
                           value="<?php echo htmlspecialchars($agendamentoParaEditar['hora_consulta']); ?>" required>
                </p>

                <p>
                    <label for="id_pac" class="w3-left">Paciente:</label>
                    <select id="id_pac" name="id_pac" class="w3-select w3-border w3-round-large" required>
                        <option value="" disabled>Selecione o Paciente</option>
                        <?php foreach ($todosPacientes as $paciente): ?>
                            <option value="<?php echo htmlspecialchars($paciente['id']); ?>"
                                <?php echo ($paciente['id'] == $agendamentoParaEditar['id_pac']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($paciente['nome']); ?> (Tutor: <?php echo htmlspecialchars($paciente['nome_tutor']); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </p>

                <p>
                    <label for="id_vet" class="w3-left">Veterinário:</label>
                    <select id="id_vet" name="id_vet" class="w3-select w3-border w3-round-large" required>
                        <option value="" disabled>Selecione o Veterinário</option>
                        <?php foreach ($todosVeterinarios as $vet): ?>
                            <option value="<?php echo htmlspecialchars($vet['id']); ?>"
                                <?php echo ($vet['id'] == $agendamentoParaEditar['id_vet']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($vet['nome']); ?> (CRM: <?php echo htmlspecialchars($vet['crmv']); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </p>

                <p>
                    <label for="tipo_servico" class="w3-left">Tipo de Serviço:</label>
                    <input type="text" id="tipo_servico" name="tipo_servico" class="w3-input w3-border w3-round-large"
                           value="<?php echo htmlspecialchars($agendamentoParaEditar['tipo_servico']); ?>" required>
                </p>

                <p>
                    <label for="observacoes" class="w3-left">Observações:</label>
                    <textarea id="observacoes" name="observacoes" class="w3-input w3-border w3-round-large" rows="4"><?php echo htmlspecialchars($agendamentoParaEditar['observacoes'] ?? ''); ?></textarea>
                </p>

                <p>
                    <label for="status" class="w3-left">Status:</label>
                    <select id="status" name="status" class="w3-select w3-border w3-round-large" required>
                        <option value="Agendado" <?php echo ($agendamentoParaEditar['status'] == 'Agendado') ? 'selected' : ''; ?>>Agendado</option>
                        <option value="Confirmado" <?php echo ($agendamentoParaEditar['status'] == 'Confirmado') ? 'selected' : ''; ?>>Confirmado</option>
                        <option value="Realizado" <?php echo ($agendamentoParaEditar['status'] == 'Realizado') ? 'selected' : ''; ?>>Realizado</option>
                        <option value="Cancelado" <?php echo ($agendamentoParaEditar['status'] == 'Cancelado') ? 'selected' : ''; ?>>Cancelado</option>
                        <option value="Remarcado" <?php echo ($agendamentoParaEditar['status'] == 'Remarcado') ? 'selected' : ''; ?>>Remarcado</option>
                    </select>
                </p>

                <div class="w3-row w3-margin-top">
                    <div class="w3-half w3-container">
                        <button type="submit" name="btnAtualizarAgendamento" class="w3-button w3-green w3-block w3-round-large">
                            <i class="fa fa-check"></i> Confirmar Edição
                        </button>
                    </div>
                    <div class="w3-half w3-container">
                        <a href="dashboardAtendente.php" class="w3-button w3-red w3-block w3-round-large">
                            <i class="fa fa-times"></i> Cancelar
                        </a>
                    </div>
                </div>

            </form>

        </div>
    </div>

</body>
</html>