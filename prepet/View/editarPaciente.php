<?php
// View/editarPaciente.php

if (!isset($_SESSION)) {
    session_start();
}

// Redireciona se o usuário não estiver logado
if (!isset($_SESSION['usuario_logado']) || !isset($_SESSION['id_pessoa'])) {
    header("Location: ../View/loginCliente.php");
    exit();
}

require_once '../Model/Pessoa.php';
require_once '../Model/Paciente.php';
require_once '../Model/Tutor.php'; // Inclui o Model Tutor
require_once '../Controller/PacienteController.php';
require_once '../Controller/TutorController.php'; // Inclui o Controller Tutor

$nomeCliente = "Cliente";
$idPessoaLogada = $_SESSION['id_pessoa'];
$idTutorLogado = 0;

// Carregar o nome da pessoa logada
$pessoa = new Pessoa();
if ($pessoa->carregarPessoa($idPessoaLogada)) {
    $nomeCliente = $pessoa->getNome();
}

// Obter o ID do Tutor para o cliente logado
$tutorController = new TutorController();
$idTutorLogado = $tutorController->garantirTutor($idPessoaLogada);

$pacienteParaEdicao = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idPaciente = (int)$_GET['id'];

    $pacienteController = new PacienteController();
    $pacienteCarregado = $pacienteController->buscarPaciente($idPaciente);

    // Verifica se o paciente existe E se pertence ao tutor logado para segurança
    if ($pacienteCarregado && $pacienteCarregado->getIdTutor() == $idTutorLogado) {
        $pacienteParaEdicao = $pacienteCarregado;
    } else {
        // Redireciona se o paciente não for encontrado ou não pertencer ao tutor logado
        header("Location: ../View/dashboardCliente.php?msg=Paciente não encontrado ou acesso negado.");
        exit();
    }
} else {
    // Redireciona se nenhum ID de paciente for fornecido
    header("Location: ../View/dashboardCliente.php?msg=ID do paciente não fornecido.");
    exit();
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Paciente - PrePet</title>
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
            margin-top: 50px; /* Ajuste para não colar no topo */
            margin-bottom: 50px;
        }
        input[type="text"], input[type="date"] {
            width: 100%;
            padding: 8px;
            margin: 6px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        select {
            width: 100%;
            padding: 8px;
            margin: 6px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            background-color: white; /* Garante que o select tenha fundo branco */
        }
    </style>
</head>
<body class="w3-light-grey">

    <div class="w3-container w3-padding-64 w3-center">
        <div class="w3-card-4 w3-white w3-padding-large w3-round-large w3-display-middle" style="width:90%;max-width:500px;">
            <h2><i class="fa fa-paw"></i> Editar Paciente: <?php echo htmlspecialchars($pacienteParaEdicao->getNome()); ?></h2>
            <p>Altere os dados do seu pet.</p>
            
            <form action="../Controller/Navegacao.php" method="post">
                <input type="hidden" name="idPaciente" value="<?php echo htmlspecialchars($pacienteParaEdicao->getId()); ?>">
                
                <p>
                    <input type="text" name="nome" placeholder="Nome do Paciente" 
                           value="<?php echo htmlspecialchars($pacienteParaEdicao->getNome()); ?>" required>
                </p>
                <p>
                    <select name="especie" required>
                        <option value="" disabled>Selecione a Espécie</option>
                        <option value="Cachorro" <?php echo ($pacienteParaEdicao->getEspecie() == 'Cachorro') ? 'selected' : ''; ?>>Cachorro</option>
                        <option value="Gato" <?php echo ($pacienteParaEdicao->getEspecie() == 'Gato') ? 'selected' : ''; ?>>Gato</option>
                        <option value="Pássaro" <?php echo ($pacienteParaEdicao->getEspecie() == 'Pássaro') ? 'selected' : ''; ?>>Pássaro</option>
                        <option value="Roedor" <?php echo ($pacienteParaEdicao->getEspecie() == 'Roedor') ? 'selected' : ''; ?>>Roedor</option>
                        <option value="Peixe" <?php echo ($pacienteParaEdicao->getEspecie() == 'Peixe') ? 'selected' : ''; ?>>Peixe</option>
                        <option value="Outro" <?php echo ($pacienteParaEdicao->getEspecie() == 'Outro') ? 'selected' : ''; ?>>Outro</option>
                    </select>
                </p>
                <p>
                    <input type="text" name="raca" placeholder="Raça (opcional)"
                           value="<?php echo htmlspecialchars($pacienteParaEdicao->getRaca()); ?>">
                </p>
                <p>
                    <label for="data_nasc" class="w3-left">Data de Nascimento:</label>
                    <input type="date" id="data_nasc" name="data_nasc" 
                           value="<?php echo htmlspecialchars($pacienteParaEdicao->getDataNasc()); ?>" required>
                </p>
                <p>
                    <button type="submit" name="btnAtualizarPaciente" class="w3-button w3-green w3-block w3-round-large">
                        <i class="fa fa-save"></i> Salvar Alterações
                    </button>
                </p>
            </form>
            <p>
                <a href="../View/dashboardCliente.php" class="w3-button w3-red w3-block w3-round-large">
                    <i class="fa fa-arrow-circle-left"></i> Voltar ao Dashboard
                </a>
            </p>
        </div>
    </div>

</body>
</html>