<?php
// View/registrarProcedimento.php

if (!isset($_SESSION)) {
    session_start();
}

// Redireciona se o usuário não estiver logado ou não for veterinário (Nível 3)
if (!isset($_SESSION['usuario_logado']) || $_SESSION['nivel_acesso'] != 3) {
    header("Location: ../View/loginFuncionario.php");
    exit();
}

// Recupera informações do veterinário logado da sessão
$nomeVeterinario = $_SESSION['usuario_logado'];
$idVeterinario = $_SESSION['id_pessoa']; // ID da pessoa/funcionário que é o veterinário

// Inclui o controller necessário para pacientes e procedimentos
require_once '../Controller/PacienteController.php';
require_once '../Controller/ProcedimentoController.php'; // Para registrar o procedimento

$pacienteController = new PacienteController();
$procedimentoController = new ProcedimentoController();

$paciente = null;
$idPaciente = $_GET['id_paciente'] ?? null; // Pega o ID do paciente da URL

// Se um ID de paciente foi passado, tente carregar os dados do paciente
if ($idPaciente) {
    $paciente = $pacienteController->buscarPaciente($idPaciente);
    if (!$paciente) {
        $_SESSION['erro'] = "Paciente não encontrado.";
        header("Location: dashboardVeterinario.php");
        exit();
    }
} else {
    $_SESSION['erro'] = "ID do paciente não fornecido.";
    header("Location: dashboardVeterinario.php");
    exit();
}

// Mensagens de feedback (sucesso/erro)
$mensagem = $_SESSION['mensagem'] ?? '';
$erro = $_SESSION['erro'] ?? '';
unset($_SESSION['mensagem']);
unset($_SESSION['erro']);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Procedimento - PrePet</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .w3-container.header {
            background-color: #1abc9c;
            color: white;
            padding-top: 22px;
            padding-bottom: 22px;
        }
        body { font-family: "Raleway", sans-serif; }
        .w3-card-4 { margin-top: 50px; margin-bottom: 50px; }
        .w3-button.w3-green { background-color: #2ecc71 !important; }
        .w3-button.w3-green:hover { background-color: #27ae60 !important; }
        .w3-button.w3-red { background-color: #e74c3c !important; }
        .w3-button.w3-red:hover { background-color: #c0392b !important; }
        .w3-bar-item.w3-button {
            color: white;
            padding: 12px 16px;
            text-align: left;
        }
        .w3-bar-item.w3-button:hover {
            background-color: #34495e;
            color: white;
        }
    </style>
</head>
<body class="w3-light-grey">
    
    <div class="w3-main">

        <header class="w3-container header" style="padding-top:22px">
            <h5><b><i class="fa fa-stethoscope"></i> Registrar Procedimento para Paciente</b></h5>
        </header>

        <div class="w3-container w3-padding-large">
            <div class="w3-card-4 w3-white w3-margin-bottom">
                <div class="w3-container w3-green">
                    <h2>Dados do Procedimento</h2>
                </div>
                <form class="w3-container w3-padding" action="../Controller/Navegacao.php" method="POST">
                    <p>
                        <label>Paciente:</label>
                        <input class="w3-input w3-border" type="text" value="<?php echo htmlspecialchars($paciente->getNome()); ?> (ID: <?php echo htmlspecialchars($paciente->getId()); ?>)" readonly>
                        <input type="hidden" name="id_pac" value="<?php echo htmlspecialchars($paciente->getId()); ?>">
                        <input type="hidden" name="id_vet" value="<?php echo htmlspecialchars($idVeterinario); ?>">
                    </p>

                    <p>
                        <label>Tipo de Procedimento:</label>
                        <input class="w3-input w3-border" type="text" name="tipo" required>
                    </p>
                    <p>
                        <label>Data do Procedimento:</label>
                        <input class="w3-input w3-border" type="date" name="data_procedimento" value="<?php echo date('Y-m-d'); ?>" required>
                    </p>
                    <p>
                        <label>Resultado:</label>
                        <textarea class="w3-input w3-border" name="resultado" rows="4" required></textarea>
                    </p>
                    <p>
                        <label>Status do Paciente (após procedimento):</label>
                        <input class="w3-input w3-border" type="text" name="status_paciente" required>
                    </p>
                    <p>
                        <label>Diagnóstico:</label>
                        <textarea class="w3-input w3-border" name="diagnostico" rows="4" required></textarea>
                    </p>
                    <p>
                        <button class="w3-button w3-green w3-round-large w3-margin-bottom" type="submit" name="btnRegistrarProcedimentoPaciente"><i class="fa fa-save"></i> Registrar Procedimento</button>
                        <a href="dashboardVeterinario.php" class="w3-button w3-red w3-round-large w3-margin-bottom"><i class="fa fa-arrow-left"></i> Voltar</a>
                    </p>
                </form>
            </div>
        </div>

    </div>

</body>
</html>