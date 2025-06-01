<?php
// View/alterarDadosUsuario.php

if (!isset($_SESSION)) {
    session_start();
}

$urlRetorno = '../View/telaInicial.php';

if (isset($_SESSION['nivel_acesso'])) {
    switch ($_SESSION['nivel_acesso']) {
        case 2: // Atendente
            $urlRetorno = '../View/dashboardAtendente.php';
            break;
        case 3: // Veterinário
            $urlRetorno = '../View/dashboardVeterinario.php';
            break;
        default:
            $urlRetorno = '../View/telaInicial.php';
            break;
    }
} elseif (isset($_SESSION['id_acesso']) && $_SESSION['id_acesso'] == 1) { // SENÃO, verifica se é um Cliente
    $urlRetorno = '../View/dashboardCliente.php';
}

// Redireciona se o usuário não estiver logado ou não tiver um nível de acesso reconhecido
if (!isset($_SESSION['usuario_logado']) ||
    (!isset($_SESSION['nivel_acesso']) && (!isset($_SESSION['id_acesso']) || $_SESSION['id_acesso'] != 1))) {
    session_unset(); 
    session_destroy();
    header("Location: ../View/loginCliente.php");
    exit();
}

require_once '../Controller/UsuarioController.php'; 

$mensagem = '';
$objUsuario = null; 
$objPessoa = null;  

$usuarioController = new UsuarioController();

if (isset($_SESSION['usuario_logado'])) {
    $dadosCarregados = $usuarioController->buscarDadosUsuarioLogado($_SESSION['usuario_logado']); 
    if ($dadosCarregados && isset($dadosCarregados['usuario']) && $dadosCarregados['usuario']->getLogin()) {
        $objUsuario = $dadosCarregados['usuario'];
        if (isset($dadosCarregados['pessoa']) && $dadosCarregados['pessoa']->getID()) { 
            $objPessoa = $dadosCarregados['pessoa'];
        } else {
        }
    } else {
        $mensagemErroController = $usuarioController->getMensagemErro();
        $mensagem = '<div class="w3-panel w3-red w3-round-large w3-padding-16">
                        <h3>Erro!</h3>
                        <p>' . htmlspecialchars($mensagemErroController ?: 'Não foi possível carregar os dados do usuário.') . '</p>
                      </div>';
        unset($_POST); 
    }
} else {
    $mensagem = '<div class="w3-panel w3-red w3-round-large w3-padding-16">
                    <h3>Erro!</h3>
                    <p>Sessão inválida ou login de usuário não encontrado na sessão.</p>
                  </div>';
    unset($_POST); 
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $objUsuario) { // Garante que $objUsuario foi carregado
    $loginAtual = $_SESSION['usuario_logado']; 

    $novoLogin = $_POST['login'] ?? $objUsuario->getLogin();
    $senha = $_POST['senha'] ?? '';
    $confirmarSenha = $_POST['confirmar_senha'] ?? '';
    
    $nome = $_POST['nome'] ?? '';
    $dataNascimento = $_POST['data_nascimento'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $rg = $_POST['rg'] ?? '';
    $cpf = $_POST['cpf'] ?? '';

    if (!empty($senha) && $senha !== $confirmarSenha) {
        $mensagem = '<div class="w3-panel w3-red w3-round-large w3-padding-16">
                        <h3>Erro!</h3>
                        <p>As senhas digitadas não coincidem.</p>
                      </div>';
    } else {
        $resultado = $usuarioController->alterarDadosUsuario(
            $loginAtual,      
            $novoLogin,       
            $senha,           
            $objUsuario->getIdPessoa(), 
            $nome,
            $dataNascimento,
            $telefone,
            $rg,
            $cpf
        );

        if ($resultado) {
            $mensagem = '<div class="w3-panel w3-green w3-round-large w3-padding-16">
                            <h3>Sucesso!</h3>
                            <p>Dados atualizados com sucesso!</p>
                          </div>';
            
            if ($loginAtual !== $novoLogin) {
                $_SESSION['usuario_logado'] = $novoLogin;
            }
            $dadosCarregados = $usuarioController->buscarDadosUsuarioLogado($_SESSION['usuario_logado']); 
            if ($dadosCarregados && isset($dadosCarregados['usuario'])) {
                $objUsuario = $dadosCarregados['usuario'];
                if (isset($dadosCarregados['pessoa']) && $dadosCarregados['pessoa']->getID()) {
                     $objPessoa = $dadosCarregados['pessoa'];
                }
            }
        } else {
            $mensagemErroController = $usuarioController->getMensagemErro();
            $mensagem = '<div class="w3-panel w3-red w3-round-large w3-padding-16">
                            <h3>Erro!</h3>
                            <p>' . htmlspecialchars($mensagemErroController ?: 'Não foi possível atualizar os dados.') . '</p>
                          </div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Dados Cadastrais - PrePet</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/711de5e590.js" crossorigin="anonymous"></script>
    <style>
        body, html {
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
            padding-top: 40px; /* Adiciona espaço no topo */
            padding-bottom: 40px; /* Adiciona espaço na base */
            background-attachment: fixed;
        }
        .main-container-wrapper { /* Novo wrapper para centralização */
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            min-height: calc(100vh - 80px);
        }
        .w3-card-4 {
            width:90%;
            max-width:600px;
            background-color: white; 
        }
    </style>
</head>
<body>
    <div class="main-container-wrapper">
        <div class="w3-card-4 w3-padding-large w3-round-large">
            <h2><i class="fa fa-user-circle"></i> Alterar Dados Cadastrais</h2>
            <p>Preencha os campos abaixo para atualizar suas informações.</p>

            <?php echo $mensagem; ?>

            <?php if ($objUsuario && $objUsuario->getLogin()): // Verifica se objUsuario é válido antes de usar ?>
            <form action="alterarDadosUsuario.php" method="POST" class="w3-container w3-margin-top">
                <h3><i class="fa fa-user-circle"></i> Dados de Acesso</h3>
                <p>
                    <label for="login" class="w3-text-grey"><b>Login:</b></label>
                    <input class="w3-input w3-border w3-round-large" type="text" id="login" name="login" value="<?php echo htmlspecialchars($objUsuario->getLogin()); ?>" required>
                </p>
                <p>
                    <label for="senha" class="w3-text-grey"><b>Nova Senha (deixe em branco para não alterar):</b></label>
                    <input class="w3-input w3-border w3-round-large" type="password" id="senha" name="senha" placeholder="Digite a nova senha">
                </p>
                <p>
                    <label for="confirmar_senha" class="w3-text-grey"><b>Confirmar Nova Senha:</b></label>
                    <input class="w3-input w3-border w3-round-large" type="password" id="confirmar_senha" name="confirmar_senha" placeholder="Confirme a nova senha">
                </p>

                <h3 class="w3-border-top w3-padding-top w3-margin-top"><i class="fa-solid fa-address-card"></i> Dados Pessoais</h3>
                <p>
                    <label for="nome" class="w3-text-grey"><b>Nome:</b></label>
                    <input class="w3-input w3-border w3-round-large" type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($objPessoa ? $objPessoa->getNome() : ''); ?>" required>
                </p>
                <p>
                    <label for="data_nascimento" class="w3-text-grey"><b>Data de Nascimento:</b></label>
                    <input class="w3-input w3-border w3-round-large" type="date" id="data_nascimento" name="data_nascimento" value="<?php echo htmlspecialchars($objPessoa ? $objPessoa->getDataNascimento() : ''); ?>" required>
                </p>
                <p>
                    <label for="telefone" class="w3-text-grey"><b>Telefone:</b></label>
                    <input class="w3-input w3-border w3-round-large" type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($objPessoa ? $objPessoa->getTelefone() : ''); ?>" required>
                </p>
                <p>
                    <label for="rg" class="w3-text-grey"><b>RG:</b></label>
                    <input class="w3-input w3-border w3-round-large" type="text" id="rg" name="rg" value="<?php echo htmlspecialchars($objPessoa ? $objPessoa->getRG() : ''); ?>">
                </p>
                <p>
                    <label for="cpf" class="w3-text-grey"><b>CPF:</b></label>
                    <input class="w3-input w3-border w3-round-large" type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($objPessoa ? $objPessoa->getCPF() : ''); ?>">
                </p>
                <p>
                    <button type="submit" class="w3-button w3-green w3-block w3-round-large w3-margin-top">
                        <i class="fa fa-save"></i> Salvar Alterações
                    </button>
                </p>
            </form>
            <?php else: ?>
                <p class="w3-text-red">Não foi possível carregar os dados do usuário para edição.</p>
            <?php endif; ?>

            <p style="margin-top: 20px;">
                <a href="<?php echo htmlspecialchars($urlRetorno); ?>" class="w3-button w3-red w3-block w3-round-large">
                    <i class="fa fa-arrow-circle-left"></i> Voltar ao Dashboard
                </a>
            </p>
        </div>
    </div>
    <script>
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(event) {
                const senha = document.getElementById('senha').value;
                const confirmarSenha = document.getElementById('confirmar_senha').value;
                if (senha !== '' && senha !== confirmarSenha) {
                    alert('As novas senhas digitadas não coincidem! Por favor, verifique.');
                    event.preventDefault(); 
                }
            });
        }
    </script>
</body>
</html>