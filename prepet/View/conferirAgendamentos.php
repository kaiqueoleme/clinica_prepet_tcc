<?php
// View/conferirAgendamentos.php

if (!isset($_SESSION)) {
    session_start();
}

// Redireciona se não for cliente logado ou se a sessão estiver incompleta
if (!isset($_SESSION['usuario_logado']) || !isset($_SESSION['id_pessoa']) || 
    (isset($_SESSION['id_acesso']) && $_SESSION['id_acesso'] != 1) ) {
    header("Location: ../View/loginCliente.php");
    exit();
}

require_once '../Controller/AgendamentoController.php';
// TutorController é chamado dentro de AgendamentoController para obter o idTutor,
// e AgendamentoController já inclui TutorController.php.

$agendamentoController = new AgendamentoController();
$agendamentos = $agendamentoController->listarAgendamentosDoClienteLogado();

$nomeCliente = "Cliente"; 
if(isset($_SESSION['usuario_logado'])){
    $nomeCliente = htmlspecialchars($_SESSION['usuario_logado']);
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Agendamentos - PrePet</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body, h1, h2, h3, h4, h5, h6 { font-family: "Montserrat", sans-serif; }
        .w3-sidebar { z-index: 3; width: 250px; background-color: #f1f1f1; position: fixed; height: 100%; overflow: auto;}
        .w3-main { margin-left: 250px; }
        .w3-table-all th, .w3-table-all td { padding: 12px 8px; text-align: left; vertical-align: middle; border: 1px solid #ddd;}
        .w3-table-all th { background-color: #007bff; color: white; }
        .w3-container.header-view { background-color: #007bff; color: white; padding-top: 10px; padding-bottom:10px; margin-bottom:20px;}
        .w3-button.w3-blue { background-color: #007bff !important; }
        .w3-button.w3-red { background-color: #dc3545 !important; }
        .w3-bar-item.w3-blue { background-color: #007bff !important; color:white !important;}

        @media (max-width: 992px) {
            .w3-main { margin-left: 0; }
            .w3-sidebar { display: none; width:100%;}
            .w3-top.w3-hide-large {display: block !important;}
        }
        .w3-top.w3-hide-large { position: sticky; top:0; z-index:100; }

    </style>
</head>
<body class="w3-light-grey">

<nav class="w3-sidebar w3-bar-block w3-collapse w3-white w3-animate-left" id="mySidebar">
    <a href="javascript:void(0)" onclick="w3_close()" title="Close Sidemenu"
    class="w3-bar-item w3-button w3-padding w3-hide-large w3-large w3-text-teal">
        <i class="fa fa-remove fa-fw"></i>  Fechar Menu
    </a>
    <div class="w3-center w3-padding-large">
        <img src="https://www.w3schools.com/w3images/avatar2.png" class="w3-circle w3-margin-right" style="width:80px; margin-bottom:10px;">
        <h4>Olá, <?php echo $nomeCliente; ?>!</h4>
         <a href="alterarDadosUsuario.php" class="w3-button w3-small w3-round-large w3-light-blue w3-text-white" style="text-decoration:none; margin-top:5px;">Alterar Cadastro</a>
    </div>
    <hr>
    <a href="dashboardCliente.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-dashboard fa-fw"></i>  Dashboard</a>
    <a href="cadastrarPaciente.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-plus fa-fw"></i>  Cadastrar Pet</a>
    <a href="conferirAgendamentos.php" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa-calendar-check-o fa-fw"></i>  Meus Agendamentos</a>
    <a href="historicoPets.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-history fa-fw"></i>  Histórico dos Pets</a>
    <form action="/prepet/Controller/Navegacao.php" method="post" style="margin:0;">
        <button type="submit" name="btnSair" class="w3-bar-item w3-button w3-padding"><i class="fa fa-sign-out fa-fw"></i>  Sair</button>
    </form>
</nav>

<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" title="Close Sidemenu" id="myOverlay" style="cursor:pointer;"></div>

<div class="w3-main">

    <div class="w3-bar w3-top w3-blue w3-hide-large">
        <button class="w3-bar-item w3-button w3-xlarge" onclick="w3_open()">☰</button>
        <span class="w3-bar-item w3-xlarge">PrePet</span>
    </div>

    <div class="w3-container" style="padding-top:16px;"> 
        <div class="w3-container header-view w3-round-large">
             <h2><i class="fa fa-calendar-check-o"></i> Meus Agendamentos</h2>
        </div>

        <?php if (!empty($agendamentos)): ?>
            <div class="w3-responsive w3-card-4 w3-round-large" style="margin-top:20px;">
                <table class="w3-table-all w3-hoverable">
                    <thead>
                        <tr> <th>Data</th>
                            <th>Hora</th>
                            <th>Pet</th>
                            <th>Veterinário</th>
                            <th>Serviço</th>
                            <th>Status</th>
                            <th>Observações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($agendamentos as $ag): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($ag['data_agend']))); ?></td>
                                <td><?php echo htmlspecialchars(date('H:i', strtotime($ag['hora_consulta']))); ?></td>
                                <td><?php echo htmlspecialchars($ag['nome_paciente']); ?></td>
                                <td><?php echo htmlspecialchars($ag['nome_veterinario']); ?></td>
                                <td><?php echo htmlspecialchars($ag['tipo_servico']); ?></td>
                                <td><?php echo htmlspecialchars($ag['status']); ?></td>
                                <td><?php echo htmlspecialchars(!empty($ag['observacoes']) ? $ag['observacoes'] : '-'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="w3-panel w3-pale-yellow w3-leftbar w3-border-yellow w3-padding-16 w3-round-large">
                <p><i class="fa fa-info-circle"></i> Você não possui agendamentos registrados no momento.</p>
            </div>
        <?php endif; ?>

        <p style="margin-top: 20px;">
            <a href="dashboardCliente.php" class="w3-button w3-red w3-round-large">
                <i class="fa fa-arrow-left"></i> Voltar ao Dashboard
            </a>
        </p>
    </div>

    <footer class="w3-container w3-padding-16 w3-light-grey w3-center" style="margin-top:30px;">
        <p>© <?php echo date("Y"); ?> PrePet. Todos os direitos reservados.</p>
    </footer>

</div> <script>
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