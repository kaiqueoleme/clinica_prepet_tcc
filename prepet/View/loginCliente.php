<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Cliente - Clínica PrePet</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body, html{
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
        .login-card {
            max-width: 400px;
            padding: 30px;
            text-align: center;
        }
    </style>
</head>
<body class="w3-light-grey">

    <div class="w3-container w3-padding-64 w3-center">
        <div class="w3-card-4 w3-white w3-padding-large w3-round-large w3-display-middle" style="width:90%;max-width:400px;">
            <h2><i class="fa fa-user-circle"></i> Login Cliente</h2>

            <?php
            // Exibe a mensagem de erro de login, se existir na sessão
            if (isset($_SESSION['erro_login'])) {
                echo '<p class="w3-text-red">' . htmlspecialchars($_SESSION['erro_login']) . '</p>';
                unset($_SESSION['erro_login']); // Limpa a mensagem após exibir
            }
            ?>

            <form action="../Controller/Navegacao.php" method="post">
                <p><input class="w3-input w3-border w3-round" type="text" name="usuario" placeholder="Usuário" required></p>
                <p><input class="w3-input w3-border w3-round" type="password" name="senha" placeholder="Senha" required></p>
                <p><button name="btnLogarCliente" class="w3-button w3-black w3-block w3-round-large">Entrar</button></p>
            </form>

            <form action="../Controller/Navegacao.php" method="post">
                <p><button name="btnCadastrar" class="w3-button w3-blue w3-block w3-round-large">Cadastre-se</button></p>
            </form>
        </div>
    </div>

</body>
</html>