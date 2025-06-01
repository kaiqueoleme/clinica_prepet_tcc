<?php
session_start();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>PrePet - Cadastrar Pessoa e Endereço</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .w3-container .w3-card-4 {
            padding: 20px;
        }
    </style>
</head>
<body class="w3-light-grey">

<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

    <div class="w3-container w3-margin-bottom">
        <div class="w3-card-4 w3-white w3-round-large">
            <div class="w3-container w3-padding">
                <?php
                // Exibe mensagens de feedback (sucesso/erro)
                if (isset($_SESSION['mensagem_sucesso'])) {
                    echo '<div class="w3-panel w3-green w3-round-large"><p>' . $_SESSION['mensagem_sucesso'] . '</p></div>';
                    unset($_SESSION['mensagem_sucesso']);
                }
                if (isset($_SESSION['mensagem_erro'])) {
                    echo '<div class="w3-panel w3-red w3-round-large"><p>' . $_SESSION['mensagem_erro'] . '</p></div>';
                    unset($_SESSION['mensagem_erro']);
                }
                ?>
                <form action="../Controller/Navegacao.php" method="post" class="w3-container">

                    <h3>Dados da Pessoa</h3>
                    <p>
                        <label for="nome" class="w3-left">Nome Completo:</label>
                        <input type="text" id="nome" name="nome" class="w3-input w3-border w3-round-large" required>
                    </p>

                    <p>
                        <label for="dataNascimento" class="w3-left">Data de Nascimento:</label>
                        <input type="date" id="dataNascimento" name="dataNascimento" class="w3-input w3-border w3-round-large" required>
                    </p>

                    <p>
                        <label for="telefone" class="w3-left">Telefone:</label>
                        <input type="text" id="telefone" name="telefone" class="w3-input w3-border w3-round-large" required placeholder="(XX) XXXXX-XXXX">
                    </p>

                    <p>
                        <label for="rg" class="w3-left">RG (Opcional):</label>
                        <input type="text" id="rg" name="rg" class="w3-input w3-border w3-round-large" placeholder="XX.XXX.XXX-X">
                    </p>

                    <p>
                        <label for="cpf" class="w3-left">CPF (Opcional):</label>
                        <input type="text" id="cpf" name="cpf" class="w3-input w3-border w3-round-large" placeholder="XXX.XXX.XXX-XX">
                    </p>
                    
                    <h3 class="w3-border-top w3-padding-top w3-margin-top">Dados do Endereço</h3>
                    <p>
                        <label for="logradouro" class="w3-left">Logradouro:</label>
                        <input type="text" id="logradouro" name="logradouro" class="w3-input w3-border w3-round-large" required>
                    </p>

                    <p>
                        <label for="numero" class="w3-left">Número:</label>
                        <input type="text" id="numero" name="numero" class="w3-input w3-border w3-round-large" required>
                    </p>

                    <p>
                        <label for="bairro" class="w3-left">Bairro:</label>
                        <input type="text" id="bairro" name="bairro" class="w3-input w3-border w3-round-large" required>
                    </p>

                    <p>
                        <label for="cidade" class="w3-left">Cidade:</label>
                        <input type="text" id="cidade" name="cidade" class="w3-input w3-border w3-round-large" required>
                    </p>

                    <p>
                        <label for="cep" class="w3-left">CEP:</label>
                        <input type="text" id="cep" name="cep" class="w3-input w3-border w3-round-large" required placeholder="XXXXX-XXX">
                    </p>

                    <p class="w3-center">
                        <button type="submit" name="btnCadastrarPessoaEndereco" class="w3-button w3-green w3-round-large w3-large w3-margin-top">
                            <i class="fa fa-save"></i> Cadastrar Pessoa
                        </button>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <footer class="w3-container w3-padding-16 w3-light-grey">
        <p>PrePet - Sistema de Gestão Pet</p>
    </footer>

    </div>

</body>
</html>