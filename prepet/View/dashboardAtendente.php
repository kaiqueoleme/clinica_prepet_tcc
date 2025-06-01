<?php
// C:\xampp\htdocs\prepet\View\dashboardAtendente.php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['usuario_logado']) || $_SESSION['nivel_acesso'] != 2) {
    header("Location: ../View/loginFuncionario.php");
    exit();
}

$nomeFuncionario = $_SESSION['usuario_logado'];

require_once '../Controller/AgendamentoController.php'; // Inclui o Controller de Agendamento

$agendamentoController = new AgendamentoController();
$agendamentos = $agendamentoController->listarTodosAgendamentos(); // Busca todos os agendamentos

?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Atendente - PrePet</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        html, body, h1, h2, h3, h4, h5, h6 {
            font-family: "Raleway", sans-serif
        }
        .w3-sidebar {
            z-index: 3;
            width: 250px;
            background-color: #2c3e50; /* Um tom de azul escuro */
            position: fixed;
            height: 100%;
            top: 0;
            left: 0;
        }
        .w3-sidebar a {
            padding: 15px;
            text-decoration: none;
            font-size: 18px;
            color: #ecf0f1; /* Um cinza claro */
            display: block;
            transition: 0.3s;
        }
        .w3-sidebar a:hover {
            color: #f39c12; /* Laranja */
            background-color: #34495e; /* Um tom um pouco mais claro */
        }
        .w3-main {
            margin-left: 250px;
            transition: margin-left 0.5s;
        }
        .w3-topbar {
            background-color: #1abc9c; /* Verde água */
            color: white;
            padding: 15px;
            position: sticky;
            top: 0;
            z-index: 2;
            box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16), 0 2px 10px 0 rgba(0,0,0,0.12);
        }
        .w3-bar-item.w3-button {
            width: 100%;
            text-align: left;
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

<nav class="w3-sidebar w3-collapse w3-cyan w3-animate-left" style="z-index:3;width:250px;" id="mySidebar"><br>
    <div class="w3-container w3-row">
        <div class="w3-col s4">
            <img src="https://www.w3schools.com/w3css/img_avatar2.png" class="w3-circle w3-margin-right" style="width:46px">
        </div>
        <div class="w3-col s8 w3-bar">
            <span>Bem-vindo(a), <strong><?php echo htmlspecialchars($nomeFuncionario); ?></strong></span><br>
            <a href="alterarDadosUsuario.php" class="w3-button w3-white w3-round-large">Alterar Cadastro</a>
        </div>
    </div>
    <hr>
    <div class="w3-container">
        <h5>Dashboard Atendente</h5>
    </div>
    <div class="w3-bar-block">
        <a href="/prepet/View/dashboardAtendente.php" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa-dashboard fa-fw"></i>  Dashboard</a>
        <a href="/prepet/View/listaPaciente.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-paw fa-fw"></i>  Pacientes</a>
        <a href="/prepet/View/agendamentoConsulta.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-calendar-plus-o fa-fw"></i>  Agendar Atendimento</a>
        <a href="/prepet/View/listaEstoque.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-cubes fa-fw"></i>  Consultar Estoque</a>
        <a href="/prepet/View/atendenteCadastrarPessoa.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-user-plus fa-fw"></i>  Cadastrar Pessoa</a>
        <a href="/prepet/View/atendenteCadastrarPet.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-paw fa-fw"></i>  Cadastrar Pet</a>
        <a href="/prepet/View/atendenteListarPessoas.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-users fa-fw"></i>  Listar Clientes</a>
        <form action="/prepet/Controller/Navegacao.php" method="post">
            <button type="submit" name="btnSair" class="w3-bar-item w3-button w3-padding"><i class="fa fa-sign-out fa-fw"></i>  Sair</button>
        </form>
    </div>
</nav>

<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<div class="w3-main" style="margin-left:250px;margin-top:43px;">

    <header class="w3-container" style="padding-top:22px">
        <h5><b><i class="fa fa-dashboard"></i> Meu Dashboard</b></h5>
    </header>

    <div class="w3-row-padding w3-margin-bottom">
        <div class="w3-quarter">
            <div class="w3-container w3-red w3-padding-16">
                <div class="w3-left"><i class="fa fa-calendar-check-o w3-xxxlarge"></i></div>
                <div class="w3-right">
                    <h3><?php echo count($agendamentos); ?></h3> </div>
                <div class="w3-clear"></div>
                <h4>Total de Agendamentos</h4>
            </div>
        </div>
        </div>

    <div class="w3-container w3-panel w3-white w3-padding-large w3-round-large">
        <h4><i class="fa fa-calendar"></i> Próximos Agendamentos</h4>
        <p>Visão geral dos atendimentos agendados.</p>

        <?php if (empty($agendamentos)): ?>
            <div class="w3-panel w3-yellow w3-round-large w3-padding-16">
                <h3>Aviso!</h3>
                <p>Nenhum agendamento encontrado.</p>
            </div>
        <?php else: ?>
            <div class="w3-responsive">
                <table class="w3-table-all">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Hora</th>
                            <th>Paciente</th>
                            <th>Veterinário</th>
                            <th>Serviço</th>
                            <th>Status</th>
                            <th>Obs.</th>
                            <th>Alterar</th> </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($agendamentos as $agendamento): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($agendamento['data_agend']))); ?></td>
                                <td><?php echo htmlspecialchars($agendamento['hora_consulta']); ?></td>
                                <td><?php echo htmlspecialchars($agendamento['nome_paciente']); ?></td>
                                <td><?php echo htmlspecialchars($agendamento['nome_veterinario']); ?></td>
                                <td><?php echo htmlspecialchars($agendamento['tipo_servico']); ?></td>
                                <td><?php echo htmlspecialchars($agendamento['status']); ?></td>
                                <td><?php echo htmlspecialchars($agendamento['observacoes'] ?? '-'); ?></td>
                                <td class="w3-center">
                                    <a href="/prepet/View/editarAgendamento.php?id=<?php echo htmlspecialchars($agendamento['id']); ?>" class="w3-button w3-center w3-blue w3-tiny w3-round" title="Editar Agendamento">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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