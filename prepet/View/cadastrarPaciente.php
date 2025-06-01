<?php
// View/cadastroPaciente.php
if (!isset($_SESSION)) {
    session_start();
}

// Opcional: Você pode querer carregar o nome do cliente aqui para a saudação
// require_once '../Model/Pessoa.php';
// require_once '../Model/Usuario.php'; // Para unserializar, se ainda estiver usando

$nomeCliente = "Cliente"; // Valor padrão

if (isset($_SESSION['usuario_logado'])) {
    if (isset($_SESSION['id_pessoa'])) {
        require_once '../Model/Pessoa.php';
        $pessoa = new Pessoa();
        if ($pessoa->carregarPessoa($_SESSION['id_pessoa'])) {
            $nomeCliente = $pessoa->getNome();
        }
    }
} else {
    // Redireciona se não houver sessão ativa
    header("Location: ../View/loginCliente.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Paciente - PrePet</title>
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
            <h2><i class="fa fa-paw"></i> Cadastrar Novo Paciente</h2>
            <p>Preencha os dados do seu pet.</p>
            
            <form action="../Controller/Navegacao.php" method="post">
                <p>
                    <input type="text" name="nome" placeholder="Nome do Paciente" required>
                </p>
                <p>
                    <select name="especie" required>
                        <option value="" disabled selected>Selecione a Espécie</option>
                        <option value="Cachorro">Cachorro</option>
                        <option value="Gato">Gato</option>
                        <option value="Pássaro">Pássaro</option>
                        <option value="Roedor">Roedor</option>
                        <option value="Peixe">Peixe</option>
                        <option value="Outro">Outro</option>
                    </select>
                </p>
                <p>
                    <input type="text" name="raca" placeholder="Raça (opcional)">
                </p>
                <p>
                    <label for="data_nasc" class="w3-left">Data de Nascimento:</label>
                    <input type="date" id="data_nasc" name="data_nasc" required>
                </p>
                <p>
                    <button type="submit" name="btnCadastrarPaciente" class="w3-button w3-blue w3-block w3-round-large">
                        <i class="fa fa-plus-circle"></i> Cadastrar Paciente
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