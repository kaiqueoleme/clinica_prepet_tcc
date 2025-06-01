<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica PrePet</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>
        body,h1,h2,h3,h4,h5,h6 {font-family: "Montserrat", sans-serif}
        .prepet-logo {
            height: 40px;
        }
        .hero {
            background: url('https://www.shutterstock.com/image-photo/cropped-image-handsome-male-veterinarian-600nw-2140114091.jpg') no-repeat center center;
            background-size: cover;
            height: 400px;
        }
        .hero-text {
            color: white;
            text-shadow: 1px 1px 4px #000;
        }
    </style>
</head>
<body class="w3-light-grey">
    
    <form action="../Controller/Navegacao.php" method="post">
        <input type="hidden" name="nome_form" value="frmLogin" />
        
        <!-- Navbar -->
        <div class="w3-bar w3-cyan w3-large">
            <a href="#home" class="w3-bar-item w3-mobile">
                <i class="material-icons">pets</i>
            </a>
            <a href="#sobre" class="w3-bar-item w3-button w3-mobile">Sobre</a>
            <a href="#servicos" class="w3-bar-item w3-button w3-mobile">Serviços</a>
            <a href="#equipe" class="w3-bar-item w3-button w3-mobile">Equipe</a>
            <a href="#contato" class="w3-bar-item w3-button w3-mobile">Contato</a>
            <button name="btnLoginFuncionarios" class="w3-bar-item w3-button w3-mobile w3-right" type="submit"><i class="fa fa-user"></i> Login Funcionário</button>
            <button name="btnLoginClientes" class="w3-bar-item w3-button w3-mobile w3-right"><i class="fa fa-user"></i> Login Cliente</button>
        </div>
    </form>

    <!-- Hero Section -->
    <header class="hero w3-display-container" id="home">
        <div class="w3-display-middle w3-center hero-text">
            <h1 class="w3-xxlarge">Bem-vindo à Clínica PrePet</h1>
            <p>Cuidado, amor e saúde para seu melhor amigo!</p>
        </div>
    </header>

    <!-- Sobre -->
    <div class="w3-container w3-padding-64" id="sobre">
        <h2 class="w3-center">Sobre a PrePet</h2>
        <p class="w3-center w3-large">Nossa missão é cuidar da saúde e bem-estar dos animais com carinho e profissionalismo.</p>
        <p>A Clínica PrePet oferece atendimento veterinário de qualidade com uma equipe dedicada e infraestrutura moderna. Nosso objetivo é garantir a saúde e a felicidade dos seus pets, com tratamentos humanizados e tecnologia de ponta.</p>
    </div>

    <!-- Serviços -->
    <div class="w3-container w3-padding-64 w3-white" id="servicos">
        <h2 class="w3-center">Nossos Serviços</h2>
        <div class="w3-row-padding w3-center w3-margin-top">
            <div class="w3-third">
                <i class="fa fa-stethoscope w3-margin-bottom w3-jumbo w3-center"></i>
                <p class="w3-large">Consultas</p>
                <p>Atendimento clínico geral com diagnóstico e orientações.</p>
            </div>
            <div class="w3-third">
                <i class="fa fa-scissors w3-margin-bottom w3-jumbo w3-center"></i>
                <p class="w3-large">Banho e Tosa</p>
                <p>Serviços de estética e higiene para cães e gatos.</p>
            </div>
            <div class="w3-third">
                <i class="fa fa-medkit w3-margin-bottom w3-jumbo w3-center"></i>
                <p class="w3-large">Vacinação</p>
                <p>Protocolos completos para prevenção de doenças.</p>
            </div>
        </div>
    </div>

    <!-- Equipe -->
    <div class="w3-container w3-padding-64" id="equipe">
        <h2 class="w3-center">Nossa Equipe</h2>
        <div class="w3-row-padding w3-center w3-margin-top">
            <div class="w3-third">
                <img src="https://randomuser.me/api/portraits/women/44.jpg" class="w3-circle" alt="Dra. Ana" style="width:150px;height:150px">
                <h4>Dra. Ana Souza</h4>
                <p>Veterinária Clínica</p>
            </div>
            <div class="w3-third">
                <img src="https://randomuser.me/api/portraits/men/32.jpg" class="w3-circle" alt="Dr. Paulo" style="width:150px;height:150px">
                <h4>Dr. Paulo Lima</h4>
                <p>Cirurgião Veterinário</p>
            </div>
            <div class="w3-third">
                <img src="https://randomuser.me/api/portraits/women/55.jpg" class="w3-circle" alt="Mariana" style="width:150px;height:150px">
                <h4>Mariana Ribeiro</h4>
                <p>Banho e Tosa</p>
            </div>
        </div>
    </div>

    <!-- Contato -->
    <div class="w3-container w3-padding-64 w3-white" id="contato">
        <h2 class="w3-center">Contato</h2>
        <p class="w3-center w3-large">Entre em contato conosco para agendar uma consulta.</p>
        <div class="w3-row">
            <div class="w3-col m6">
                <form class="w3-container w3-card-4 w3-padding">
                    <p><input class="w3-input" type="text" placeholder="Seu nome" required></p>
                    <p><input class="w3-input" type="email" placeholder="Seu e-mail" required></p>
                    <p><input class="w3-input" type="text" placeholder="Assunto" required></p>
                    <p><textarea class="w3-input" placeholder="Mensagem" required></textarea></p>
                    <p><button class="w3-button w3-black w3-round" type="submit">Enviar</button></p>
                </form>
            </div>
            <div class="w3-col m6 w3-padding-large">
                <h4><i class="fa fa-map-marker fa-fw w3-margin-right"></i> Rua dos Pets, 123 - Petópolis, BR</h4>
                <h4><i class="fa fa-phone fa-fw w3-margin-right"></i> +55 11 91234-5678</h4>
                <h4><i class="fa fa-envelope fa-fw w3-margin-right"></i> contato@prepet.com.br</h4>
            </div>
        </div>
    </div>

    <!-- Rodapé -->
    <footer class="w3-center w3-cyan w3-padding-16">
        <p>© 2025 Clínica PrePet - Todos os direitos reservados</p>
    </footer>

</body>
</html>
