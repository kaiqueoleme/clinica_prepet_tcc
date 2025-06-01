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
            margin-top: 50px;
            margin-bottom: 50px;
        }
        input[type="text"], input[type="date"], input[type="number"] { /* Adicionado number */
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
            background-color: white;
        }
    </style>
</head>
<body class="w3-light-grey">

    <div class="w3-container w3-padding-64 w3-center">
        <div class="w3-card-4 w3-white w3-padding-large w3-round-large w3-display-middle" style="width:90%;max-width:500px;">
            <h2><i class="fa fa-paw"></i> Cadastrar Novo Paciente</h2>
            <p>Preencha os dados do seu pet e selecione o tutor.</p>

            <?php echo $mensagem; // Exibe a mensagem de sucesso ou erro ?>
            
            <form action="cadastrarPacienteComPessoa.php" method="post">
                <p>
                    <label for="id_pessoa" class="w3-left">Tutor (Pessoa):</label>
                    <select name="id_pessoa" id="id_pessoa" required>
                        <option value="" disabled <?php echo empty($idPessoaSelecionada) ? 'selected' : ''; ?>>Selecione o Tutor</option>
                        <?php foreach ($listaPessoas as $pessoaItem): ?>
                            <option value="<?php echo htmlspecialchars($pessoaItem['id']); ?>"
                                <?php
                                if ($id_pessoa_logada == $pessoaItem['id']) {
                                    echo 'selected';
                                }
                                if (!empty($idPessoaSelecionada) && $idPessoaSelecionada == $pessoaItem['id']) {
                                    echo 'selected';
                                }
                                ?>
                            >
                                <?php echo htmlspecialchars($pessoaItem['nome']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <p>
                    <label for="nome" class="w3-left">Nome do Paciente:</label>
                    <input type="text" id="nome" name="nome" placeholder="Nome do Paciente" value="<?php echo htmlspecialchars($nomePaciente ?? ''); ?>" required>
                </p>
                <p>
                    <label for="especie" class="w3-left">Espécie:</label>
                    <select name="especie" id="especie" required>
                        <option value="" disabled <?php echo empty($especie) ? 'selected' : ''; ?>>Selecione a Espécie</option>
                        <option value="Cachorro" <?php echo ($especie == 'Cachorro') ? 'selected' : ''; ?>>Cachorro</option>
                        <option value="Gato" <?php echo ($especie == 'Gato') ? 'selected' : ''; ?>>Gato</option>
                        <option value="Pássaro" <?php echo ($especie == 'Pássaro') ? 'selected' : ''; ?>>Pássaro</option>
                        <option value="Roedor" <?php echo ($especie == 'Roedor') ? 'selected' : ''; ?>>Roedor</option>
                        <option value="Peixe" <?php echo ($especie == 'Peixe') ? 'selected' : ''; ?>>Peixe</option>
                        <option value="Outro" <?php echo ($especie == 'Outro') ? 'selected' : ''; ?>>Outro</option>
                    </select>
                </p>
                <p>
                    <label for="raca" class="w3-left">Raça:</label>
                    <input type="text" id="raca" name="raca" placeholder="Raça (opcional)" value="<?php echo htmlspecialchars($raca ?? ''); ?>">
                </p>
                <p>
                    <label for="data_nasc" class="w3-left">Data de Nascimento:</label>
                    <input type="date" id="data_nasc" name="data_nasc" value="<?php echo htmlspecialchars($dataNascimento ?? ''); ?>" required>
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