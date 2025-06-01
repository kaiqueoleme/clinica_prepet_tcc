<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Realizado com Sucesso!</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            background-color: #f1f1f1; /* Um fundo claro para contraste */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh; /* Ocupa a altura total da viewport */
            margin: 0;
        }
        .success-card {
            max-width: 500px;
            text-align: center;
            padding: 30px;
        }
        .success-icon {
            font-size: 80px;
            color: #4CAF50; /* Verde de sucesso */
            margin-bottom: 20px;
        }
        .success-card h1 {
            color: #333;
            margin-bottom: 15px;
        }
        .success-card p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 25px;
        }
        .w3-button {
            margin: 10px 5px;
        }
    </style>
</head>
<body>

<div class="w3-card-4 w3-white success-card">
    <i class="fa fa-check-circle success-icon"></i>
    <h1>Cadastro Realizado com Sucesso!</h1>
    <p>Parabéns! Sua conta foi criada com êxito. Agora você pode fazer login para acessar todos os recursos da nossa clínica veterinária.</p>
    
    <p>
        <form action="../Controller/Navegacao.php" method="post" style="display:inline-block;">
            <button type="submit" name="btnLoginClientes" class="w3-button w3-cyan w3-round-large">Fazer Login</button>
        </form>

        <form action="../Controller/Navegacao.php" method="post" style="display:inline-block;">
            <button type="submit" name="btnTelaInicial" class="w3-button w3-light-grey w3-round-large">Página Inicial</button>
        </form>
    </p>
</div>

</body>
</html>