<?php
// View/adicionarEstoque.php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['usuario_logado']) || !isset($_SESSION['nivel_acesso']) || $_SESSION['nivel_acesso'] != 3) {
    header("Location: ../View/loginFuncionario.php");
    exit();
}

require_once '../Controller/EstoqueController.php';

$mensagem = ''; 
$nomeProduto = ''; // Para limpar os campos após sucesso
$quantidade = '';  // Para limpar os campos após sucesso
$fornecedor = '';  // Para limpar os campos após sucesso

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomeProdutoForm = $_POST['nome_produto'] ?? '';
    $quantidadeForm = $_POST['quantidade'] ?? '';
    $fornecedorForm = $_POST['fornecedor'] ?? '';

    if (empty($nomeProdutoForm) || !isset($quantidadeForm) || $quantidadeForm === '' || empty($fornecedorForm)) {
        $mensagem = '<div class="w3-panel w3-red w3-round-large w3-padding-16">
                        <h3>Erro!</h3>
                        <p>Por favor, preencha todos os campos (Nome do Produto, Quantidade, Fornecedor).</p>
                      </div>';
        $nomeProduto = $nomeProdutoForm;
        $quantidade = $quantidadeForm;
        $fornecedor = $fornecedorForm;

    } elseif (!is_numeric($quantidadeForm) || (int)$quantidadeForm <= 0) {
        $mensagem = '<div class="w3-panel w3-red w3-round-large w3-padding-16">
                        <h3>Erro!</h3>
                        <p>A quantidade deve ser um número positivo maior que zero.</p>
                      </div>';
        $nomeProduto = $nomeProdutoForm;
        $quantidade = $quantidadeForm;
        $fornecedor = $fornecedorForm;
    } else {
        $estoqueController = new EstoqueController();
        $resultado = $estoqueController->adicionarItemEstoque($nomeProdutoForm, (int)$quantidadeForm, $fornecedorForm); 

        if ($resultado) {
            $mensagem = '<div class="w3-panel w3-green w3-round-large w3-padding-16">
                            <h3>Sucesso!</h3>
                            <p>Item "'.htmlspecialchars($nomeProdutoForm).'" adicionado ao estoque com sucesso.</p>
                          </div>';
            $nomeProduto = ''; 
            $quantidade = '';  
            $fornecedor = '';  
        } else {
            $erroMsgController = $estoqueController->getMensagemErro();
            $mensagem = '<div class="w3-panel w3-red w3-round-large w3-padding-16">
                            <h3>Erro!</h3>
                            <p>'.($erroMsgController ?: 'Ocorreu um erro ao adicionar o item. Tente novamente.').'</p>
                          </div>';
            $nomeProduto = $nomeProdutoForm;
            $quantidade = $quantidadeForm;
            $fornecedor = $fornecedorForm;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Item ao Estoque - PrePet</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body, html { height: 100%; margin: 0; font-family: "Montserrat", sans-serif; background-color: #f0f2f5; }
        .main-container { max-width: 600px; margin: 50px auto; background-color: white; }
        .header-view { background-color: #5cb85c; color: white; } /* Verde para estoque */
        .w3-button.w3-green { background-color: #5cb85c !important; }
        .w3-button.w3-grey { background-color: #6c757d !important; }
    </style>
</head>
<body>

    <div class="w3-container main-container w3-card-4 w3-round-large">
        <div class="w3-container header-view w3-padding-large w3-round-top-large">
            <h2><i class="fa fa-plus-circle"></i> Adicionar Novo Item ao Estoque</h2>
            <p>Preencha os campos abaixo.</p>
        </div>

        <div class="w3-container w3-padding">
            <?php echo $mensagem; ?>

            <form action="adicionarEstoque.php" method="POST" class="w3-container w3-margin-top">
                <p>
                    <label for="nome_produto" class="w3-text-grey"><b>Nome do Produto</b></label>
                    <input class="w3-input w3-border w3-round-large" type="text" id="nome_produto" name="nome_produto" 
                           value="<?php echo htmlspecialchars($nomeProduto); ?>" required>
                </p>
                <p>
                    <label for="quantidade" class="w3-text-grey"><b>Quantidade</b></label>
                    <input class="w3-input w3-border w3-round-large" type="number" id="quantidade" name="quantidade" 
                           value="<?php echo htmlspecialchars($quantidade); ?>" required min="1">
                </p>
                <p>
                    <label for="fornecedor" class="w3-text-grey"><b>Fornecedor</b></label>
                    <input class="w3-input w3-border w3-round-large" type="text" id="fornecedor" name="fornecedor" 
                           value="<?php echo htmlspecialchars($fornecedor); ?>" required>
                </p>
                <hr>
                <div class="w3-row-padding">
                    <div class="w3-half">
                        <button type="submit" class="w3-button w3-green w3-block w3-round-large w3-padding">
                            <i class="fa fa-plus"></i> Adicionar Item
                        </button>
                    </div>
                     <div class="w3-half">
                        <a href="listaEstoqueVet.php" class="w3-button w3-grey w3-block w3-round-large w3-padding">
                            <i class="fa fa-list"></i> Ver Estoque / Cancelar
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