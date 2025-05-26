<?php
// View/dashboardCliente.php

if (!isset($_SESSION)) {
    session_start();
}

// Redireciona se o usuário não estiver logado
if (!isset($_SESSION['usuario_logado']) || !isset($_SESSION['id_pessoa'])) {
    header("Location: ../View/loginCliente.php");
    exit();
}

require_once '../Model/Pessoa.php';
require_once '../Model/Tutor.php'; // Inclui o Model Tutor
require_once '../Model/Paciente.php'; // Inclui o Model Paciente
require_once '../Controller/PessoaController.php';
require_once '../Controller/TutorController.php'; // Inclui o Controller Tutor
require_once '../Controller/PacienteController.php'; // Inclui o Controller Paciente

$nomeCliente = "Cliente";
$idPessoaLogada = $_SESSION['id_pessoa'];
$idTutorLogado = 0; // Inicializa com 0

// Carregar o nome da pessoa logada
$pessoa = new Pessoa();
if ($pessoa->carregarPessoa($idPessoaLogada)) {
    $nomeCliente = $pessoa->getNome();
}

// Obter o ID do Tutor para o cliente logado
$tutorController = new TutorController();
$idTutorLogado = $tutorController->garantirTutor($idPessoaLogada); // Garante e retorna o ID do tutor

$pacientes = []; // Inicializa a lista de pacientes
if ($idTutorLogado > 0) {
    $pacienteController = new PacienteController();
    $pacientes = $pacienteController->listarPacientes($idTutorLogado);
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard do Cliente - PrePet</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/711de5e590.js" crossorigin="anonymous"></script>
    <style>
        body, h1, h2, h3, h4, h5, h6 { font-family: "Montserrat", sans-serif }
        .w3-sidebar { z-index: 3; width: 250px; background-color: #f1f1f1; }
        .w3-main { margin-left: 250px; }
        .w3-topbar { position: fixed; top: 0; width: calc(100% - 250px); left: 250px; }
        @media (max-width: 600px) {
            .w3-main { margin-left: 0; }
            .w3-sidebar { display: none; }
            .w3-topbar { width: 100%; left: 0; }
        }
        .w3-table-all { margin-top: 20px; }
        .w3-table-all th, .w3-table-all td { padding: 12px 8px; text-align: left; vertical-align: middle; }
    </style>
</head>
<body class="w3-light-grey">

<nav class="w3-sidebar w3-bar-block w3-collapse w3-white w3-animate-left" id="mySidebar">
    <a href="javascript:void(0)" onclick="w3_close()" title="Close Sidemenu" 
    class="w3-bar-item w3-button w3-padding w3-hide-large w3-large">
        <i class="fa fa-remove fa-fw"></i>  Fechar
    </a>
    <div class="w3-center w3-padding-large">
        <img src="https://www.w3schools.com/w3images/avatar2.png" class="w3-circle w3-margin-right" style="width:100px">
        <h4>Olá, <?php echo htmlspecialchars($nomeCliente); ?>!</h4>
    </div>
    <hr>
    <a href="#" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa-dashboard fa-fw"></i>  Dashboard</a>
    
    <form action="/prepet/View/cadastrarPaciente.php" method="get">
        <button type="submit" name="btnNovoPaciente" class="w3-bar-item w3-button w3-padding"><i class="fa fa-plus fa-fw"></i>  Cadastrar Paciente</button>
    </form>

    <form action="/prepet/Controller/Navegacao.php" method="post">
        <button type="submit" name="btnSair" class="w3-bar-item w3-button w3-padding"><i class="fa fa-sign-out fa-fw"></i>  Sair</button>
    </form>
</nav>

<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" title="Close Sidemenu" id="myOverlay"></div>

<div class="w3-main" style="margin-left:250px">

    <header class="w3-container w3-top w3-blue w3-hide-large w3-xlarge w3-padding">
        <a href="javascript:void(0)" class="w3-button w3-blue w3-margin-right" onclick="w3_open()">☰</a>
        <span>PrePet Dashboard</span>
    </header>

    <div class="w3-container w3-padding-large">
        <h2 class="w3-text-grey w3-padding-16">Seus Pets</h2>

        <?php if (!empty($pacientes)): ?>
            <table class="w3-table-all w3-hoverable w3-card-4">
                <thead>
                    <tr class="w3-blue">
                        <th>Nome</th>
                        <th>Espécie</th>
                        <th>Raça</th>
                        <th>Data de Nascimento</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pacientes as $paciente): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($paciente['nome']); ?></td>
                            <td><?php echo htmlspecialchars($paciente['especie']); ?></td>
                            <td><?php echo htmlspecialchars($paciente['raca']); ?></td>
                            <td><?php echo htmlspecialchars($paciente['data_nasc']); ?></td>
                            <td>
                                <form action="/prepet/View/editarPaciente.php" method="get" style="display:inline-block;">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($paciente['id']); ?>">
                                    <button type="submit" class="w3-button w3-small w3-teal w3-round-large">
                                        <i class="fa fa-edit"></i> Editar
                                    </button>
                                </form>
                                <form action="/prepet/Controller/Navegacao.php" method="post" style="display:inline-block;" onsubmit="return confirm('Tem certeza que deseja excluir o paciente <?php echo htmlspecialchars($paciente['nome']); ?>?');">
                                    <input type="hidden" name="idPaciente" value="<?php echo htmlspecialchars($paciente['id']); ?>">
                                    <button type="submit" name="btnExcluirPaciente" class="w3-button w3-small w3-red w3-round-large">
                                        <i class="fa fa-trash"></i> Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="w3-panel w3-pale-blue w3-leftbar w3-border-blue">
                <p>Você ainda não tem pacientes cadastrados. <a href="cadastrarPaciente.php">Cadastre um agora!</a></p>
            </div>
        <?php endif; ?>

    </div>

    <footer class="w3-container w3-padding w3-light-grey w3-center" style="margin-top:auto;">
        <p>© <?php echo date("Y"); ?> PrePet. Todos os direitos reservados.</p>
    </footer>

</div>

<script>
    // Script para abrir e fechar a sidebar em telas pequenas
    var mySidebar = document.getElementById("mySidebar");
    var overlayBg = document.getElementById("myOverlay");

    function w3_open() {
        if (mySidebar.style.display === 'block') {
            mySidebar.style.display = 'none';
            overlayBg.style.display = "none";
        } else {
            mySidebar.style.display = 'block';
            overlayBg.style.display = "block";
        }
    }

    function w3_close() {
        mySidebar.style.display = "none";
        overlayBg.style.display = "none";
    }
</script>

</body>
</html>