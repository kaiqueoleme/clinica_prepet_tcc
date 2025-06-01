-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 01/06/2025 às 04:03
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `clinica_veterinaria`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `agendamento`
--

CREATE TABLE `agendamento` (
  `id` int(11) NOT NULL,
  `data_agend` date DEFAULT NULL,
  `hora_consulta` time NOT NULL,
  `tipo_servico` varchar(100) NOT NULL,
  `observacoes` text DEFAULT NULL,
  `status` enum('Agendado','Confirmado','Realizado','Cancelado','Remarcado') DEFAULT 'Agendado',
  `id_vet` int(11) DEFAULT NULL,
  `id_pac` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `documento`
--

CREATE TABLE `documento` (
  `id` int(11) NOT NULL,
  `tipo` varchar(60) DEFAULT NULL,
  `conteudo` longtext DEFAULT NULL,
  `id_vet` int(11) DEFAULT NULL,
  `id_pac` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `endereco`
--

CREATE TABLE `endereco` (
  `id` int(11) NOT NULL,
  `logradouro` varchar(80) DEFAULT NULL,
  `numero` varchar(11) DEFAULT NULL,
  `bairro` varchar(45) DEFAULT NULL,
  `cidade` varchar(80) DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `id_pessoa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `estoque`
--

CREATE TABLE `estoque` (
  `id` int(11) NOT NULL,
  `nome_produto` varchar(60) DEFAULT NULL,
  `quantidade` decimal(10,2) DEFAULT NULL,
  `fornecedor` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `nivelacesso`
--

CREATE TABLE `nivelacesso` (
  `id` int(11) NOT NULL,
  `descricao` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `paciente`
--

CREATE TABLE `paciente` (
  `id` int(11) NOT NULL,
  `nome` varchar(45) NOT NULL,
  `especie` varchar(45) DEFAULT NULL,
  `raca` varchar(45) DEFAULT NULL,
  `data_nasc` date DEFAULT NULL,
  `id_tutor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pessoa`
--

CREATE TABLE `pessoa` (
  `id` int(11) NOT NULL,
  `nome` varchar(80) NOT NULL,
  `dataNascimento` date DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `rg` varchar(15) DEFAULT NULL,
  `cpf` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `procedimento`
--

CREATE TABLE `procedimento` (
  `id` int(11) NOT NULL,
  `tipo` varchar(60) DEFAULT NULL,
  `data_procedimento` date DEFAULT NULL,
  `resultado` varchar(80) DEFAULT NULL,
  `status_paciente` varchar(45) DEFAULT NULL,
  `diagnostico` varchar(80) DEFAULT NULL,
  `id_vet` int(11) DEFAULT NULL,
  `id_pac` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tutor`
--

CREATE TABLE `tutor` (
  `id` int(11) NOT NULL,
  `id_pessoa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `login` varchar(15) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `id_pessoa` int(11) DEFAULT NULL,
  `id_acesso` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `veterinario`
--

CREATE TABLE `veterinario` (
  `id` int(11) NOT NULL,
  `crmv` varchar(15) NOT NULL,
  `especialidade` varchar(100) DEFAULT NULL,
  `despesa` decimal(10,2) DEFAULT NULL,
  `salario` decimal(10,2) DEFAULT NULL,
  `id_pessoa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `agendamento`
--
ALTER TABLE `agendamento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `agendamento_ibfk_1` (`id_vet`),
  ADD KEY `agendamento_ibfk_2` (`id_pac`);

--
-- Índices de tabela `documento`
--
ALTER TABLE `documento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documento_ibfk_1` (`id_vet`),
  ADD KEY `documento_ibfk_2` (`id_pac`);

--
-- Índices de tabela `endereco`
--
ALTER TABLE `endereco`
  ADD PRIMARY KEY (`id`),
  ADD KEY `endereco_ibfk_1` (`id_pessoa`);

--
-- Índices de tabela `estoque`
--
ALTER TABLE `estoque`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `nivelacesso`
--
ALTER TABLE `nivelacesso`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `paciente`
--
ALTER TABLE `paciente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tutor` (`id_tutor`);

--
-- Índices de tabela `pessoa`
--
ALTER TABLE `pessoa`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `procedimento`
--
ALTER TABLE `procedimento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_vet` (`id_vet`),
  ADD KEY `procedimento_ibfk_2` (`id_pac`);

--
-- Índices de tabela `tutor`
--
ALTER TABLE `tutor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tutor_ibfk_1` (`id_pessoa`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`login`),
  ADD KEY `usuario_ibfk_1` (`id_pessoa`),
  ADD KEY `usuario_ibfk_2` (`id_acesso`);

--
-- Índices de tabela `veterinario`
--
ALTER TABLE `veterinario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `crmv` (`crmv`),
  ADD KEY `id_pessoa` (`id_pessoa`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `agendamento`
--
ALTER TABLE `agendamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `documento`
--
ALTER TABLE `documento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `endereco`
--
ALTER TABLE `endereco`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `estoque`
--
ALTER TABLE `estoque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `nivelacesso`
--
ALTER TABLE `nivelacesso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `paciente`
--
ALTER TABLE `paciente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pessoa`
--
ALTER TABLE `pessoa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `procedimento`
--
ALTER TABLE `procedimento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tutor`
--
ALTER TABLE `tutor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `veterinario`
--
ALTER TABLE `veterinario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `agendamento`
--
ALTER TABLE `agendamento`
  ADD CONSTRAINT `agendamento_ibfk_1` FOREIGN KEY (`id_vet`) REFERENCES `veterinario` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `agendamento_ibfk_2` FOREIGN KEY (`id_pac`) REFERENCES `paciente` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `documento`
--
ALTER TABLE `documento`
  ADD CONSTRAINT `documento_ibfk_1` FOREIGN KEY (`id_vet`) REFERENCES `veterinario` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `documento_ibfk_2` FOREIGN KEY (`id_pac`) REFERENCES `paciente` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `endereco`
--
ALTER TABLE `endereco`
  ADD CONSTRAINT `endereco_ibfk_1` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoa` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `paciente`
--
ALTER TABLE `paciente`
  ADD CONSTRAINT `paciente_ibfk_1` FOREIGN KEY (`id_tutor`) REFERENCES `tutor` (`id`);

--
-- Restrições para tabelas `procedimento`
--
ALTER TABLE `procedimento`
  ADD CONSTRAINT `procedimento_ibfk_1` FOREIGN KEY (`id_vet`) REFERENCES `veterinario` (`id`),
  ADD CONSTRAINT `procedimento_ibfk_2` FOREIGN KEY (`id_pac`) REFERENCES `paciente` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `tutor`
--
ALTER TABLE `tutor`
  ADD CONSTRAINT `tutor_ibfk_1` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoa` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`id_acesso`) REFERENCES `nivelacesso` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `veterinario`
--
ALTER TABLE `veterinario`
  ADD CONSTRAINT `veterinario_ibfk_1` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoa` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
