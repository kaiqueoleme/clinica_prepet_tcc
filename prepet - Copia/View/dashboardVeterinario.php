<?php
// View/dashboardVeterinario.php

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

// Inclui o controller de Pacientes
require_once '../Controller/PacienteController.php';

$pacienteController = new PacienteController();
$todosPacientes = $pacienteController->listarTodosPacientes();

// Mensagens de feedback (sucesso/erro)
$mensagem = $_SESSION['mensagem'] ?? '';
$erro = $_SESSION['erro'] ?? '';
unset($_SESSION['mensagem']); // Limpa a mensagem após exibir
unset($_SESSION['erro']);     // Limpa o erro após exibir

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard do Veterinário - PrePet</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        html, body, h1, h2, h3, h4, h5, h6 {font-family: "Raleway", sans-serif}
        .w3-sidebar {
            z-index: 3;
            width: 250px;
            background-color: #2c3e50; /* Cor escura para a sidebar */
            color: white;
        }
        .w3-main { margin-left: 250px; }
        .w3-bar-item.w3-button {
            color: white;
            padding: 12px 16px;
            text-align: left;
        }
        .w3-bar-item.w3-button:hover {
            background-color: #34495e; /* Tom mais claro no hover */
            color: white;
        }
        .w3-dropdown-content {
            background-color: #34495e;
        }
        .w3-dropdown-content a {
            color: white;
            padding: 8px 16px;
        }
        .w3-dropdown-content a:hover {
            background-color: #4a627a;
        }
        .w3-topbar { border-color: #1abc9c !important; } /* Cor verde para a barra superior */
        .w3-container.header {
            background-color: #1abc9c; /* Cor verde para o cabeçalho */
            color: white;
        }
        .w3-table-all thead tr {
            background-color: #1abc9c;
            color: white;
        }
        .w3-table-all th, .w3-table-all td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .w3-table-all tr:nth-child(even) {background-color: #f2f2f2;}
        .w3-table-all tr:hover {background-color: #ddd;}
        .w3-button.w3-green { background-color: #2ecc71 !important; }
        .w3-button.w3-green:hover { background-color: #27ae60 !important; }

        /* Ajustes para telas menores */
        @media (max-width: 992px) {
            .w3-sidebar {
                display: none;
            }
            .w3-main {
                margin-left: 0;
            }
        }
    </style>
</head>
<body class="w3-light-grey">

    <nav class="w3-sidebar w3-collapse w3-animate-left" style="z-index:3;" id="mySidebar"><br>
            <div class="w3-col s4">
                <img src="https://www.w3schools.com/w3images/avatar2.png" class="w3-circle w3-margin-right" style="width:46px">
            </div>
            <div class="w3-col s8 w3-bar">
                <span>Bem-vindo(a), <strong><?php echo htmlspecialchars($nomeVeterinario); ?></strong></span><br>
                <a href="#" class="w3-bar-item w3-button"><i class="fa fa-envelope"></i></a>
                <a href="#" class="w3-bar-item w3-button"><i class="fa fa-user"></i></a>
                <a href="#" class="w3-bar-item w3-button"><i class="fa fa-cog"></i></a>
            </div>
        </div>
        <hr>
        <div class="w3-container">
            <h5>Dashboard Veterinário</h5>
        </div>
        <div class="w3-bar-block">
            <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  Fechar Menu</a>
            <a href="dashboardVeterinario.php" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa-users fa-fw"></i>  Pacientes</a>
            <a href="cadastrarFuncionario.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-user-plus fa-fw"></i>  Cadastrar Funcionário</a>
            <a href="cadastrarTutor.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-user-circle-o fa-fw"></i>  Cadastrar Tutor</a>
            <a href="cadastrarPaciente.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-paw fa-fw"></i>  Cadastrar Paciente</a>
            <a href="../Controller/Navegacao.php?acao=logout" class="w3-bar-item w3-button w3-padding"><i class="fa fa-sign-out fa-fw"></i>  Sair</a>
    </nav>

    <div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

    <div class="w3-main">

        <header class="w3-container header" style="padding-top:22px">
            <h5><b><i class="fa fa-dashboard"></i> Dashboard do Veterinário</b></h5>
        </header>

        <div class="w3-container w3-padding-large w3-margin-bottom">
            <h4>Meus Pacientes</h4>

            <?php if ($mensagem): ?>
                <div class="w3-panel w3-green w3-display-container">
                    <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                    <p><?php echo htmlspecialchars($mensagem); ?></p>
                </div>
            <?php endif; ?>

            <?php if ($erro): ?>
                <div class="w3-panel w3-red w3-display-container">
                    <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                    <p><?php echo htmlspecialchars($erro); ?></p>
                </div>
            <?php endif; ?>

            <?php if (empty($todosPacientes)): ?>
                <p>Nenhum paciente cadastrado na clínica.</p>
            <?php else: ?>
                <table class="w3-table w3-striped w3-bordered w3-hoverable w3-table-all">
                    <thead>
                        <tr class="w3-light-grey">
                            <th>ID</th>
                            <th>Nome do Paciente</th>
                            <th>Espécie</th>
                            <th>Raça</th>
                            <th>Data Nasc.</th>
                            <th>Tutor</th>
                            <th>Ações</th>
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
                                <td>
                                    <a href="registrarProcedimento.php?id_paciente=<?php echo $paciente['id']; ?>" class="w3-padding w3-button w3-small w3-half w3-green w3-round-large">
                                        <i class="fa fa-stethoscope"></i> Registrar Procedimento</a>
                                    <a href="registrarDocumento.php?id_paciente=<?php echo htmlspecialchars($paciente['id']); ?>" class="w3-padding w3-button w3-half w3-small w3-blue w3-round-large">
                                        <i class="fa fa-file-text-o"></i> Documento</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        </div>

    <script>
    // Script para abrir e fechar a sidebar em telas pequenas
    function w3_open() {
        document.getElementById("mySidebar").style.display = "block";
        document.getElementById("myOverlay").style.display = "block";
    }

    function w3_close() {
        document.getElementById("mySidebar").style.display = "none";
        document.getElementById("myOverlay").style.display = "none";
    }

    // Fecha a sidebar em telas maiores (opcional, mas comum para dashboards)
    window.onload = function() {
        if (window.innerWidth >= 993) { // Largura W3.CSS para "large"
            document.getElementById("mySidebar").style.display = "block";
            document.getElementById("myOverlay").style.display = "none";
        }
    }
    </script>

</body>
</html>