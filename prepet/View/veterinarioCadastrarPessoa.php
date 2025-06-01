<?php
// View/veterinarioCadastrarPessoa.php

if (!isset($_SESSION)) {
    session_start();
}

// Redireciona se não for Veterinário logado (nível de acesso 3)
if (!isset($_SESSION['usuario_logado']) || !isset($_SESSION['nivel_acesso']) || $_SESSION['nivel_acesso'] != 3) {
    header("Location: ../View/loginFuncionario.php");
    exit();
}

$nomeVeterinario = htmlspecialchars($_SESSION['usuario_logado']);

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
    <title>Cadastrar Nova Pessoa (Veterinário) - PrePet</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body, html { height: 100%; margin: 0; font-family: "Montserrat", sans-serif; }
        .main-content { padding-top: 50px; padding-bottom: 50px; }
        .form-card { max-width: 600px; margin: auto; }
        .header-view { background-color: #1abc9c; color: white; margin-bottom:20px; } /* Verde do Vet */
        .w3-button.w3-green { background-color: #2ecc71 !important; } 
        .w3-button.w3-grey { background-color: #7f8c8d !important; } 
    </style>
</head>
<body class="w3-light-grey">

    <div class="w3-container main-content">
        <div class="w3-card-4 w3-white w3-round-large form-card">
            <div class="w3-container header-view w3-round-top-large">
                <h2><i class="fa fa-user-plus"></i> Cadastrar Nova Pessoa</h2>
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
                    <label for="nome" class="w3-text-grey"><b><i class="fa fa-user"></i> Nome Completo:</b></label>
                    <input class="w3-input w3-border w3-round-large" type="text" id="nome" name="nome" required>
                </p>
                <p>
                    <label for="data_nascimento" class="w3-text-grey"><b><i class="fa fa-calendar"></i> Data de Nascimento:</b></label>
                    <input class="w3-input w3-border w3-round-large" type="date" id="data_nascimento" name="data_nascimento" required>
                </p>
                <p>
                    <label for="telefone" class="w3-text-grey"><b><i class="fa fa-phone"></i> Telefone:</b></label>
                    <input class="w3-input w3-border w3-round-large" type="text" id="telefone" name="telefone" placeholder="(XX) XXXXX-XXXX" required>
                </p>
                <p>
                    <label for="rg" class="w3-text-grey"><b><i class="fa fa-id-card-o"></i> RG:</b></label>
                    <input class="w3-input w3-border w3-round-large" type="text" id="rg" name="rg">
                </p>
                <p>
                    <label for="cpf" class="w3-text-grey"><b><i class="fa fa-id-card"></i> CPF:</b></label>
                    <input class="w3-input w3-border w3-round-large" type="text" id="cpf" name="cpf">
                </p>
                <hr>
                <div class="w3-row-padding">
                    <div class="w3-half">
                        <button type="submit" name="btnVeterinarioCadastraPessoa" class="w3-button w3-green w3-block w3-round-large w3-padding">
                            <i class="fa fa-save"></i> Salvar Pessoa
                        </button>
                    </div>
                    <div class="w3-half">
                        <a href="dashboardVeterinario.php" class="w3-button w3-grey w3-block w3-round-large w3-padding">
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