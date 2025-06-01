<?php
// View/listaEstoque.php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['usuario_logado']) || (isset($_SESSION['nivel_acesso']) && $_SESSION['nivel_acesso'] != 2 && $_SESSION['nivel_acesso'] != 3) ) {
     if (!isset($_SESSION['nivel_acesso']) || ($_SESSION['nivel_acesso'] != 2 && $_SESSION['nivel_acesso'] != 3 && (!isset($_SESSION['id_acesso']) || $_SESSION['id_acesso'] != 1) ) ) {
     }
     if (!isset($_SESSION['nivel_acesso']) || $_SESSION['nivel_acesso'] != 3) {
        header("Location: ../View/loginFuncionario.php");
        exit();
    }
}


require_once '../Controller/EstoqueController.php'; 

$nomeFuncionario = $_SESSION['usuario_logado'] ?? "Usuário"; // Nome do funcionário logado

$estoqueController = new EstoqueController(); 
$itensEstoque = $estoqueController->listarItensEstoque(); 

$mensagem_sucesso = $_SESSION['mensagem_sucesso_estoque'] ?? null;
$mensagem_erro = $_SESSION['mensagem_erro_estoque'] ?? null;
unset($_SESSION['mensagem_sucesso_estoque']);
unset($_SESSION['mensagem_erro_estoque']);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estoque - PrePet</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: "Montserrat", sans-serif;
            background-color: #f0f2f5; /* Um cinza claro para o fundo */
        }
        .main-container { max-width: 900px; margin: 50px auto; background-color: white; }
        .header-view { background-color: #5cb85c; color: white; /* Verde para estoque */ }
        .w3-table-all th { background-color: #4cae4c; color: white; } /* Tom de verde mais escuro para cabeçalho */
        .w3-button.w3-blue { background-color: #337ab7 !important; } /* Azul para ações primárias */
        .w3-button.w3-red { background-color: #d9534f !important; } /* Vermelho para voltar/cancelar */
        .action-buttons a, .action-buttons button { margin-right: 5px; }
    </style>
</head>
<body>

    <div class="w3-container main-container w3-card-4 w3-round-large">
        <div class="w3-container header-view w3-padding-large w3-round-top-large">
            <h2><i class="fa fa-cubes"></i> Estoque Atual</h2>
            <p>Itens disponíveis no estoque. Administrado por: <?php echo $nomeFuncionario; ?></p>
        </div>

        <div class="w3-container w3-padding">
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

            <p>
                <a href="adicionarEstoque.php" class="w3-button w3-blue w3-round-large"><i class="fa fa-plus"></i> Adicionar Novo Item</a>
            </p>

            <?php if (empty($itensEstoque)): ?>
                <div class="w3-panel w3-yellow w3-padding-16 w3-round-large">
                    <h3><i class="fa fa-info-circle"></i> Aviso!</h3>
                    <p>Nenhum item encontrado no estoque.</p>
                </div>
            <?php else: ?>
                <div class="w3-responsive">
                    <table class="w3-table-all w3-hoverable w3-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome do Produto</th>
                                <th>Quantidade</th>
                                <th>Fornecedor</th>
                                <th>Ações</th> </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($itensEstoque as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['id']); ?></td>
                                    <td><?php echo htmlspecialchars($item['nome_produto']); ?></td>
                                    <td><?php echo htmlspecialchars($item['quantidade']); ?></td>
                                    <td><?php echo htmlspecialchars($item['fornecedor']); ?></td>
                                    <td class="action-buttons">
                                        <a href="editarItemEstoque.php?id_item=<?php echo htmlspecialchars($item['id']); ?>" 
                                           class="w3-button w3-tiny w3-orange w3-round-large" title="Editar Item">
                                            <i class="fa fa-pencil"></i> Editar
                                        </a>
                                        </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <p style="margin-top: 20px;">
                <a href="../View/dashboardVeterinario.php" class="w3-button w3-red w3-round-large">
                    <i class="fa fa-arrow-circle-left"></i> Voltar ao Dashboard
                </a>
            </p>
        </div>
    </div>

    <footer class="w3-container w3-padding-16 w3-light-grey w3-center" style="margin-top:30px;">
        <p>© <?php echo date("Y"); ?> PrePet. Todos os direitos reservados.</p>
    </footer>

</body>
</html>