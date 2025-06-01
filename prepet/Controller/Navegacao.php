<?php
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

session_start();

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

            if (isset($_SESSION['nivel_acesso'])) {
                unset($_SESSION['nivel_acesso']);
            }

            if ($usuario->getIdAcesso() == 1) { 
                $_SESSION['usuario_logado'] = $usuario->getLogin();
                $_SESSION['login_usuario'] = $usuario->getLogin();
                $_SESSION['id_acesso'] = $usuario->getIdAcesso();
                $_SESSION['id_pessoa'] = $usuario->getIdPessoa();
                header('Location: ../View/dashboardCliente.php');
                exit();
            } else { 
                $_SESSION['erro_login'] = "Suas credenciais são de funcionário ou tipo de acesso incorreto. Por favor, use a tela de login apropriada.";
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
        $nivelAcessoForm = $_POST['option_acesso'];

        $usuario = new Usuario();

        if (isset($_SESSION['id_acesso'])) {
        unset($_SESSION['id_acesso']);
        }

        if ($usuario->carregarUsuario($usuarioLogin)) {

            if (password_verify($senhaLogin, $usuario->getSenha()) && $nivelAcessoForm == $usuario->getIdAcesso()) {

                $_SESSION['usuario_logado'] = $usuario->getLogin();
                $_SESSION['nivel_acesso'] = $usuario->getIdAcesso(); 
                $_SESSION['id_pessoa'] = $usuario->getIdPessoa();

                switch ($_SESSION['nivel_acesso']) {
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
    case isset($_POST["btnCadastrar"]): 
        include_once "../View/cadastroClientePessoa.php";
        break;

    // Cadastrar usuário - Pessoa
    case isset($_POST["btnCadastroPessoa"]): 
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
    case isset($_POST["btnCadastroEndereco"]): 
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
    case isset($_POST["btnCadastroUsuario"]): 
        require_once "../Controller/UsuarioController.php";
        $uController = new UsuarioController();

        if ($_POST["senha"] !== $_POST["confirmar_senha"]) {
            $_SESSION['erro_cadastro_usuario'] = "As senhas não coincidem. Por favor, digite novamente.";
            include_once "../View/cadastroClienteUsuario.php";
            break;
        }

        if ($uController->inserir(
            $_POST["login"],
            $_POST["senha"] 
        )) {
            include_once "../View/cadastroRealizado.php";
            unset($_SESSION['dados_pessoa']);
            unset($_SESSION['dados_endereco']);
            unset($_SESSION['id_pessoa']);
        } else {
            $_SESSION['erro_cadastro_usuario'] = "Não foi possível finalizar o cadastro do usuário. Tente novamente.";
            include_once "../View/cadastroClienteUsuario.php";
        }
        break;
    // Tutor cadastra Pet
    case isset($_POST['btnCadastrarPaciente']):
        if (isset($_SESSION['id_pessoa'])) {
            $idPessoaLogada = $_SESSION['id_pessoa'];

            $tutorController = new TutorController();
            $idTutor = $tutorController->garantirTutor($idPessoaLogada);

            if ($idTutor > 0) {
                $pacienteController = new PacienteController();

                $nomePaciente = $_POST['nome'];
                $especiePaciente = $_POST['especie'];
                $racaPaciente = $_POST['raca'];
                $dataNascPaciente = $_POST['data_nasc'];

                if ($pacienteController->inserir(
                    $nomePaciente,
                    $especiePaciente,
                    $racaPaciente,
                    $dataNascPaciente,
                    $idTutor 
                )) {
                    header('Location: ../View/dashboardCliente.php');
                    exit();
                } else {
                    header('Location: ../View/cadastrarPaciente.php');
                    exit();
                }
            } else {
                header('Location: ../View/erro.php?msg=Não foi possível associar o tutor ao paciente.');
                exit();
            }
        } else {
            header('Location: ../View/loginCliente.php');
            exit();
        }
        break;
    // Tutor atualiza Pet
    case isset($_POST['btnAtualizarPaciente']):
        if (isset($_SESSION['id_pessoa']) && isset($_POST['idPaciente'])) {
            $idPessoaLogada = $_SESSION['id_pessoa'];
            $idPaciente = (int)$_POST['idPaciente'];

            $tutorController = new TutorController();
            $idTutor = $tutorController->garantirTutor($idPessoaLogada);

            if ($idTutor > 0) { 
                $pacienteController = new PacienteController();

                $nomePaciente = $_POST['nome'];
                $especiePaciente = $_POST['especie'];
                $racaPaciente = $_POST['raca'];
                $dataNascPaciente = $_POST['data_nasc'];

                if ($pacienteController->atualizar(
                    $idPaciente,
                    $nomePaciente,
                    $especiePaciente,
                    $racaPaciente,
                    $dataNascPaciente,
                    $idTutor
                )) {
                    header('Location: ../View/dashboardCliente.php?msg=Paciente atualizado com sucesso!');
                    exit();
                } else {
                    $_SESSION['erro'] = "Erro ao atualizar paciente. Verifique os dados.";
                    header('Location: ../View/erroCadastro.php?msg=Erro ao atualizar paciente. Verifique os dados.');
                    exit();
                }
            } else {
                $_SESSION['erro'] = "Não foi possível associar o tutor para atualização.";
                header('Location: ../View/erroCadastro.php?msg=Não foi possível associar o tutor para atualização.');
                exit();
            }
        } else {
            $_SESSION['erro'] = "Dados insuficientes para atualização.";
            header('Location: ../View/erroCadastro.php?msg=Dados insuficientes para atualização.');
            exit();
        }
        break;

    // Usuario exclui Pet
    case isset($_POST['btnExcluirPaciente']): {
        if (isset($_POST['idPaciente']) && !empty($_POST['idPaciente'])) {
            $idPaciente = $_POST['idPaciente'];

            $idTutorLogado = 0;
            if (isset($_SESSION['id_tutor_logado'])) {
                $idTutorLogado = $_SESSION['id_tutor_logado'];
            } else {
                $idPessoaLogada = $_SESSION['id_pessoa'];
                $tutorController = new TutorController();
                $idTutorLogado = $tutorController->garantirTutor($idPessoaLogada);
                $_SESSION['id_tutor_logado'] = $idTutorLogado;
            }

            if ($idTutorLogado > 0) {
                $pacienteController = new PacienteController();

                if ($pacienteController->excluir($idPaciente, $idTutorLogado)) {

                    $_SESSION['mensagem_sucesso'] = "Paciente excluído com sucesso!";
                } else {
                    $_SESSION['mensagem_erro'] = "Erro ao excluir paciente. Pode ser que o paciente não pertença a este tutor.";
                }
            } else {
                $_SESSION['mensagem_erro'] = "Erro: ID do tutor não encontrado.";
            }

        } else {
            $_SESSION['mensagem_erro'] = "ID do paciente não fornecido para exclusão.";
            }
            header("Location: ../View/dashboardCliente.php");
            exit();
        }
        break;

    // Atendente agenda Consulta
    case isset($_POST['btnAgendarConsulta']): {
        $agendamentoController = new AgendamentoController();

        $data_agend = $_POST['data_agend'];
        $hora_consulta = $_POST['hora_consulta'];
        $tipo_servico = $_POST['tipo_servico'];
        $observacoes = $_POST['observacoes'];
        $status = "Agendado";
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
        }
        break;
    // Atendente atualiza Consulta
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
            header("Location: ../View/dashboardAtendente.php");
            exit();
        } else {
            $_SESSION['erro'] = "Erro ao atualizar agendamento.";
            header("Location: ../View/editarAgendamento.php?id=" . $id_agendamento);
            exit();
        }
        break;

    // Atendente exclui Consulta
    case isset($_POST['btnCancelarAgendamento']): {
        if (isset($_POST['idAgendamento']) && !empty($_POST['idAgendamento'])) {
            $idAgendamento = (int)$_POST['idAgendamento'];

            $agendamentoController = new AgendamentoController();

            if ($agendamentoController->excluirAgendamento($idAgendamento)) {
                $_SESSION['mensagem_sucesso'] = "Agendamento excluído com sucesso!";
            } else {
                $_SESSION['mensagem_erro'] = "Erro ao excluir agendamento. Tente novamente.";
            }
        } else {
            $_SESSION['mensagem_erro'] = "ID do agendamento não fornecido para exclusão.";
        }
        header("Location: ../View/dashboardAtendente.php");
        exit();
        }
        break;
    // Veterinário registra Procedimento    
    case isset($_POST['btnRegistrarProcedimentoPaciente']):
        if (!isset($_SESSION['id_pessoa']) || !isset($_SESSION['nivel_acesso']) || $_SESSION['nivel_acesso'] != 3) {
            $_SESSION['erro'] = "Acesso negado. Por favor, faça login como veterinário para registrar procedimentos.";
            header("Location: ../View/loginFuncionario.php");
            exit();
        }
        $idPessoaVeterinarioLogado = $_SESSION['id_pessoa'];
        $veterinarioModel = new Veterinario();
        $dadosVeterinario = $veterinarioModel->buscarPorIdPessoa($idPessoaVeterinarioLogado);

        if (!$dadosVeterinario || !isset($dadosVeterinario['id'])) {
            $_SESSION['erro'] = "Erro: Não foi possível encontrar o registro do veterinário associado ao seu login. Verifique o cadastro.";
            header("Location: ../View/dashboardVeterinario.php");
            exit();
        }
        $id_vet_para_procedimento = (int)$dadosVeterinario['id'];
        $tipo = $_POST['tipo'];
        $data_procedimento = $_POST['data_procedimento'];
        $resultado = $_POST['resultado'];
        $status_paciente = $_POST['status_paciente'];
        $diagnostico = $_POST['diagnostico'];

        $id_pac = (int)$_POST['id_pac']; 

        $procedimentoController = new ProcedimentoController();

        if ($procedimentoController->registrarProcedimento(
            $tipo,
            $data_procedimento,
            $resultado,
            $status_paciente,
            $diagnostico,
            $id_vet_para_procedimento,
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
        break;

    // Veterinário registra Documento
    case isset($_POST['btnRegistrarDocumento']):
        if (!isset($_SESSION['id_pessoa']) || !isset($_SESSION['nivel_acesso']) || ($_SESSION['nivel_acesso'] != 3 && $_SESSION['nivel_acesso'] != 1)) {
            $_SESSION['erro'] = "Acesso negado. Por favor, faça login com permissão para registrar documentos.";
            header("Location: ../View/loginFuncionario.php");
            exit();
        }

        $idPessoaVeterinarioLogado = $_SESSION['id_pessoa'];
        $veterinarioModel = new Veterinario();
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
            $id_vet_logado,
            $id_pac
        )) {
            $_SESSION['mensagem'] = "Documento registrado com sucesso para o paciente " . $id_pac . "!";
            header("Location: ../View/dashboardVeterinario.php");
            exit();
        } else {
            $_SESSION['erro'] = "Erro ao registrar o documento. Verifique os dados.";
            header("Location: ../View/registrarDocumento.php?id_paciente=" . $id_pac);
            exit();
        }
        break;
    
    // Atendente cadastra Pessoa
    case isset($_POST["btnAtendenteCadastraPessoa"]):
        if (isset($_SESSION['nivel_acesso']) && $_SESSION['nivel_acesso'] == 2) { // Confirma que é um atendente
            require_once "../Controller/PessoaController.php";
            $pController = new PessoaController();

            // Coleta os dados do formulário
            $nome = $_POST["nome"] ?? null;
            $dataNascimento = $_POST["data_nascimento"] ?? null;
            $telefone = $_POST["telefone"] ?? null;
            $rg = $_POST["rg"] ?? null;
            $cpf = $_POST["cpf"] ?? null;

            if (empty($nome) || empty($dataNascimento) || empty($telefone)) {
                $_SESSION['mensagem_erro'] = "Erro: Nome, Data de Nascimento e Telefone são obrigatórios.";
                header('Location: ../View/atendenteCadastrarPessoa.php');
                exit();
            }
            
            $resultadoInsercao = $pController->inserir(
                $nome,
                $dataNascimento,
                $telefone,
                $rg,
                $cpf
            );

            if ($resultadoInsercao) {
                $_SESSION['mensagem_sucesso'] = "Pessoa cadastrada com sucesso! ID: " . $_SESSION['id_pessoa'];

                unset($_SESSION['Pessoa']); 

                header('Location: ../View/atendenteCadastrarPessoa.php');
                exit();
            } else {
                $_SESSION['mensagem_erro'] = "Erro ao cadastrar pessoa. Verifique os dados ou tente novamente.";
                header('Location: ../View/atendenteCadastrarPessoa.php');
                exit();
            }
        } else {
            // Não é um atendente ou não está logado
            $_SESSION['erro_login'] = "Acesso negado.";
            header('Location: ../View/loginFuncionario.php');
            exit();
        }
        break;

    // Atendente cadastra Paciente
    case isset($_POST['btnAtendenteCadastraPet']):
        if (isset($_SESSION['nivel_acesso']) && $_SESSION['nivel_acesso'] == 2) {
            
            require_once "../Controller/TutorController.php";
            require_once "../Controller/PacienteController.php";

            $id_pessoa_tutor = $_POST['id_pessoa_tutor'] ?? null;
            $nome_pet = $_POST['nome_pet'] ?? null;
            $especie_pet = $_POST['especie_pet'] ?? null;
            $raca_pet = $_POST['raca_pet'] ?? ''; 
            $data_nasc_pet = $_POST['data_nasc_pet'] ?? null;

            // Validação básica
            if (empty($id_pessoa_tutor) || empty($nome_pet) || empty($especie_pet) || empty($data_nasc_pet)) {
                $_SESSION['mensagem_erro'] = "Erro: Tutor, Nome do Pet, Espécie e Data de Nascimento são obrigatórios.";
                header('Location: ../View/atendenteCadastrarPet.php');
                exit();
            }

            $tutorController = new TutorController();
            $pacienteController = new PacienteController();

            $idTutor = $tutorController->garantirTutor((int)$id_pessoa_tutor);

            if ($idTutor > 0) {
                if ($pacienteController->inserir(
                    $nome_pet,
                    $especie_pet,
                    $raca_pet,
                    $data_nasc_pet,
                    $idTutor
                )) {
                    $_SESSION['mensagem_sucesso'] = "Pet '".htmlspecialchars($nome_pet)."' cadastrado com sucesso para o tutor selecionado!";
                } else {
                    $_SESSION['mensagem_erro'] = "Erro ao cadastrar o pet. Tente novamente.";
                }
            } else {
                $_SESSION['mensagem_erro'] = "Erro ao definir o tutor para o pet. Verifique se a pessoa selecionada é válida.";
            }
            header('Location: ../View/atendenteCadastrarPet.php');
            exit();

        } else {
            // Não é um atendente ou não está logado
            $_SESSION['erro_login'] = "Acesso negado.";
            header('Location: ../View/loginFuncionario.php');
            exit();
        }
        break;
    
    case isset($_POST["btnVeterinarioCadastraPessoa"]):
        if (isset($_SESSION['nivel_acesso']) && $_SESSION['nivel_acesso'] == 3) { // Confirma que é um Veterinário
            require_once "../Controller/PessoaController.php";
            $pController = new PessoaController();

            $nome = $_POST["nome"] ?? null;
            $dataNascimento = $_POST["data_nascimento"] ?? null;
            $telefone = $_POST["telefone"] ?? null;
            $rg = $_POST["rg"] ?? null;
            $cpf = $_POST["cpf"] ?? null;

            if (empty($nome) || empty($dataNascimento) || empty($telefone)) {
                $_SESSION['mensagem_erro'] = "Erro: Nome, Data de Nascimento e Telefone são obrigatórios.";
                header('Location: ../View/veterinarioCadastrarPessoa.php');
                exit();
            }
            
            $resultadoInsercao = $pController->inserir(
                $nome,
                $dataNascimento,
                $telefone,
                $rg,
                $cpf
            );

            if ($resultadoInsercao) {
                $_SESSION['mensagem_sucesso'] = "Pessoa cadastrada com sucesso! ID: " . ($_SESSION['id_pessoa'] ?? 'N/D');
                
                unset($_SESSION['Pessoa']);
                
                header('Location: ../View/veterinarioCadastrarPessoa.php'); 
                exit();
            } else {
                $_SESSION['mensagem_erro'] = "Erro ao cadastrar pessoa. Verifique os dados ou tente novamente.";
                header('Location: ../View/veterinarioCadastrarPessoa.php');
                exit();
            }
        } else {
            $_SESSION['erro_login'] = "Acesso negado.";
            header('Location: ../View/loginFuncionario.php');
            exit();
        }
        break;

    case isset($_POST['btnVeterinarioCadastraPet']):
        if (isset($_SESSION['nivel_acesso']) && $_SESSION['nivel_acesso'] == 3) { // Confirma que é um Veterinário
            
            require_once "../Controller/TutorController.php";
            require_once "../Controller/PacienteController.php";

            $id_pessoa_tutor = $_POST['id_pessoa_tutor'] ?? null;
            $nome_pet = $_POST['nome_pet'] ?? null;
            $especie_pet = $_POST['especie_pet'] ?? null;
            $raca_pet = $_POST['raca_pet'] ?? ''; 
            $data_nasc_pet = $_POST['data_nasc_pet'] ?? null;

            if (empty($id_pessoa_tutor) || empty($nome_pet) || empty($especie_pet) || empty($data_nasc_pet)) {
                $_SESSION['mensagem_erro'] = "Erro: Tutor, Nome do Pet, Espécie e Data de Nascimento são obrigatórios.";
                header('Location: ../View/veterinarioCadastrarPet.php');
                exit();
            }

            $tutorController = new TutorController();
            $pacienteController = new PacienteController();

            $idTutor = $tutorController->garantirTutor((int)$id_pessoa_tutor);

            if ($idTutor > 0) {
                if ($pacienteController->inserir(
                    $nome_pet,
                    $especie_pet,
                    $raca_pet,
                    $data_nasc_pet,
                    $idTutor
                )) {
                    $_SESSION['mensagem_sucesso'] = "Pet '".htmlspecialchars($nome_pet)."' cadastrado com sucesso para o tutor selecionado!";
                } else {
                    $_SESSION['mensagem_erro'] = "Erro ao cadastrar o pet. Tente novamente.";
                }
            } else {
                $_SESSION['mensagem_erro'] = "Erro ao definir o tutor para o pet. Verifique se a pessoa selecionada é válida.";
            }
            header('Location: ../View/veterinarioCadastrarPet.php');
            exit();

        } else {
            $_SESSION['erro_login'] = "Acesso negado.";
            header('Location: ../View/loginFuncionario.php');
            exit();
        }
        break;
    
    // Veterinário Atualiza Item do Estoque
    case isset($_POST['btnAtualizarItemEstoque']):
        if (isset($_SESSION['nivel_acesso']) && $_SESSION['nivel_acesso'] == 3) { // Acesso Veterinário
            
            require_once __DIR__ . "/../Controller/EstoqueController.php";

            $id_item = $_POST['id_item'] ?? null;
            $nome_produto = $_POST['nome_produto'] ?? '';
            $quantidade = $_POST['quantidade'] ?? '';
            $fornecedor = $_POST['fornecedor'] ?? '';

            if (empty($id_item) || !is_numeric($id_item) || empty($nome_produto) || !isset($quantidade) || $quantidade === '' || empty($fornecedor)) {
                $_SESSION['mensagem_erro_estoque_edit'] = "Erro: Todos os campos são obrigatórios para atualizar.";
                header('Location: ../View/editarItemEstoque.php?id_item=' . $id_item);
                exit();
            }
            if (!is_numeric($quantidade) || (int)$quantidade < 0) {
                $_SESSION['mensagem_erro_estoque_edit'] = "Erro: A quantidade deve ser um número igual ou maior que zero.";
                header('Location: ../View/editarItemEstoque.php?id_item=' . $id_item);
                exit();
            }

            $estoqueController = new EstoqueController();
            if ($estoqueController->atualizarItemEstoque((int)$id_item, $nome_produto, (int)$quantidade, $fornecedor)) {
                $_SESSION['mensagem_sucesso_estoque'] = "Item ID ".htmlspecialchars($id_item)." atualizado com sucesso no estoque!";
                header('Location: ../View/listaEstoque.php');
                exit();
            } else {
                $erroMsg = $estoqueController->getMensagemErro() ?: "Ocorreu um erro ao atualizar o item do estoque.";
                $_SESSION['mensagem_erro_estoque_edit'] = htmlspecialchars($erroMsg);
                header('Location: ../View/editarItemEstoque.php?id_item=' . $id_item);
                exit();
            }
        } else {
            $_SESSION['erro_login'] = "Acesso negado.";
            header('Location: ../View/loginFuncionario.php');
            exit();
        }
        break;
    
        // Botão de Sair (Logout)
    case isset($_POST['btnSair']):
        session_unset();    // Remove a sessão
        session_destroy();  // Destroi a sessão
        header('Location: ../View/telaInicial.php');
        exit();
        break;

    default:
        header('Location: ../View/telaInicial.php');
        exit();
        break;
}
?>