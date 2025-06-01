<?php
// C:\xampp\htdocs\prepet\View\registrarDocumento.php

if (!isset($_SESSION)) {
    session_start();
}

// Redireciona se o usuário não estiver logado ou não for veterinário (Nível 3)
if (!isset($_SESSION['usuario_logado']) || ($_SESSION['nivel_acesso'] != 3)) {
    header("Location: ../View/loginFuncionario.php");
    exit();
}

// Inclui os controllers necessários
require_once __DIR__ . '/../Controller/PacienteController.php';
require_once __DIR__ . '/../Model/Veterinario.php'; // Para obter o ID real do veterinário

$idPaciente = null;
$nomePaciente = "Paciente Não Encontrado"; // Valor padrão caso o ID não seja válido ou não venha

// Tenta obter o ID do paciente da URL (GET)
if (isset($_GET['id_paciente']) && is_numeric($_GET['id_paciente'])) {
    $idPaciente = (int)$_GET['id_paciente'];

    $pacienteController = new PacienteController();
    $pacienteObj = $pacienteController->buscarPaciente($idPaciente); 
    if ($pacienteObj && method_exists($pacienteObj, 'getNome')) {
        $nomePaciente = htmlspecialchars($pacienteObj->getNome());
    } else {
    }
}

$idPessoaVeterinarioLogado = $_SESSION['id_pessoa'];
$idVeterinarioReal = null;

$veterinarioModel = new Veterinario();
$dadosVeterinario = $veterinarioModel->buscarPorIdPessoa($idPessoaVeterinarioLogado);

if ($dadosVeterinario && isset($dadosVeterinario['id'])) {
    $idVeterinarioReal = (int)$dadosVeterinario['id'];
} else {
    $_SESSION['erro'] = "Erro: Seu perfil de veterinário não foi encontrado. Contate o administrador.";
    header("Location: dashboardVeterinario.php"); // Redireciona de volta
    exit();
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Documento - PrePet</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body class="w3-light-grey">

        <header class="w3-container" style="padding-top:22px">
            <h5><b><i class="fa fa-file-text-o"></i> Registrar Novo Documento</b></h5>
            <p>Registrando documento para: <?php echo $nomePaciente; ?> (ID: <?php echo htmlspecialchars($idPaciente); ?>)</p>
        </header>

        <div class="w3-container w3-padding">
            <?php
            // Exibir mensagens de sucesso ou erro
            if (isset($_SESSION['mensagem'])) {
                echo '<div class="w3-panel w3-pale-green w3-border">' . $_SESSION['mensagem'] . '</div>';
                unset($_SESSION['mensagem']);
            }
            if (isset($_SESSION['erro'])) {
                echo '<div class="w3-panel w3-pale-red w3-border">' . $_SESSION['erro'] . '</div>';
                unset($_SESSION['erro']);
            }
            ?>

            <div class="w3-card-4 w3-white w3-margin-bottom">
                <div class="w3-container w3-padding-large">
                    <form action="../Controller/Navegacao.php" method="POST">
                        
                        <input type="hidden" name="id_pac" value="<?php echo htmlspecialchars($idPaciente); ?>">
                        
                        <input type="hidden" name="id_vet" value="<?php echo htmlspecialchars($idVeterinarioReal); ?>">

                        <p>
                            <label for="tipo">Tipo de Documento:</label>
                            <input class="w3-input w3-border" type="text" id="tipo" name="tipo" required 
                                placeholder="Ex: Receita, Atestado, Laudo de Exame">
                        </p>

                        <p>
                            <label for="conteudo">Conteúdo do Documento:</label>
                            <textarea class="w3-input w3-border" id="conteudo" name="conteudo" style="height:150px" required 
                                placeholder="Descreva o conteúdo completo do documento aqui..."></textarea>
                        </p>

                        <p>
                            <button class="w3-button w3-blue w3-round-large" type="submit" name="btnRegistrarDocumento">
                                <i class="fa fa-save"></i> Registrar Documento
                            </button>
                            <a href="dashboardVeterinario.php" class="w3-button w3-grey w3-round-large"><i class="fa fa-arrow-left"></i> Voltar</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>

        <footer class="w3-container w3-padding-16 w3-light-grey">
            <p>&copy; <?php echo date("Y"); ?> PrePet. Todos os direitos reservados.</p>
        </footer>

    </div>

    <script>
        // Script para abrir e fechar a sidebar em telas pequenas (se não estiver no sidebar.php)
        function w3_open() {
            document.getElementById("mySidebar").style.display = "block";
            document.getElementById("myOverlay").style.display = "block";
        }

        function w3_close() {
            document.getElementById("mySidebar").style.display = "none";
            document.getElementById("myOverlay").style.display = "none";
        }
    </script>

</body>
</html>