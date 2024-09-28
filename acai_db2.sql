CREATE DATABASE acai_db2;

-- DROP DATABASE acai_db2;


USE acai_db2;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `acai_db2`
--
-- --------------------------------------------------------

--
-- Estrutura para tabela `compra`
--

CREATE TABLE `compra` (
  `id_compra` int(11) NOT NULL,
  `data_compra` timestamp NOT NULL DEFAULT current_timestamp(),
  `nome_cliente` varchar(100) NOT NULL,
  `valor_total` decimal(10,2) NOT NULL,
  `entrega` smallint(1) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `compra`
--

INSERT INTO `compra` (`id_compra`, `data_compra`, `nome_cliente`, `valor_total`, `entrega`, `endereco`) VALUES
(1, '2024-06-21 16:41:12', 'eee', 10.00, 1, 'sssssssssssssss'),
(2, '2024-06-21 16:46:09', 'sssssss', 10.00, 2, ''),
(3, '2024-06-21 16:50:46', 'eee', 21.00, 1, 'ddddddddddddddddddd'),
(4, '2024-06-21 16:51:19', 'eee', 21.00, 1, 'ddddddddddddddddddd'),
(5, '2024-06-21 16:51:40', 'sssssss', 42.00, 0, 'ddddddddddddddddddd'),
(6, '2024-06-21 16:52:15', 'jjjjjjjjj', 15.00, 2, ''),
(7, '2024-06-21 16:52:35', 'kkkkkkk', 20.00, 1, 'kkkkkkkk'),
(8, '2024-06-21 16:57:15', '', 42.00, 0, ''),
(9, '2024-06-21 17:04:30', 'cccccc', 62.00, 0, ''),
(10, '2024-06-21 17:08:54', 'ggggggggggggg', 21.00, 0, 'gggggggggg'),
(11, '2024-06-21 17:10:24', 'ccccccc', 42.00, 0, 'cccccccc'),
(13, '2024-06-21 17:15:14', 'fffff', 21.00, 0, 'fffffff'),
(14, '2024-06-21 17:18:53', 'cccccc', 21.00, 0, 'cccc'),
(15, '2024-06-21 17:23:14', 'ccccccc', 21.00, 0, 'ddddd'),
(16, '2024-06-21 17:27:06', 'cccc', 21.00, 0, 'ddd'),
(19, '2024-06-21 17:38:42', 'sssssss', 21.00, 0, 'ddddddddddddddddddd'),
(20, '2024-06-21 17:39:04', 'eee', 63.00, 0, 'ddddddddddddddddddd'),
(21, '2024-06-21 18:07:40', 'gg', 0.00, 0, 'gg'),
(22, '2024-06-21 18:09:35', 'dddd', 0.00, 0, 'ddd'),
(23, '2024-06-21 18:09:53', 'hhhh', 21.00, 0, 'hhhhhh');


INSERT INTO `compra` (`id_compra`, `data_compra`, `nome_cliente`, `valor_total`, `entrega`, `endereco`) VALUES
(24, '2024-06-26 16:41:12', 'eee', 10.00, 1, 'sssssssssssssss'),
(25, '2024-07-21 16:46:09', 'sssssss', 10.00, 2, ''),
(26, '2024-08-08 17:39:04', 'eee', 63.00, 0, 'ddddddddddddddddddd'),
(27, '2024-09-10 18:07:40', 'gg', 0.00, 0, 'gg'),
(28, '2024-06-12 18:09:35', 'dddd', 0.00, 0, 'ddd'),
(29, '2024-06-14 18:09:53', 'hhhh', 21.00, 0, 'hhhhhh');

-- --------------------------------------------------------

--
-- Estrutura para tabela `item`
--

CREATE TABLE `item` (
  `id_compra` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `valor_item` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `item`
--

INSERT INTO `item` (`id_compra`, `id_produto`, `quantidade`, `valor_item`) VALUES
(1, 1, 1, 2.00),
(1, 2, 1, 1.50),
(1, 3, 1, 3.00),
(1, 4, 1, 2.50),
(2, 1, 1, 2.00),
(2, 4, 1, 2.50),
(6, 1, 1, 2.00),
(6, 2, 1, 1.50),
(6, 4, 1, 2.50),
(7, 1, 1, 2.00),
(7, 2, 1, 1.50),
(7, 3, 1, 3.00),
(11, 5, 1, 21.00),
(13, 5, 1, 21.00),
(14, 6, 1, 21.00),
(15, 7, 1, 21.00),
(16, 8, 1, 21.00),
(19, 5, 1, 21.00),
(20, 5, 1, 21.00),
(20, 6, 1, 21.00),
(20, 8, 1, 21.00),
(23, 7, 1, 21.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produto`
--

CREATE TABLE `produto` (
  `id_produto` int(11) NOT NULL,
  `nome_produto` varchar(50) NOT NULL,
  `valor_unitario` decimal(10,2) NOT NULL,
  `tipo` smallint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produto`
--

INSERT INTO `produto` (`id_produto`, `nome_produto`, `valor_unitario`, `tipo`) VALUES
(1, 'Granola', 2.00, 2),
(2, 'Banana', 1.50, 2),
(3, 'Morango', 3.00, 2),
(4, 'Leite Condensado', 2.50, 2),
(5, 'Omelete', 21.00, 3),
(6, 'Panqueca', 21.00, 3),
(7, 'Salada', 21.00, 4),
(8, 'Salada de fruta', 21.00, 5);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`id_compra`);

--
-- Índices de tabela `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id_compra`,`id_produto`),
  ADD KEY `fk_produto` (`id_produto`);

--
-- Índices de tabela `produto`
--
ALTER TABLE `produto`
  ADD PRIMARY KEY (`id_produto`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `compra`
--
ALTER TABLE `compra`
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de tabela `produto`
--
ALTER TABLE `produto`
  MODIFY `id_produto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `fk_compra` FOREIGN KEY (`id_compra`) REFERENCES `compra` (`id_compra`),
  ADD CONSTRAINT `fk_produto` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id_produto`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


CREATE TABLE usuarios ( 
    ID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telefone VARCHAR(20) DEFAULT NULL,
    senha VARCHAR(255) NOT NULL,
    cargo ENUM('funcionario', 'gestor', 'finceiro') NOT NULL,
    aprovado TINYINT DEFAULT 0,
    token_reset_senha VARCHAR(100) DEFAULT NULL,
    data_token_reset_senha DATETIME DEFAULT NULL
);


INSERT INTO usuarios (ID, nome, usuario, email, telefone, senha, cargo, aprovado, token_reset_senha, data_token_reset_senha)
VALUES ('0000000001', 'admin', '', 'admin@gmail.com', '', '$2y$10$CODZcU9ZkM6OAWNRfmUzrOEPVHsmeLDO1l0hlyj0shK1MkEIc5aCy', 'gestor', 1, NULL, NULL);


CREATE TABLE vendas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    valor DECIMAL(10, 2) NOT NULL,
    data DATE NOT NULL
);

CREATE TABLE gastos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    valor DECIMAL(10, 2) NOT NULL,
    data DATE NOT NULL
);


INSERT INTO vendas (valor, data) VALUES
(150.00, '2024-09-01'),
(230.50, '2024-09-02'),
(110.75, '2024-09-03'),
(400.00, '2024-09-04'),
(95.20, '2024-09-05'),
(310.90, '2024-09-06'),
(250.00, '2024-09-07');

INSERT INTO gastos (valor, data) VALUES
(50.00, '2024-09-01'),
(75.00, '2024-09-02'),
(40.30, '2024-09-03'),
(120.00, '2024-09-04'),
(25.50, '2024-09-05'),
(65.00, '2024-09-06'),
(80.00, '2024-09-07');


CREATE TABLE ouvidoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    tipo ENUM('sugestao', 'reclamacao', 'elogio', 'duvida') NOT NULL,
    mensagem TEXT NOT NULL,
    data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);






