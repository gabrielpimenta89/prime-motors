-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 08/10/2026 às 20:32
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
-- Banco de dados: `montclair_motors`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `carros`
--

CREATE TABLE `carros` (
  `id_carro` int(11) NOT NULL,
  `marca` varchar(30) NOT NULL,
  `modelo` varchar(30) NOT NULL,
  `ano` year(4) NOT NULL,
  `cor` varchar(20) DEFAULT NULL,
  `combustivel` varchar(20) DEFAULT NULL,
  `km` int(11) DEFAULT NULL,
  `n_chassi` varchar(17) NOT NULL,
  `preco` decimal(10,2) NOT NULL DEFAULT 0.00,
  `imagem` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `carros`
--

INSERT INTO `carros` (`id_carro`, `marca`, `modelo`, `ano`, `cor`, `combustivel`, `km`, `n_chassi`, `preco`, `imagem`) VALUES
(3, 'Ferrari', 'Roma', '2026', 'Preta', 'Gasolina', 14, '73273731', 4700000.00, 'uploads/6abdbca4df555_6a4422aa92f1b_ferrariroma.webp'),
(4, 'BMW', 'Serie 3', '2023', 'Prata', 'Gasolina', 1000, '11414F6752S', 379999.00, 'uploads/6a443d2f46deb_bmw3.webp');

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `endereco` varchar(150) DEFAULT NULL,
  `celular` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nome`, `cpf`, `email`, `senha`, `endereco`, `celular`) VALUES
(3, 'Gabriel Araujo', '123.456.789-09', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `estoque`
--

CREATE TABLE `estoque` (
  `id_estoque` int(11) NOT NULL,
  `id_carro` int(11) NOT NULL,
  `status_veiculo` enum('disponivel','vendido','reservado') DEFAULT 'disponivel',
  `preco_compra` decimal(10,2) DEFAULT NULL,
  `preco_venda` decimal(10,2) DEFAULT NULL,
  `data_entrada` date DEFAULT NULL,
  `data_saida` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `interesse`
--

CREATE TABLE `interesse` (
  `id_interesse` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_carro` int(11) NOT NULL,
  `data_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens_nota_fiscal`
--

CREATE TABLE `itens_nota_fiscal` (
  `id_item` int(11) NOT NULL,
  `id_nota` int(11) NOT NULL,
  `id_carro` int(11) NOT NULL,
  `quantidade` int(11) DEFAULT 1,
  `valor_unitario` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `itens_nota_fiscal`
--

INSERT INTO `itens_nota_fiscal` (`id_item`, `id_nota`, `id_carro`, `quantidade`, `valor_unitario`) VALUES
(1, 1, 4, 1, 379999.00),
(2, 2, 3, 1, 4700000.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `nota_fiscal`
--

CREATE TABLE `nota_fiscal` (
  `id_nota` int(11) NOT NULL,
  `id_venda` int(11) NOT NULL,
  `data_emissao` datetime DEFAULT current_timestamp(),
  `valor_total` decimal(10,2) DEFAULT NULL,
  `numero_nota` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `nota_fiscal`
--

INSERT INTO `nota_fiscal` (`id_nota`, `id_venda`, `data_emissao`, `valor_total`, `numero_nota`) VALUES
(1, 1, '2026-09-30 22:52:35', 379999.00, '2026000001'),
(2, 2, '2026-09-30 22:53:45', 4700000.00, '2026000002');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pagamentos`
--

CREATE TABLE `pagamentos` (
  `id_pagamento` int(11) NOT NULL,
  `id_venda` int(11) NOT NULL,
  `forma_pagamento` varchar(50) DEFAULT NULL,
  `banco` varchar(50) DEFAULT NULL,
  `parcelas` int(11) DEFAULT 1,
  `juros` decimal(10,2) DEFAULT 0.00,
  `status_pagamento` enum('pendente','pago','cancelado') DEFAULT NULL,
  `data_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pagamentos`
--

INSERT INTO `pagamentos` (`id_pagamento`, `id_venda`, `forma_pagamento`, `banco`, `parcelas`, `juros`, `status_pagamento`, `data_registro`) VALUES
(1, 1, 'Cartão de débito', NULL, 1, 0.00, 'pago', '2026-09-30 22:52:35'),
(2, 2, 'Financiamento', NULL, 1, 0.00, 'pendente', '2026-09-30 22:53:45');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `perfil` enum('admin','vendedor') NOT NULL,
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id`, `nome`, `email`, `senha`, `perfil`, `ativo`) VALUES
(1, 'Administrador', 'admin@gmail.com', '$2y$10$HASH_AQUI', 'admin', 1),
(2, 'Gabriel', 'gabriel.araujozx7@gmail.com', '$2y$10$B4paq4YqJigFGxNzTawJ7ucOTFrWD53IEDQjI/pPO6LCAL86nTDsS', 'admin', 1),
(4, 'lula', 'test@gmail.com', '$2y$10$RhDV62PUnD1999.BcqlMCeE2XV8oUoiL4NhFERjHzV5WlX7TFe13i', 'admin', 1),
(6, 'lula', 'teste@gmail.com', '$2y$10$oYTWWLWDRYu92c.yVIg1JeHd2stwKt3uQ2fsVFbP.peBGNhx0er/S', 'admin', 1),
(7, 'aula', 'aula@gmail.com', '$2y$10$EMdspBToPdw7LBUrX4rDZumWA61FAfD3QekiVIJiIt8TGI4LoJco.', 'vendedor', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `vendas`
--

CREATE TABLE `vendas` (
  `id_venda` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_carro` int(11) NOT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `data_venda` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `vendas`
--

INSERT INTO `vendas` (`id_venda`, `id_usuario`, `id_cliente`, `id_carro`, `valor`, `data_venda`) VALUES
(1, 6, 3, 4, 379999.00, '2026-09-30'),
(2, 6, 3, 3, 4700000.00, '2026-09-30');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `carros`
--
ALTER TABLE `carros`
  ADD PRIMARY KEY (`id_carro`),
  ADD UNIQUE KEY `n_chassi` (`n_chassi`);

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `estoque`
--
ALTER TABLE `estoque`
  ADD PRIMARY KEY (`id_estoque`),
  ADD KEY `id_carro` (`id_carro`),
  ADD KEY `idx_estoque_status` (`status_veiculo`);

--
-- Índices de tabela `interesse`
--
ALTER TABLE `interesse`
  ADD PRIMARY KEY (`id_interesse`),
  ADD UNIQUE KEY `uk_cliente_carro` (`id_cliente`,`id_carro`),
  ADD KEY `id_carro` (`id_carro`);

--
-- Índices de tabela `itens_nota_fiscal`
--
ALTER TABLE `itens_nota_fiscal`
  ADD PRIMARY KEY (`id_item`),
  ADD KEY `id_nota` (`id_nota`),
  ADD KEY `id_carro` (`id_carro`);

--
-- Índices de tabela `nota_fiscal`
--
ALTER TABLE `nota_fiscal`
  ADD PRIMARY KEY (`id_nota`),
  ADD UNIQUE KEY `numero_nota` (`numero_nota`),
  ADD KEY `id_venda` (`id_venda`);

--
-- Índices de tabela `pagamentos`
--
ALTER TABLE `pagamentos`
  ADD PRIMARY KEY (`id_pagamento`),
  ADD KEY `id_venda` (`id_venda`),
  ADD KEY `idx_pagamentos_status` (`status_pagamento`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `vendas`
--
ALTER TABLE `vendas`
  ADD PRIMARY KEY (`id_venda`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_carro` (`id_carro`),
  ADD KEY `idx_vendas_data` (`data_venda`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `carros`
--
ALTER TABLE `carros`
  MODIFY `id_carro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `estoque`
--
ALTER TABLE `estoque`
  MODIFY `id_estoque` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `interesse`
--
ALTER TABLE `interesse`
  MODIFY `id_interesse` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `itens_nota_fiscal`
--
ALTER TABLE `itens_nota_fiscal`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `nota_fiscal`
--
ALTER TABLE `nota_fiscal`
  MODIFY `id_nota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `pagamentos`
--
ALTER TABLE `pagamentos`
  MODIFY `id_pagamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `vendas`
--
ALTER TABLE `vendas`
  MODIFY `id_venda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `estoque`
--
ALTER TABLE `estoque`
  ADD CONSTRAINT `estoque_ibfk_1` FOREIGN KEY (`id_carro`) REFERENCES `carros` (`id_carro`);

--
-- Restrições para tabelas `interesse`
--
ALTER TABLE `interesse`
  ADD CONSTRAINT `interesse_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  ADD CONSTRAINT `interesse_ibfk_2` FOREIGN KEY (`id_carro`) REFERENCES `carros` (`id_carro`);

--
-- Restrições para tabelas `itens_nota_fiscal`
--
ALTER TABLE `itens_nota_fiscal`
  ADD CONSTRAINT `itens_nota_fiscal_ibfk_1` FOREIGN KEY (`id_nota`) REFERENCES `nota_fiscal` (`id_nota`),
  ADD CONSTRAINT `itens_nota_fiscal_ibfk_2` FOREIGN KEY (`id_carro`) REFERENCES `carros` (`id_carro`);

--
-- Restrições para tabelas `nota_fiscal`
--
ALTER TABLE `nota_fiscal`
  ADD CONSTRAINT `nota_fiscal_ibfk_1` FOREIGN KEY (`id_venda`) REFERENCES `vendas` (`id_venda`);

--
-- Restrições para tabelas `pagamentos`
--
ALTER TABLE `pagamentos`
  ADD CONSTRAINT `pagamentos_ibfk_1` FOREIGN KEY (`id_venda`) REFERENCES `vendas` (`id_venda`);

--
-- Restrições para tabelas `vendas`
--
ALTER TABLE `vendas`
  ADD CONSTRAINT `vendas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `vendas_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  ADD CONSTRAINT `vendas_ibfk_3` FOREIGN KEY (`id_carro`) REFERENCES `carros` (`id_carro`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
