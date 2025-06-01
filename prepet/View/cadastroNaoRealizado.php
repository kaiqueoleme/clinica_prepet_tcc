<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro no Cadastro!</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            background-color: #f1f1f1;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .error-card {
            max-width: 500px;
            text-align: center;
            padding: 30px;
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
            border-radius: 8px; 
        }
        .error-icon {
            font-size: 80px;
            color: #f44336;
            margin-bottom: 20px;
        }
        .error-card h1 {
            color: #333;
            margin-bottom: 15px;
        }
        .error-card p {
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

<div class="w3-card-4 w3-white error-card">
    <i class="fa fa-times-circle error-icon"></i> <h1>Cadastro Não Realizado</h1>
    <p>Ocorreu um erro ao tentar realizar o seu cadastro. Por favor, revise as informações e tente novamente. Se o problema persistir, entre em contato com o suporte.</p>
    
    <p>
        <form action="../Controller/Navegacao.php" method="post" style="display:inline-block;">
            <button type="submit" name="btnVoltarCadastro" class="w3-button w3-red w3-round-large">Tentar Novamente</button> </form>

        <form action="../Controller/Navegacao.php" method="post" style="display:inline-block;">
            <button type="submit" name="btnTelaInicial" class="w3-button w3-light-grey w3-round-large">Página Inicial</button>
        </form>
    </p>
</div>

</body>
</html>