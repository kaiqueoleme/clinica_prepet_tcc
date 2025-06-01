<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['usuario_logado']) || !isset($_SESSION['nivel_acesso']) || $_SESSION['nivel_acesso'] != 3) {
    header("Location: ../View/loginFuncionario.php");
    exit();
}

require_once '../Controller/EstoqueController.php';

$idItem = $_GET['id_item'] ?? null;
$itemEstoque = null;
$nomeProdutoAtual = '';
$quantidadeAtual = '';
$fornecedorAtual = '';
$mensagem = '';

if (!$idItem || !is_numeric($idItem)) {
    $_SESSION['mensagem_erro_estoque'] = "ID do item inválido ou não fornecido.";
    header('Location: listaEstoque.php');
    exit();
}

$estoqueController = new EstoqueController();
$itemEstoque = $estoqueController->buscarItemEstoquePorId((int)$idItem);

if (!$itemEstoque) {
    $_SESSION['mensagem_erro_estoque'] = $estoqueController->getMensagemErro() ?: "Item não encontrado para edição.";
    header('Location: listaEstoque.php');
    exit();
} else {
    $nomeProdutoAtual = $itemEstoque['nome_produto'];
    $quantidadeAtual = $itemEstoque['quantidade'];
    $fornecedorAtual = $itemEstoque['fornecedor'];
}

if(isset($_SESSION['mensagem_sucesso_estoque_edit'])) {
    $mensagem = '<div class="w3-panel w3-green w3-padding w3-margin w3-round-large"><span onclick="this.parentElement.style.display=\'none\'" class="w3-button w3-transparent w3-display-topright">&times;</span><p><i class="fa fa-check-circle"></i> '.htmlspecialchars($_SESSION['mensagem_sucesso_estoque_edit']).'</p></div>';
    unset($_SESSION['mensagem_sucesso_estoque_edit']);
}
if(isset($_SESSION['mensagem_erro_estoque_edit'])) {
    $mensagem = '<div class="w3-panel w3-red w3-padding w3-margin w3-round-large"><span onclick="this.parentElement.style.display=\'none\'" class="w3-button w3-transparent w3-display-topright">&times;</span><p><i class="fa fa-exclamation-triangle"></i> '.htmlspecialchars($_SESSION['mensagem_erro_estoque_edit']).'</p></div>';
    unset($_SESSION['mensagem_erro_estoque_edit']);
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Item do Estoque - PrePet</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body, html { height: 100%; margin: 0; font-family: "Montserrat", sans-serif; background-color: #f0f2f5; }
        .main-container { max-width: 600px; margin: 50px auto; background-color: white; }
        .header-view { background-color: #f0ad4e; color: white; } /* Laranja para edição */
        .w3-button.w3-orange { background-color: #f0ad4e !important; }
        .w3-button.w3-grey { background-color: #6c757d !important; }
    </style>
</head>
<body>

    <div class="w3-container main-container w3-card-4 w3-round-large">
        <div class="w3-container header-view w3-padding-large w3-round-top-large">
            <h2><i class="fa fa-pencil-square-o"></i> Editar Item do Estoque</h2>
            <p>Modifique os dados do item selecionado.</p>
        </div>

        <div class="w3-container w3-padding">
            <?php echo $mensagem; ?>

            <form action="../Controller/Navegacao.php" method="POST" class="w3-container w3-margin-top">
                <input type="hidden" name="id_item" value="<?php echo htmlspecialchars($idItem); ?>">
                
                <p>
                    <label for="nome_produto" class="w3-text-grey"><b>Nome do Produto</b></label>
                    <input class="w3-input w3-border w3-round-large" type="text" id="nome_produto" name="nome_produto" 
                           value="<?php echo htmlspecialchars($nomeProdutoAtual); ?>" required>
                </p>
                <p>
                    <label for="quantidade" class="w3-text-grey"><b>Quantidade</b></label>
                    <input class="w3-input w3-border w3-round-large" type="number" id="quantidade" name="quantidade" 
                           value="<?php echo htmlspecialchars($quantidadeAtual); ?>" required min="0">
                </p>
                <p>
                    <label for="fornecedor" class="w3-text-grey"><b>Fornecedor</b></label>
                    <input class="w3-input w3-border w3-round-large" type="text" id="fornecedor" name="fornecedor" 
                           value="<?php echo htmlspecialchars($fornecedorAtual); ?>" required>
                </p>
                <hr>
                <div class="w3-row-padding">
                    <div class="w3-half">
                        <button type="submit" name="btnAtualizarItemEstoque" class="w3-button w3-orange w3-block w3-round-large w3-padding">
                            <i class="fa fa-save"></i> Salvar Alterações
                        </button>
                    </div>
                    <div class="w3-half">
                         <a href="listaEstoqueVet.php" class="w3-button w3-grey w3-block w3-round-large w3-padding">
                            <i class="fa fa-times"></i> Cancelar / Voltar
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