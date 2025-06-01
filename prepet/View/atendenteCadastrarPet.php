<?php
// View/atendenteCadastrarPet.php

if (!isset($_SESSION)) {
    session_start();
}

// Redireciona se não for atendente logado (nível de acesso 2)
if (!isset($_SESSION['usuario_logado']) || !isset($_SESSION['nivel_acesso']) || $_SESSION['nivel_acesso'] != 2) {
    header("Location: ../View/loginFuncionario.php");
    exit();
}

require_once '../Controller/PessoaController.php'; 

$pessoaController = new PessoaController();
$listaPessoasParaSelecao = $pessoaController->listarTodasAsPessoasParaSelecao(); 

// Mensagens de feedback
$mensagem_sucesso = $_SESSION['mensagem_sucesso'] ?? null;
$mensagem_erro = $_SESSION['mensagem_erro'] ?? null;
unset($_SESSION['mensagem_sucesso']);
unset($_SESSION['mensagem_erro']);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Novo Pet (Atendente) - PrePet</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body, html { height: 100%; margin: 0; font-family: "Montserrat", sans-serif; }
        .main-content { padding-top: 50px; padding-bottom: 50px; }
        .form-card { max-width: 600px; margin: auto; }
        .header-view { background-color: #17a2b8; color: white; margin-bottom:20px; } 
        .w3-button.w3-teal { background-color: #20c997 !important; }
        .w3-button.w3-grey { background-color: #6c757d !important; }
        select, input[type="text"], input[type="date"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
    </style>
</head>
<body class="w3-light-grey">

    <div class="w3-container main-content">
        <div class="w3-card-4 w3-white w3-round-large form-card">
            <div class="w3-container header-view w3-round-top-large">
                <h2><i class="fa fa-paw"></i> Cadastrar Novo Pet</h2>
            </div>

            <?php if ($mensagem_sucesso): ?>
                <div class="w3-panel w3-green w3-padding w3-margin w3-round-large">
                    <span onclick="this.parentElement.style.display='none'" class="w3-button w3-transparent w3-display-topright">&times;</span>
                    <p><i class="fa fa-check-circle"></i> <?php echo htmlspecialchars($mensagem_sucesso); ?></p>
                </div>
            <?php endif; ?>
            <?php if ($mensagem_erro): ?>
                <div class="w3-panel w3-red w3-padding w3-margin w3-round-large">
                    <span onclick="this.parentElement.style.display='none'" class="w3-button w3-transparent w3-display-topright">&times;</span>
                    <p><i class="fa fa-exclamation-triangle"></i> <?php echo htmlspecialchars($mensagem_erro); ?></p>
                </div>
            <?php endif; ?>

            <form action="/prepet/Controller/Navegacao.php" method="post" class="w3-container w3-padding-16">
                <p>
                    <label for="id_pessoa_tutor" class="w3-text-grey"><b><i class="fa fa-user"></i> Selecione o Tutor (Pessoa):</b></label>
                    <select id="id_pessoa_tutor" name="id_pessoa_tutor" required>
                        <option value="" disabled selected>-- Escolha um Tutor --</option>
                        <?php if (!empty($listaPessoasParaSelecao)): ?> {/* ALTERAÇÃO AQUI */}
                            <?php foreach ($listaPessoasParaSelecao as $pessoa): ?> {/* ALTERAÇÃO AQUI */}
                                <option value="<?php echo htmlspecialchars($pessoa['id']); ?>">
                                    <?php echo htmlspecialchars($pessoa['nome']); ?> (CPF: <?php echo htmlspecialchars($pessoa['cpf'] ?: 'N/A'); ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>Nenhuma pessoa encontrada no sistema.</option>
                        <?php endif; ?>
                    </select>
                </p>
                <hr>
                <h4>Dados do Pet</h4>
                <p>
                    <label for="nome_pet" class="w3-text-grey"><b><i class="fa fa-tag"></i> Nome do Pet:</b></label>
                    <input type="text" id="nome_pet" name="nome_pet" required>
                </p>
                <p>
                    <label for="especie_pet" class="w3-text-grey"><b><i class="fa fa-github-alt"></i> Espécie:</b></label>
                    <select name="especie_pet" id="especie_pet" required>
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
                    <label for="raca_pet" class="w3-text-grey"><b><i class="fa fa-paw"></i> Raça:</b></label>
                    <input type="text" id="raca_pet" name="raca_pet" placeholder="Opcional">
                </p>
                <p>
                    <label for="data_nasc_pet" class="w3-text-grey"><b><i class="fa fa-birthday-cake"></i> Data de Nascimento do Pet:</b></label>
                    <input type="date" id="data_nasc_pet" name="data_nasc_pet" required>
                </p>
                <hr>
                <div class="w3-row-padding">
                    <div class="w3-half">
                        <button type="submit" name="btnAtendenteCadastraPet" class="w3-button w3-teal w3-block w3-round-large w3-padding">
                            <i class="fa fa-save"></i> Salvar Pet
                        </button>
                    </div>
                    <div class="w3-half">
                        <a href="dashboardAtendente.php" class="w3-button w3-grey w3-block w3-round-large w3-padding">
                            <i class="fa fa-arrow-left"></i> Voltar ao Dashboard
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <footer class="w3-container w3-padding-16 w3-light-grey w3-center" style="margin-top:30px;">
        <p>© <?php echo date("Y"); ?> PrePet. Todos os direitos reservados.</p>
    </footer>

</body>
</html>