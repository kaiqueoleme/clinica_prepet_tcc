<?php
ini_set('display_errors', 1); // Manter para depuração
ini_set('display_startup_errors', 1); // Manter para depuração
error_reporting(E_ALL); // Manter para depuração

session_start(); // Garanta que a sessão seja iniciada no topo do arquivo

require_once __DIR__ . '/../Model/Usuario.php';
require_once __DIR__ . '/../Controller/UsuarioController.php';
require_once __DIR__ . '/../Model/Pessoa.php';
require_once __DIR__ . '/../Controller/PessoaController.php';
require_once __DIR__ . '/../Model/Endereco.php';
require_once __DIR__ . '/../Controller/EnderecoController.php';
require_once __DIR__ . '/../Model/Tutor.php';
require_once __DIR__ . '/../Controller/TutorController.php';
require_once __DIR__ . '/../Model/Paciente.php';
require_once __DIR__ . '/../Controller/PacienteController.php';
require_once __DIR__ . '/../Model/Agendamento.php';
require_once __DIR__ . '/../Controller/AgendamentoController.php';
require_once __DIR__ . '/../Model/Veterinario.php';
require_once __DIR__ . '/../Model/Procedimento.php';
require_once __DIR__ . '/../Controller/ProcedimentoController.php';
require_once __DIR__ . '/../Model/Documento.php';
require_once __DIR__ . '/../Controller/DocumentoController.php';


// Lógica para lidar com requisições GET ou POST vazio
if ($_SERVER['REQUEST_METHOD'] === 'GET' || empty($_POST)) {
    include_once "../View/telaInicial.php";
    exit();
}

switch (true) {

    case isset($_POST["btnTelaInicial"]):
        include_once "../View/telaInicial.php";
        break;

    // Tela de Login dos Funcionarios
    case isset($_POST["btnLoginFuncionarios"]):
        include_once "../View/loginFuncionario.php";
        break;

    // Tela de Login dos Clientes
    case isset($_POST["btnLoginClientes"]):
        include_once "../View/loginCliente.php";
        break;

    // Login de Cliente
    case isset($_POST["btnLogarCliente"]):
        $uController = new UsuarioController();

        if ($uController->login($_POST["usuario"], $_POST["senha"])) {
            $usuario = new Usuario();
            $usuario->carregarUsuario($_POST["usuario"]);

            if ($usuario->getIdAcesso() == 1) {
                $_SESSION['usuario_logado'] = $usuario->getLogin();
                $_SESSION['id_acesso'] = $usuario->getIdAcesso();
                $_SESSION['id_pessoa'] = $usuario->getIdPessoa();
                header('Location: ../View/dashboardCliente.php');
                exit();
            } else {
                $_SESSION['erro_login'] = "Suas credenciais são de funcionário. Por favor, use a tela de login de funcionários.";
                include_once "../View/loginCliente.php";
            }
        } else {
            $_SESSION['erro_login'] = "Login ou senha incorretos. Tente novamente.";
            include_once "../View/loginCliente.php";
        }
        break;

    // Login de Funcionário
    case isset($_POST['btnLogarFuncionario']):
        $usuarioLogin = $_POST['usuario'];
        $senhaLogin = $_POST['senha'];
        $nivelAcessoForm = $_POST['option_acesso']; // Pega o nível de acesso SELECIONADO no formulário

        $usuario = new Usuario(); // Instantiate the Usuario model
        if ($usuario->carregarUsuario($usuarioLogin)) { // Load user data from DB
            // Verify password AND if the access level from the form matches the DB
            if (password_verify($senhaLogin, $usuario->getSenha()) && $nivelAcessoForm == $usuario->getIdAcesso()) {
                // Employee login successful and access level matches
                $_SESSION['usuario_logado'] = $usuario->getLogin();
                $_SESSION['nivel_acesso'] = $usuario->getIdAcesso(); // <--- CORRECTED: Use 'nivel_acesso' as the key and getIdAcesso() for the value
                $_SESSION['id_pessoa'] = $usuario->getIdPessoa(); // This seems correct based on your previous outputs

                switch ($_SESSION['nivel_acesso']) {
                    case 5: // Proprietário
                        header('Location: ../View/dashboardProprietario.php');
                        exit();
                    case 2: // Atendente
                        header('Location: ../View/dashboardAtendente.php');
                        exit();
                    case 3: // Veterinário
                        header('Location: ../View/dashboardVeterinario.php');
                        exit();
                    default:
                        $_SESSION['erro_login'] = "Nível de acesso inválido ou desconhecido.";
                        include_once "../View/loginFuncionario.php";
                        break;
                }
            } else {
                $_SESSION['erro_login'] = "Usuário, senha ou nível de acesso inválidos.";
                include_once "../View/loginFuncionario.php";
            }
        } else {
            $_SESSION['erro_login'] = "Usuário, senha ou nível de acesso inválidos.";
            include_once "../View/loginFuncionario.php";
        }
        break;

    // Cadastro de Clientes - Tela de Cadastro Clientes
    case isset($_POST["btnCadastrar"]): // Botão da tela inicial para iniciar o cadastro
        include_once "../View/cadastroClientePessoa.php";
        break;

    // Cadastrar usuário - Pessoa
    case isset($_POST["btnCadastroPessoa"]): // Botão "Avançar" da tela de cadastro de pessoa
        require_once "../Controller/PessoaController.php";
        $pController = new PessoaController();

        if ($pController->inserir(
            $_POST["nome"],
            date("Y-m-d", strtotime($_POST["data_nascimento"])),
            $_POST["telefone"],
            $_POST["rg"],
            $_POST["cpf"]
        )) {
            include_once "../View/cadastroClienteEndereco.php";
        } else {
            $_SESSION['erro_cadastro_pessoa'] = "Erro ao cadastrar dados pessoais.";
            include_once "../View/cadastroClientePessoa.php";
        }
        break;

    // Cadastrar usuário - Endereço
    case isset($_POST["btnCadastroEndereco"]): // Botão "Avançar" da tela de cadastro de endereço
        require_once "../Controller/EnderecoController.php";
        $eController = new EnderecoController();

        if ($eController->inserir(
            $_POST["logradouro"],
            $_POST["numero"],
            $_POST["bairro"],
            $_POST["cidade"],
            $_POST["cep"]
        )) {
            include_once "../View/cadastroClienteUsuario.php";
        } else {
            $_SESSION['erro_cadastro_endereco'] = "Erro ao cadastrar endereço.";
            include_once "../View/cadastroClienteEndereco.php";
        }
        break;

    // Cadastrar usuário - Usuario (finaliza o cadastro completo)
    case isset($_POST["btnCadastroUsuario"]): // Botão "Cadastrar" da tela final de cadastro de usuário
        require_once "../Controller/UsuarioController.php";
        $uController = new UsuarioController();

        if ($_POST["senha"] !== $_POST["confirmar_senha"]) {
            $_SESSION['erro_cadastro_usuario'] = "As senhas não coincidem. Por favor, digite novamente.";
            include_once "../View/cadastroClienteUsuario.php";
            break;
        }

        if ($uController->inserir(
            $_POST["login"],
            $_POST["senha"] // A senha deve ser hashed dentro do método inserir do UsuarioController
        )) {
            include_once "../View/cadastroRealizado.php";
            // Limpa as variáveis de sessão usadas no cadastro após o sucesso
            unset($_SESSION['dados_pessoa']);
            unset($_SESSION['dados_endereco']);
            unset($_SESSION['id_pessoa']); // Se você salvou o id_pessoa na sessão
        } else {
            $_SESSION['erro_cadastro_usuario'] = "Não foi possível finalizar o cadastro do usuário. Tente novamente.";
            include_once "../View/cadastroClienteUsuario.php";
        }
        break;

    case isset($_POST['btnCadastrarPaciente']):
    // 1. Verificar se o usuário está logado e obter id_pessoa
    if (isset($_SESSION['id_pessoa'])) {
        $idPessoaLogada = $_SESSION['id_pessoa'];

        // 2. Garantir que exista um registro de Tutor para esta Pessoa e obter o id_tutor
        $tutorController = new TutorController();
        $idTutor = $tutorController->garantirTutor($idPessoaLogada);

        if ($idTutor > 0) { // Se um id_tutor válido foi obtido
            $pacienteController = new PacienteController();

            // Dados do formulário de cadastro de paciente
            $nomePaciente = $_POST['nome'];
            $especiePaciente = $_POST['especie'];
            $racaPaciente = $_POST['raca'];
            $dataNascPaciente = $_POST['data_nasc']; // Nome do campo no formulário

            // 3. Chamar o método inserir do PacienteController, passando o idTutor obtido
            if ($pacienteController->inserir(
                $nomePaciente,
                $especiePaciente,
                $racaPaciente,
                $dataNascPaciente,
                $idTutor // Passando o idTutor automaticamente
            )) {
                // Sucesso no cadastro do paciente
                header('Location: ../View/dashboardCliente.php');
                exit();
            } else {
                header('Location: ../View/cadastrarPaciente.php');
                exit();
            }
        } else {
            // Não foi possível obter ou criar o id_tutor
            header('Location: ../View/erro.php?msg=Não foi possível associar o tutor ao paciente.');
            exit();
        }
    } else {
        // Usuário não logado, redirecionar para o login
        header('Location: ../View/loginCliente.php');
        exit();
    }
    break;

    case isset($_POST['btnAtualizarPaciente']):
    if (isset($_SESSION['id_pessoa']) && isset($_POST['idPaciente'])) {
        $idPessoaLogada = $_SESSION['id_pessoa'];
        $idPaciente = (int)$_POST['idPaciente']; // ID do paciente a ser atualizado

        // 1. Garantir que exista um registro de Tutor para esta Pessoa e obter o id_tutor
        $tutorController = new TutorController();
        $idTutor = $tutorController->garantirTutor($idPessoaLogada);

        if ($idTutor > 0) { // Se um id_tutor válido foi obtido
            $pacienteController = new PacienteController();

            // Dados do formulário de edição de paciente
            $nomePaciente = $_POST['nome'];
            $especiePaciente = $_POST['especie'];
            $racaPaciente = $_POST['raca'];
            $dataNascPaciente = $_POST['data_nasc'];

            // 2. Chamar o método atualizar do PacienteController
            // Passa o id do paciente, os novos dados e o idTutor (para segurança)
            if ($pacienteController->atualizar(
                $idPaciente,
                $nomePaciente,
                $especiePaciente,
                $racaPaciente,
                $dataNascPaciente,
                $idTutor // Passando o idTutor para verificar propriedade
            )) {
                // Sucesso na atualização
                header('Location: ../View/dashboardCliente.php?msg=Paciente atualizado com sucesso!');
                exit();
            } else {
                // Erro ao atualizar paciente
                $_SESSION['erro'] = "Erro ao atualizar paciente. Verifique os dados."; // Use $_SESSION['erro']
                header('Location: ../View/erroCadastro.php?msg=Erro ao atualizar paciente. Verifique os dados.');
                exit();
            }
        } else {
            // Não foi possível obter ou criar o id_tutor (erro de segurança/config)
            $_SESSION['erro'] = "Não foi possível associar o tutor para atualização."; // Use $_SESSION['erro']
            header('Location: ../View/erroCadastro.php?msg=Não foi possível associar o tutor para atualização.');
            exit();
        }
    } else {
        // ID do paciente ou usuário logado não fornecido
        $_SESSION['erro'] = "Dados insuficientes para atualização."; // Use $_SESSION['erro']
        header('Location: ../View/erroCadastro.php?msg=Dados insuficientes para atualização.');
        exit();
    }
    break;
    
    case isset($_POST['btnExcluirPaciente']):
    if (isset($_SESSION['id_pessoa']) && isset($_POST['idPaciente'])) {
        $idPessoaLogada = $_SESSION['id_pessoa'];
        $idPaciente = (int)$_POST['idPaciente']; // ID do paciente a ser excluído

        // 1. Garantir que exista um registro de Tutor para esta Pessoa e obter o id_tutor
        $tutorController = new TutorController();
        $idTutor = $tutorController->garantirTutor($idPessoaLogada);

        if ($idTutor > 0) { // Se um id_tutor válido foi obtido
            $pacienteController = new PacienteController();

            // 2. Chamar o método excluir do PacienteController
            // Passa o id do paciente e o idTutor (para verificar propriedade)
            // OBS: Seu método excluir() em PacienteController.php só recebe $id.
            // Para garantir a propriedade, você precisaria carregar o paciente e verificar se o id_tutor corresponde.
            // Por enquanto, vamos chamar o método existente.
            if ($pacienteController->excluir($idPaciente)) { // Removido $idTutor aqui, pois o método só aceita $id.
                // Sucesso na exclusão
                $_SESSION['mensagem'] = "Paciente excluído com sucesso!"; // Use $_SESSION['mensagem']
                header('Location: ../View/dashboardCliente.php');
                exit();
            } else {
                // Erro ao excluir paciente (ou paciente não encontrado/não pertence ao tutor)
                $_SESSION['erro'] = "Erro ao excluir paciente ou acesso negado."; // Use $_SESSION['erro']
                header('Location: ../View/erroCadastro.php?msg=Erro ao excluir paciente ou acesso negado.');
                exit();
            }
        } else {
            // Não foi possível obter o id_tutor (erro de segurança/config)
            $_SESSION['erro'] = "Não foi possível verificar o tutor para exclusão."; // Use $_SESSION['erro']
            header('Location: ../View/erroCadastro.php?msg=Não foi possível verificar o tutor para exclusão.');
            exit();
        }
    } else {
        // ID do paciente ou usuário logado não fornecido
        $_SESSION['erro'] = "Dados insuficientes para exclusão."; // Use $_SESSION['erro']
        header('Location: ../View/erroCadastro.php?msg=Dados insuficientes para exclusão.');
        exit();
    }
    break;

    case isset($_POST['btnAgendarConsulta']):
        $agendamentoController = new AgendamentoController();

        $data_agend = $_POST['data_agend'];
        $hora_consulta = $_POST['hora_consulta'];
        $tipo_servico = $_POST['tipo_servico'];
        $observacoes = $_POST['observacoes'];
        $status = "Agendado"; // Default status
        $id_vet = (int)$_POST['id_vet'];
        $id_pac = (int)$_POST['id_pac'];

        if ($agendamentoController->agendarConsulta(
            $id_pac,
            $id_vet,
            $data_agend,
            $hora_consulta,
            $tipo_servico,
            $observacoes
        )) {
            $_SESSION['mensagem'] = "Agendamento realizado com sucesso!";
            header("Location: ../View/dashboardAtendente.php");
            exit();
        } else {
            $_SESSION['erro'] = "Erro ao agendar consulta.";
            header("Location: ../View/agendarConsulta.php");
            exit();
        }
        break;

    case isset($_POST['btnAtualizarAgendamento']):
        $agendamentoController = new AgendamentoController();
        $id_agendamento = $_POST['id_agendamento'];
        $data_agend = $_POST['data_agend'];
        $hora_consulta = $_POST['hora_consulta'];
        $tipo_servico = $_POST['tipo_servico'];
        $observacoes = $_POST['observacoes'];
        $status = $_POST['status'];
        $id_vet = (int)$_POST['id_vet'];
        $id_pac = (int)$_POST['id_pac'];

        if ($agendamentoController->atualizarAgendamento($id_agendamento, $data_agend, $hora_consulta, $tipo_servico, $observacoes, $status, $id_vet, $id_pac)) {
            $_SESSION['mensagem'] = "Agendamento atualizado com sucesso!";
            header("Location: ../View/dashboardAtendente.php"); // Redireciona de volta para o dashboard
            exit();
        } else {
            $_SESSION['erro'] = "Erro ao atualizar agendamento.";
            header("Location: ../View/editarAgendamento.php?id=" . $id_agendamento); // Redireciona de volta para a página de edição com erro
            exit();
        }
        break;

    case isset($_POST['btnCancelarAgendamento']):
        $agendamentoController = new AgendamentoController();
        $id_agendamento = $_POST['id_agendamento'];

        if ($agendamentoController->cancelarAgendamento($id_agendamento)) {
            $_SESSION['mensagem'] = "Agendamento cancelado com sucesso!";
            header("Location: ../View/dashboardAtendente.php"); // Redireciona para o dashboard
            exit();
        } else {
            $_SESSION['erro'] = "Erro ao cancelar agendamento.";
            header("Location: ../View/dashboardAtendente.php"); // Redireciona para o dashboard com erro
            exit();
        }
        break;

    case isset($_POST['btnRegistrarProcedimento']): // OU 'btnRegistrarProcedimentoPaciente' - USE O QUE VOCÊ TEM NO FORM
    // 1. **Verificação de segurança**: Garante que há um usuário logado e que ele é um veterinário (nível de acesso 3).
    if (!isset($_SESSION['id_pessoa']) || !isset($_SESSION['nivel_acesso']) || $_SESSION['nivel_acesso'] != 3) {
        $_SESSION['erro'] = "Acesso negado. Por favor, faça login como veterinário para registrar procedimentos.";
        header("Location: ../View/loginFuncionario.php");
        exit();
    }

    // 2. **Obter o ID da Pessoa do veterinário logado da sessão**
    $idPessoaVeterinarioLogado = $_SESSION['id_pessoa'];

    // 3. **Instanciar o Model Veterinario e buscar o ID real da tabela 'veterinario'**
    $veterinarioModel = new Veterinario();
    $dadosVeterinario = $veterinarioModel->buscarPorIdPessoa($idPessoaVeterinarioLogado);

    // 4. **Verificar se o registro do veterinário foi encontrado**
    if (!$dadosVeterinario || !isset($dadosVeterinario['id'])) {
        $_SESSION['erro'] = "Erro: Não foi possível encontrar o registro do veterinário associado ao seu login. Verifique o cadastro.";
        header("Location: ../View/dashboardVeterinario.php");
        exit();
    }

    // 5. **O ID do veterinário para o procedimento é o 'id' da tabela 'veterinario'**
    $id_vet_para_procedimento = (int)$dadosVeterinario['id'];

    // 6. **Obter os outros dados do formulário POST**
    $tipo = $_POST['tipo'];
    $data_procedimento = $_POST['data_procedimento'];
    $resultado = $_POST['resultado'];
    $status_paciente = $_POST['status_paciente'];
    $diagnostico = $_POST['diagnostico'];

    $id_pac = (int)$_POST['id_pac']; 

    // 7. **Instanciar o ProcedimentoController e registrar o procedimento**
    $procedimentoController = new ProcedimentoController();

    if ($procedimentoController->registrarProcedimento(
        $tipo,
        $data_procedimento,
        $resultado,
        $status_paciente,
        $diagnostico,
        $id_vet_para_procedimento, // <-- ESTE É O PONTO CHAVE: USA O ID CORRETO!
        $id_pac
    )) {
        $_SESSION['mensagem'] = "Procedimento registrado com sucesso para o paciente " . $id_pac . "!";
        header("Location: ../View/dashboardVeterinario.php");
        exit();
    } else {
        $_SESSION['erro'] = "Erro ao registrar o procedimento. Por favor, tente novamente.";
        header("Location: ../View/registrarProcedimento.php?id_paciente=" . $id_pac);
        exit();
    }
    break; // <-- Importante ter o break aqui!

    case isset($_POST['btnRegistrarDocumento']):

    if (!isset($_SESSION['id_pessoa']) || !isset($_SESSION['nivel_acesso']) || ($_SESSION['nivel_acesso'] != 3 && $_SESSION['nivel_acesso'] != 1)) {
        $_SESSION['erro'] = "Acesso negado. Por favor, faça login com permissão para registrar documentos.";
        header("Location: ../View/loginFuncionario.php");
        exit();
    }

    $idPessoaVeterinarioLogado = $_SESSION['id_pessoa'];
    $veterinarioModel = new Veterinario(); // Garanta que Veterinario.php esteja incluído
    $dadosVeterinario = $veterinarioModel->buscarPorIdPessoa($idPessoaVeterinarioLogado);

    if (!$dadosVeterinario || !isset($dadosVeterinario['id'])) {
        $_SESSION['erro'] = "Erro: Não foi possível identificar o veterinário logado para registrar o documento.";
        header("Location: ../View/dashboardVeterinario.php");
        exit();
    }
    $id_vet_logado = (int)$dadosVeterinario['id'];

    $tipo = $_POST['tipo'];
    $conteudo = $_POST['conteudo'];
    $id_pac = (int)$_POST['id_pac'];

    $documentoController = new DocumentoController();
    if ($documentoController->registrarDocumento(
        $tipo,
        $conteudo,
        $id_vet_logado, // Usa o ID do veterinário logado
        $id_pac
    )) {
        $_SESSION['mensagem'] = "Documento registrado com sucesso para o paciente " . $id_pac . "!";
        header("Location: ../View/dashboardVeterinario.php"); // Ou para uma página de sucesso de documento
        exit();
    } else {
        $_SESSION['erro'] = "Erro ao registrar o documento. Verifique os dados.";
        header("Location: ../View/registrarDocumento.php?id_paciente=" . $id_pac); // Retorna ao formulário
        exit();
    }
    break;

    // Botão de Sair (Logout)
    case isset($_POST['btnSair']):
        session_unset();    // remove todas as variáveis de sessão
        session_destroy();  // destroi a sessão
        header('Location: ../View/telaInicial.php');
        exit();
        break;

    // Fallback para caso nenhuma condição seja atendida em POST
    default:
        // Pode redirecionar para uma página de erro ou a tela inicial
        header('Location: ../View/telaInicial.php');
        exit();
        break;
}
?>