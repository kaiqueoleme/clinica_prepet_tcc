<?php
// View/listaPacientes.php

if (!isset($_SESSION)) {
    session_start();
}

// Redireciona se o usuário não estiver logado ou não for atendente
if (!isset($_SESSION['usuario_logado']) || $_SESSION['nivel_acesso'] != 2) {
    header("Location: ../View/loginFuncionario.php");
    exit();
}

require_once '../Controller/PacienteController.php'; // Inclui o Controller do Paciente

$nomeFuncionario = $_SESSION['usuario_logado'];

$pacienteController = new PacienteController(); // Instancia o Controller
$todosPacientes = $pacienteController->listarTodosPacientes(); // Busca todos os pacientes com seus tutores

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Pacientes - PrePet</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body class="w3-light-grey">

    <div class="w3-container w3-padding-64 w3-center">
        <div class="w3-card-4 w3-white w3-padding-large w3-round-large w3-display-middle" style="width:90%;max-width:900px;">
            <h2><i class="fa fa-paw"></i> Lista de Pacientes</h2>
            <p>Informações de todos os pets cadastrados.</p>

            <?php if (empty($todosPacientes)): ?>
                <div class="w3-panel w3-yellow w3-round-large w3-padding-16">
                    <h3>Aviso!</h3>
                    <p>Nenhum paciente encontrado.</p>
                </div>
            <?php else: ?>
                <div class="w3-responsive">
                    <table class="w3-table-all">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Espécie</th>
                                <th>Raça</th>
                                <th>Nascimento</th>
                                <th>Tutor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($todosPacientes as $paciente): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($paciente['id']); ?></td>
                                    <td><?php echo htmlspecialchars($paciente['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($paciente['especie']); ?></td>
                                    <td><?php echo htmlspecialchars($paciente['raca']); ?></td>
                                    <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($paciente['data_nasc']))); ?></td>
                                    <td><?php echo htmlspecialchars($paciente['nome_tutor']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <p style="margin-top: 20px;">
                <a href="../View/dashboardAtendente.php" class="w3-button w3-red w3-block w3-round-large">
                    <i class="fa fa-arrow-circle-left"></i> Voltar ao Dashboard
                </a>
            </p>
        </div>
    </div>

</body>
</html>