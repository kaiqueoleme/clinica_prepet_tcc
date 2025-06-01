<?php
// View/atendenteListarPessoas.php

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
$listaClientes = $pessoaController->listarPessoasClientes();

$nomeAtendente = htmlspecialchars($_SESSION['usuario_logado']);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Clientes - PrePet</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body, html { height: 100%; margin: 0; font-family: "Montserrat", sans-serif; }
        .main-content { padding-top: 50px; padding-bottom: 50px; }
        .table-container { max-width: 900px; margin: auto; }
        .header-view { background-color: #666; color: white; margin-bottom:20px; } /* Cinza escuro */
        .w3-table-all th { background-color: #343a40; color: white; } /* Cabeçalho da tabela escuro */
        .w3-button.w3-grey { background-color: #6c757d !important; }
    </style>
</head>
<body class="w3-light-grey">

    <div class="w3-container main-content">
        <div class="w3-card-4 w3-white w3-round-large table-container">
            <div class="w3-container header-view w3-round-top-large">
                <h2><i class="fa fa-users"></i> Lista de Clientes (Pessoas com acesso de cliente)</h2>
            </div>

            <div class="w3-container w3-padding-16">
                <?php if (!empty($listaClientes)): ?>
                    <div class="w3-responsive">
                        <table class="w3-table-all w3-hoverable w3-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>CPF</th>
                                    <th>Telefone</th>
                                    <th>Data de Nascimento</th>
                                    <th>RG</th>
                                    </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($listaClientes as $cliente): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($cliente['id']); ?></td>
                                        <td><?php echo htmlspecialchars($cliente['nome']); ?></td>
                                        <td><?php echo htmlspecialchars($cliente['cpf'] ?: 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($cliente['telefone']); ?></td>
                                        <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($cliente['dataNascimento']))); ?></td>
                                        <td><?php echo htmlspecialchars($cliente['rg'] ?: 'N/A'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="w3-panel w3-pale-yellow w3-leftbar w3-border-yellow w3-padding-16 w3-round-large">
                        <p><i class="fa fa-info-circle"></i> Nenhum cliente encontrado no sistema.</p>
                    </div>
                <?php endif; ?>

                <p style="margin-top: 20px;">
                    <a href="dashboardAtendente.php" class="w3-button w3-grey w3-round-large">
                        <i class="fa fa-arrow-left"></i> Voltar ao Dashboard
                    </a>
                </p>
            </div>
        </div>
    </div>

    <footer class="w3-container w3-padding-16 w3-light-grey w3-center" style="margin-top:30px;">
        <p>© <?php echo date("Y"); ?> PrePet. Todos os direitos reservados.</p>
    </footer>

</body>
</html>