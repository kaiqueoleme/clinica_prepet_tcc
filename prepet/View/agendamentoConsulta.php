<?php
// View/agendarConsulta.php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['usuario_logado']) || $_SESSION['nivel_acesso'] != 2) {
    header("Location: ../View/loginFuncionario.php");
    exit();
}

require_once '../Model/Paciente.php';
require_once '../Model/Veterinario.php';
require_once '../Controller/PacienteController.php';

$nomeFuncionario = $_SESSION['usuario_logado'];

$pacienteController = new PacienteController();
$veterinarioModel = new Veterinario(); 
$todosPacientes = $pacienteController->listarTodosPacientes();

$veterinarios = $veterinarioModel->listarVeterinarios();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Atendimento - PrePet</title>
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
            background-attachment: fixed;
        }
        .w3-card-4 {
            margin-top: 50px;
            margin-bottom: 50px;
        }
        input[type="text"], input[type="date"], input[type="time"], select, textarea {
            width: 100%;
            padding: 8px;
            margin: 6px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
    </style>
</head>
<body class="w3-light-grey">

    <div class="w3-container w3-padding-64 w3-center">
        <?php if (isset($_SESSION['mensagem_sucesso'])): ?>
            <div class="w3-panel w3-green w3-round-large w3-padding-16">
                <h3>Sucesso!</h3>
                <p><?php echo $_SESSION['mensagem_sucesso']; ?></p>
            </div>
            <?php unset($_SESSION['mensagem_sucesso']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['mensagem_erro'])): ?>
            <div class="w3-panel w3-red w3-round-large w3-padding-16">
                <h3>Erro!</h3>
                <p><?php echo $_SESSION['mensagem_erro']; ?></p>
            </div>
            <?php unset($_SESSION['mensagem_erro']); ?>
        <?php endif; ?>

        <div class="w3-card-4 w3-white w3-padding-large w3-round-large w3-display-middle" style="width:90%;max-width:500px;">
            <h2><i class="fa fa-calendar-plus-o"></i> Agendar Novo Atendimento</h2>
            <p>Preencha os detalhes para agendar um atendimento.</p>

            <form action="../Controller/Navegacao.php" method="post">
                <p>
                    <label for="id_paciente" class="w3-left">Paciente:</label>
                    <select id="id_paciente" name="id_pac" required>
                        <option value="" disabled selected>Selecione o Paciente</option>
                        <?php foreach ($todosPacientes as $paciente): ?>
                            <option value="<?php echo htmlspecialchars($paciente['id']); ?>">
                                <?php echo htmlspecialchars($paciente['nome']); ?> (Tutor: <?php echo htmlspecialchars($paciente['nome_tutor']); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </p>

                <p>
                    <label for="id_veterinario" class="w3-left">Veterinário:</label>
                    <select id="id_veterinario" name="id_vet" required>
                        <option value="" disabled selected>Selecione o Veterinário</option>
                        <?php foreach ($veterinarios as $vet): ?>
                            <option value="<?php echo htmlspecialchars($vet['id']); ?>">
                                <?php echo htmlspecialchars($vet['nome']); ?> (CRM: <?php echo htmlspecialchars($vet['crmv']); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </p>

                <p>
                    <label for="data_agend" class="w3-left">Data do Atendimento:</label>
                    <input type="date" id="data_agend" name="data_agend" required>
                </p>

                <p>
                    <label for="hora_consulta" class="w3-left">Hora do Atendimento:</label>
                    <input type="time" id="hora_consulta" name="hora_consulta" required>
                </p>

                <p>
                    <label for="tipo_servico" class="w3-left">Tipo de Serviço:</label>
                    <select id="tipo_servico" name="tipo_servico" required>
                        <option value="" disabled selected>Selecione o Tipo de Serviço</option>
                        <option value="Consulta Rotina">Consulta de Rotina</option>
                        <option value="Vacinação">Vacinação</option>
                        <option value="Exame">Exame</option>
                        <option value="Cirurgia">Cirurgia</option>
                        <option value="Retorno">Retorno</option>
                        <option value="Outro">Outro</option>
                    </select>
                </p>

                <p>
                    <label for="observacoes" class="w3-left">Observações (opcional):</label>
                    <textarea id="observacoes" name="observacoes" rows="4" placeholder="Detalhes adicionais sobre o atendimento..."></textarea>
                </p>

                <p>
                    <button  name="btnAgendarConsulta" class="w3-button w3-green w3-block w3-round-large">
                        <i class="fa fa-check-circle"></i> Agendar Atendimento
                    </button>
                </p>
            </form>
            <p>
                <a href="../View/dashboardAtendente.php" class="w3-button w3-red w3-block w3-round-large">
                    <i class="fa fa-arrow-circle-left"></i> Voltar ao Dashboard
                </a>
            </p>
        </div>
    </div>

</body>
</html>