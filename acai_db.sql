CREATE DATABASE acai_db;

USE acai_db;

CREATE TABLE compras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ingredientes TEXT NOT NULL,
    data_compra TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    nome_cliente VARCHAR(100) NOT NULL,
    valor_total DECIMAL(10, 2) NOT NULL,
	entrega VARCHAR(255),
    endereco VARCHAR(255)	
);

CREATE TABLE compras_sa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_cliente VARCHAR(255) NOT NULL,
    descricao TEXT NOT NULL,
    valor_total DECIMAL(10, 2) NOT NULL,
    entrega BOOLEAN NOT NULL,
    endereco TEXT,
    data_compra DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE usuarios (
  ID int(10) UNSIGNED ZEROFILL NOT NULL,
  nome varchar(100) NOT NULL,
  usuario varchar(50) NOT NULL,
  email varchar(100) NOT NULL,
  telefone varchar(20) DEFAULT NULL,
  senha varchar(255) NOT NULL,
	cargo ENUM('funcionario', 'gestor') NOT NULL,
    aprovado TINYINT DEFAULT 0
);
/*para indicar que um campo pode conter um valor nulo por padrão*/
ALTER TABLE usuarios ADD COLUMN token_reset_senha VARCHAR(100) DEFAULT NULL;
ALTER TABLE usuarios ADD COLUMN data_token_reset_senha DATETIME DEFAULT NULL;

SELECT * FROM compras_sa;

SELECT * FROM usuarios;

DROP DATABASE acai_db;