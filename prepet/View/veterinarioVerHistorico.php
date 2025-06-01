<?php
// View/veterinarioVerHistorico.php

if (!isset($_SESSION)) {
    session_start();
}

// Redireciona se não for Veterinário logado (nível de acesso 3)
if (!isset($_SESSION['usuario_logado']) || !isset($_SESSION['nivel_acesso']) || $_SESSION['nivel_acesso'] != 3) {
    header("Location: ../View/loginFuncionario.php");
    exit();
}

require_once '../Controller/PacienteController.php';
require_once '../Controller/ProcedimentoController.php';
require_once '../Controller/DocumentoController.php';

$nomeVeterinario = htmlspecialchars($_SESSION['usuario_logado']);

$pacienteController = new PacienteController();
$procedimentoController = new ProcedimentoController();
$documentoController = new DocumentoController();

$idPacienteSelecionado = null;
$pacienteSelecionado = null;
$todosOsPacientes = [];
$procedimentos = [];
$documentos = [];

if (isset($_GET['id_paciente']) && is_numeric($_GET['id_paciente'])) {
    $idPacienteSelecionado = (int)$_GET['id_paciente'];
    $pacienteObj = $pacienteController->buscarPaciente($idPacienteSelecionado);

    if ($pacienteObj) { // Veterinário pode ver qualquer paciente
        $pacienteSelecionado = [
            'id' => $pacienteObj->getId(),
            'nome' => $pacienteObj->getNome(),
            'especie' => $pacienteObj->getEspecie(),
            'raca' => $pacienteObj->getRaca(),
            'data_nasc' => $pacienteObj->getDataNasc(),
            'id_tutor' => $pacienteObj->getIdTutor()
        ];

        $procedimentos = $procedimentoController->listarProcedimentosPaciente($idPacienteSelecionado);
        $documentos = $documentoController->listarDocumentosPorPaciente($idPacienteSelecionado);
    } else {
        $_SESSION['mensagem_erro_hist'] = "Paciente com ID " . $idPacienteSelecionado . " não encontrado.";
        $idPacienteSelecionado = null; 
    }
} else {
    $todosOsPacientes = $pacienteController->listarTodosPacientes(); 
}

// Mensagens de feedback (se houver erro ao buscar paciente específico)
$mensagem_erro_hist = $_SESSION['mensagem_erro_hist'] ?? null;
unset($_SESSION['mensagem_erro_hist']);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Pacientes (Veterinário) - PrePet</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body, html { height: 100%; margin: 0; font-family: "Montserrat", sans-serif; }
        .main-content { padding-top: 20px; padding-bottom: 50px; }
        .container-padding { padding-left:16px; padding-right:16px;}
        .header-view { background-color: #1abc9c; color: white; margin-bottom:20px; } /* Verde Vet */
        .w3-table-all th { background-color: #16a085; color: white; } /* Tom mais escuro de verde */
        .w3-table-all td, .w3-table-all th { border: 1px solid #ddd; padding: 8px;}
        .section-title { margin-top: 30px; margin-bottom: 15px; border-bottom: 2px solid #1abc9c; padding-bottom: 5px;}
        .w3-button.w3-grey { background-color: #7f8c8d !important; }
        .w3-button.w3-teal { background-color: #1abc9c !important; }
    </style>
</head>
<body class="w3-light-grey">

    <nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index:3;width:250px;" id="mySidebarVet"><br>
        <div class="w3-container w3-row">
            <div class="w3-col s4">
                <img src="https://www.w3schools.com/w3images/avatar2.png" class="w3-circle w3-margin-right" style="width:46px">
            </div>
            <div class="w3-col s8 w3-bar">
                <span>Bem-vindo(a),<br><strong>Dr(a). <?php echo $nomeVeterinario; ?></strong></span><br>
                <a href="alterarDadosUsuario.php" class="w3-button w3-tiny w3-green w3-round" style="margin-top:5px; padding: 2px 5px;">Alterar Cadastro</a>
            </div>
        </div>
        <hr>
        <div class="w3-container">
            <h5>Dashboard Veterinário</h5>
        </div>
        <div class="w3-bar-block">
            <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close_vet()" title="close menu"><i class="fa fa-remove fa-fw"></i> Fechar Menu</a>
            <a href="dashboardVeterinario.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-tachometer fa-fw"></i> Dashboard Principal</a>
            <div class="w3-dropdown-hover">
                <button class="w3-button w3-padding w3-block w3-left-align"><i class="fa fa-plus-circle fa-fw"></i> Cadastros <i class="fa fa-caret-down"></i></button>
                <div class="w3-dropdown-content w3-bar-block w3-card-4">
                    <a href="veterinarioCadastrarPessoa.php" class="w3-bar-item w3-button">Nova Pessoa</a>
                    <a href="veterinarioCadastrarPet.php" class="w3-bar-item w3-button">Novo Pet</a>
                </div>
            </div>
            <a href="veterinarioVerHistorico.php" class="w3-bar-item w3-button w3-padding w3-teal"><i class="fa fa-history fa-fw"></i> Histórico de Pacientes</a>
            <form action="/prepet/Controller/Navegacao.php" method="post" style="margin:0;">
                 <button type="submit" name="btnSair" class="w3-bar-item w3-button w3-padding"><i class="fa fa-sign-out fa-fw"></i> Sair</button>
            </form>
        </div>
    </nav>

    <div class="w3-overlay w3-hide-large" onclick="w3_close_vet()" style="cursor:pointer" title="close side menu" id="myOverlayVet"></div>

    <div class="w3-main" style="margin-left:250px;">
        <div class="w3-bar w3-top w3-teal w3-hide-large">
            <button class="w3-bar-item w3-button w3-xlarge" onclick="w3_open_vet()">☰</button>
            <span class="w3-bar-item w3-xlarge">PrePet - Histórico</span>
        </div>

        <div class="main-content container-padding">
            <div class="w3-container header-view w3-round-large">
                <h2><i class="fa fa-history"></i> Histórico de Pacientes da Clínica</h2>
            </div>

            <?php if ($mensagem_erro_hist): ?>
                <div class="w3-panel w3-red w3-padding w3-margin w3-round-large">
                    <span onclick="this.parentElement.style.display='none'" class="w3-button w3-transparent w3-display-topright">&times;</span>
                    <p><i class="fa fa-exclamation-triangle"></i> <?php echo htmlspecialchars($mensagem_erro_hist); ?></p>
                </div>
            <?php endif; ?>

            <?php if ($pacienteSelecionado): ?>
                <div class="w3-card-4 w3-white w3-padding w3-round-large" style="margin-top:20px;">
                    <h3>Pet: <?php echo htmlspecialchars($pacienteSelecionado['nome']); ?></h3>
                    <p><strong>Espécie:</strong> <?php echo htmlspecialchars($pacienteSelecionado['especie']); ?> | 
                       <strong>Raça:</strong> <?php echo htmlspecialchars($pacienteSelecionado['raca'] ?: 'N/D'); ?> | 
                       <strong>Nascimento:</strong> <?php echo htmlspecialchars(date('d/m/Y', strtotime($pacienteSelecionado['data_nasc']))); ?>
                       <?php /* if(isset($pacienteSelecionado['nome_tutor'])) { echo " | <strong>Tutor:</strong> " . htmlspecialchars($pacienteSelecionado['nome_tutor']); } */ ?>
                    </p>
                    
                    <a href="veterinarioVerHistorico.php" class="w3-button w3-small w3-light-grey w3-round-large w3-margin-bottom"><i class="fa fa-list"></i> Ver lista de todos os pacientes</a>

                    <h4 class="section-title">Procedimentos Realizados</h4>
                    <?php if (!empty($procedimentos)): ?>
                        <div class="w3-responsive">
                            <table class="w3-table-all w3-hoverable w3-small">
                                <thead><tr><th>Data</th><th>Tipo</th><th>Diagnóstico</th><th>Resultado</th><th>Status do Pet</th><th>Veterinário Executor</th></tr></thead>
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
                                <thead><tr><th>Tipo</th><th>Conteúdo (Resumo)</th><th>Veterinário Emissor</th></tr></thead>
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

            <?php elseif (!empty($todosOsPacientes)): ?>
                <div class="w3-panel w3-pale-blue w3-leftbar w3-border-blue w3-padding-16 w3-round-large">
                    <p><i class="fa fa-info-circle"></i> Selecione um paciente da lista para ver seu histórico detalhado.</p>
                </div>
                <div class="w3-responsive w3-card-4 w3-round-large" style="margin-top:20px;">
                    <table class="w3-table-all w3-hoverable w3-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome do Pet</th>
                                <th>Espécie</th>
                                <th>Raça</th>
                                <th>Tutor</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($todosOsPacientes as $pet): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($pet['id']); ?></td>
                                    <td><?php echo htmlspecialchars($pet['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($pet['especie']); ?></td>
                                    <td><?php echo htmlspecialchars($pet['raca'] ?: 'N/D'); ?></td>
                                    <td><?php echo htmlspecialchars($pet['nome_tutor'] ?? 'N/D'); ?></td>
                                    <td>
                                        <a href="veterinarioVerHistorico.php?id_paciente=<?php echo $pet['id']; ?>" class="w3-button w3-tiny w3-teal w3-round-large">
                                            <i class="fa fa-eye"></i> Ver Histórico
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="w3-panel w3-pale-yellow w3-leftbar w3-border-yellow w3-padding-16 w3-round-large">
                    <p><i class="fa fa-exclamation-triangle"></i> Nenhum paciente cadastrado na clínica.</p>
                </div>
            <?php endif; ?>

            <p style="margin-top: 30px;">
                <a href="dashboardVeterinario.php" class="w3-button w3-grey w3-round-large">
                    <i class="fa fa-arrow-left"></i> Voltar ao Dashboard
                </a>
            </p>
        </div>

        <footer class="w3-container w3-padding-16 w3-light-grey w3-center" style="margin-top:auto; position:relative; bottom:0; width:calc(100% - 250px);">
            <p>© <?php echo date("Y"); ?> PrePet. Todos os direitos reservados.</p>
        </footer>
    </div> <script>
    // Script para sidebar do veterinário
    var mySidebarVet = document.getElementById("mySidebarVet");
    var overlayBgVet = document.getElementById("myOverlayVet");

    function w3_open_vet() {
        if (mySidebarVet.style.display === 'block') {
            mySidebarVet.style.display = 'none';
            overlayBgVet.style.display = "none";
        } else {
            mySidebarVet.style.display = 'block';
            overlayBgVet.style.display = "block";
        }
    }

    function w3_close_vet() {
        mySidebarVet.style.display = "none";
        overlayBgVet.style.display = "none";
    }
</script>

</body>
</html>