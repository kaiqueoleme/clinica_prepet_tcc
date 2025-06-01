<?php
// View/historicoPets.php

if (!isset($_SESSION)) {
    session_start();
}

// Redireciona se não for cliente logado ou se a sessão estiver incompleta
if (!isset($_SESSION['usuario_logado']) || !isset($_SESSION['id_pessoa']) || 
    (isset($_SESSION['id_acesso']) && $_SESSION['id_acesso'] != 1) ) {
    header("Location: ../View/loginCliente.php");
    exit();
}

require_once '../Controller/TutorController.php';
require_once '../Controller/PacienteController.php';
require_once '../Controller/ProcedimentoController.php';
require_once '../Controller/DocumentoController.php';

$idPessoaLogada = $_SESSION['id_pessoa'];
$nomeCliente = htmlspecialchars($_SESSION['usuario_logado']);

$tutorController = new TutorController();
$pacienteController = new PacienteController();
$procedimentoController = new ProcedimentoController();
$documentoController = new DocumentoController();

$idTutor = $tutorController->garantirTutor($idPessoaLogada);

$idPacienteSelecionado = null;
$pacienteSelecionado = null;
$petsDoCliente = [];
$procedimentos = [];
$documentos = [];

if (isset($_GET['id_paciente']) && is_numeric($_GET['id_paciente'])) {
    $idPacienteSelecionado = (int)$_GET['id_paciente'];
    $pacienteObj = $pacienteController->buscarPaciente($idPacienteSelecionado);

    // Validação: Verifica se o paciente pertence ao tutor logado
    if ($pacienteObj && $pacienteObj->getIdTutor() == $idTutor) {
        $pacienteSelecionado = [
            'id' => $pacienteObj->getId(),
            'nome' => $pacienteObj->getNome(),
            'especie' => $pacienteObj->getEspecie(),
            'raca' => $pacienteObj->getRaca(),
            'data_nasc' => $pacienteObj->getDataNasc()
        ];
        $procedimentos = $procedimentoController->listarProcedimentosPaciente($idPacienteSelecionado);
        $documentos = $documentoController->listarDocumentosPorPaciente($idPacienteSelecionado);
    } else {
        $idPacienteSelecionado = null; 
    }
}

// Se nenhum pet específico foi selecionado (ou se o selecionado não for válido), lista os pets do cliente
if ($idTutor > 0 && !$pacienteSelecionado) {
    $petsDoCliente = $pacienteController->listarPacientes($idTutor);
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico dos Pets - PrePet</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body, h1, h2, h3, h4, h5, h6 { font-family: "Montserrat", sans-serif; }
        .w3-sidebar { z-index: 3; width: 250px; background-color: #f1f1f1; position: fixed; height: 100%; overflow: auto;}
        .w3-main { margin-left: 250px; }
        .w3-table-all th, .w3-table-all td { padding: 10px 8px; text-align: left; vertical-align: middle; border: 1px solid #ddd;}
        .w3-table-all th { background-color: #007bff; color: white; }
        .w3-container.header-view { background-color: #007bff; color: white; padding-top: 10px; padding-bottom:10px; margin-bottom:20px;}
        .w3-button.w3-blue { background-color: #007bff !important; }
        .w3-button.w3-red { background-color: #dc3545 !important; }
        .w3-bar-item.w3-blue { background-color: #007bff !important; color:white !important;}
        .pet-list-item { margin-bottom: 10px; }
        .section-title { margin-top: 30px; margin-bottom: 15px; border-bottom: 2px solid #007bff; padding-bottom: 5px;}

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
    <a href="conferirAgendamentos.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-calendar-check-o fa-fw"></i>  Meus Agendamentos</a>
    <a href="historicoPets.php" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa-history fa-fw"></i>  Histórico dos Pets</a>
    <form action="/prepet/Controller/Navegacao.php" method="post" style="margin:0;">
        <button type="submit" name="btnSair" class="w3-bar-item w3-button w3-padding"><i class="fa fa-sign-out fa-fw"></i>  Sair</button>
    </form>
</nav>

<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" title="Close Sidemenu" id="myOverlay" style="cursor:pointer;"></div>

<div class="w3-main">

    <div class="w3-bar w3-top w3-blue w3-hide-large">
        <button class="w3-bar-item w3-button w3-xlarge" onclick="w3_open()">☰</button>
        <span class="w3-bar-item w3-xlarge">PrePet - Histórico</span>
    </div>

    <div class="w3-container" style="padding-top:16px;"> 
        <div class="w3-container header-view w3-round-large">
             <h2><i class="fa fa-history"></i> Histórico dos Pets</h2>
        </div>

        <?php if ($pacienteSelecionado): ?>
            <div class="w3-card-4 w3-white w3-padding w3-round-large" style="margin-top:20px;">
                <h3>Pet: <?php echo htmlspecialchars($pacienteSelecionado['nome']); ?></h3>
                <p><strong>Espécie:</strong> <?php echo htmlspecialchars($pacienteSelecionado['especie']); ?> | 
                   <strong>Raça:</strong> <?php echo htmlspecialchars($pacienteSelecionado['raca'] ?: 'N/D'); ?> | 
                   <strong>Nascimento:</strong> <?php echo htmlspecialchars(date('d/m/Y', strtotime($pacienteSelecionado['data_nasc']))); ?></p>
                
                <a href="historicoPets.php" class="w3-button w3-small w3-light-grey w3-round-large w3-margin-bottom"><i class="fa fa-list"></i> Ver lista de todos os meus pets</a>

                <h4 class="section-title">Procedimentos Realizados</h4>
                <?php if (!empty($procedimentos)): ?>
                    <div class="w3-responsive">
                        <table class="w3-table-all w3-hoverable w3-small">
                            <thead><tr><th>Data</th><th>Tipo</th><th>Diagnóstico</th><th>Resultado</th><th>Status do Pet</th><th>Veterinário</th></tr></thead>
                            <tbody>
                                <?php foreach ($procedimentos as $proc): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($proc['data_procedimento']))); ?></td>
                                    <td><?php echo htmlspecialchars($proc['tipo']); ?></td>
                                    <td><?php echo nl2br(htmlspecialchars($proc['diagnostico'])); ?></td>
                                    <td><?php echo nl2br(htmlspecialchars($proc['resultado'])); ?></td>
                                    <td><?php echo htmlspecialchars($proc['status_paciente']); ?></td>
                                    <td><?php echo htmlspecialchars($proc['nome_veterinario'] ?? 'N/D'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p>Nenhum procedimento registrado para este pet.</p>
                <?php endif; ?>

                <h4 class="section-title">Documentos</h4>
                <?php if (!empty($documentos)): ?>
                     <div class="w3-responsive">
                        <table class="w3-table-all w3-hoverable w3-small">
                            <thead><tr><th>Tipo</th><th>Conteúdo (Resumo)</th><th>Veterinário</th></tr></thead>
                            <tbody>
                                <?php foreach ($documentos as $doc): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($doc['tipo']); ?></td>
                                    <td><?php echo nl2br(htmlspecialchars(substr($doc['conteudo'], 0, 150) . (strlen($doc['conteudo']) > 150 ? '...' : ''))); ?></td>
                                    <td><?php echo htmlspecialchars($doc['nome_veterinario'] ?? 'N/D'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p>Nenhum documento registrado para este pet.</p>
                <?php endif; ?>
            </div>

        <?php elseif (!empty($petsDoCliente)): ?>
            <div class="w3-panel w3-pale-blue w3-leftbar w3-border-blue w3-padding-16 w3-round-large">
                <p><i class="fa fa-info-circle"></i> Selecione um pet para ver seu histórico detalhado.</p>
            </div>
            <div class="w3-row-padding">
                <?php foreach ($petsDoCliente as $pet): ?>
                    <div class="w3-col l4 m6 s12 pet-list-item">
                        <div class="w3-card-2 w3-padding w3-round-large w3-center">
                            <h4><i class="fa fa-paw"></i> <?php echo htmlspecialchars($pet['nome']); ?></h4>
                            <p><?php echo htmlspecialchars($pet['especie']); ?> - <?php echo htmlspecialchars($pet['raca'] ?: 'Sem raça definida'); ?></p>
                            <a href="historicoPets.php?id_paciente=<?php echo $pet['id']; ?>" class="w3-button w3-blue w3-round-large">Ver Histórico <i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="w3-panel w3-pale-yellow w3-leftbar w3-border-yellow w3-padding-16 w3-round-large">
                <p><i class="fa fa-exclamation-triangle"></i> Você não possui pets cadastrados. <a href="cadastrarPaciente.php">Cadastre um pet agora!</a></p>
            </div>
        <?php endif; ?>

        <p style="margin-top: 30px;">
            <a href="dashboardCliente.php" class="w3-button w3-red w3-round-large">
                <i class="fa fa-arrow-left"></i> Voltar ao Dashboard
            </a>
        </p>
    </div>

    <footer class="w3-container w3-padding-16 w3-light-grey w3-center" style="margin-top:30px;">
        <p>© <?php echo date("Y"); ?> PrePet. Todos os direitos reservados.</p>
    </footer>

</div>

<script>
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