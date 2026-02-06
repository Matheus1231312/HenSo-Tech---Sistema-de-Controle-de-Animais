-- db.sql
CREATE DATABASE IF NOT EXISTS HensoTech CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE HensoTech;

CREATE TABLE animais (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150) NOT NULL,
  numero VARCHAR(50) DEFAULT NULL,
  pai VARCHAR(150) DEFAULT NULL,
  mae VARCHAR(150) DEFAULT NULL,
  telefone_dono VARCHAR(30) DEFAULT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE prenhezes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  animal_id INT NOT NULL,
  data_inseminacao DATE NOT NULL,
  touro VARCHAR(150) DEFAULT NULL,
  tipo_inseminacao ENUM('Sexado','Normal') NOT NULL DEFAULT 'Normal',
  status ENUM('EmGestacao','Perdeu','Pariu') NOT NULL DEFAULT 'EmGestacao',
  confirmado_perda TINYINT(1) DEFAULT 0,
  confirmado_parto TINYINT(1) DEFAULT 0,
  aviso_secagem TINYINT(1) DEFAULT 0,
  aviso_parto TINYINT(1) DEFAULT 0,
  vaca_seca TINYINT(1) DEFAULT 0,
  notificacao_secagem_enviada TINYINT(1) DEFAULT 0,
  notificacao_pre_parto_enviada TINYINT(1) DEFAULT 0,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (animal_id) REFERENCES animais(id) ON DELETE CASCADE
);

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100),
  email VARCHAR(150) UNIQUE,
  senha_hash VARCHAR(255),
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS settings (
  `key` VARCHAR(100) PRIMARY KEY,
  `value` TEXT
);
