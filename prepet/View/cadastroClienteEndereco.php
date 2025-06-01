<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Clínica PrePet</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/711de5e590.js" crossorigin="anonymous"></script>
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
    </style>
</head>
<body class="w3-light-grey">

    <!-- Form. Login -->
    <div class="w3-container w3-padding-64 w3-center">
        <div class="w3-card-4 w3-white w3-padding-large w3-round-large w3-display-middle" style="width:90%;max-width:400px;">
            <h2><i class="fa fa-user-circle"></i> Cadastro</h2>
            <form action="../Controller/Navegacao.php" method="post">
                    <h3><i class="fa fa-user-circle"></i>Endereço</h3>
                    <input type="text" name="logradouro" placeholder="Logradouro" required>
                    <input type="text" name="numero" placeholder="Número" required>
                    <input type="text" name="bairro" placeholder="Bairro" required>
                    <input type="text" name="cidade" placeholder="Cidade" required>
                    <input type="text" name="cep" placeholder="CEP" required>
                <p><button name="btnCadastroEndereco" class="w3-button w3-black w3-block w3-round-large">Cadastrar</button></p>
            </form>
        </div>
    </div>
</body>
</html>
