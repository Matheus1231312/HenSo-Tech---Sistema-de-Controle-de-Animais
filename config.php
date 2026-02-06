<?php
/*************************************************
 * CONFIG + MIGRAÇÃO AUTOMÁTICA DO BANCO
 * SquareCloud / MySQL com SSL obrigatório
 *************************************************/

// 🔹 Credenciais
$DB_HOST = 'square-cloud-db-8ebe7ab366784db8af1d807ef760ceb4.squareweb.app';
$DB_PORT = '7109';
$DB_NAME = 'HensoTech';
$DB_USER = 'squarecloud';
$DB_PASS = '5hiQEwb93lZpwsH34CiVZDA7';

// 🔹 Conexão SEM selecionar banco (para criar)
$dsnNoDb = "mysql:host=$DB_HOST;port=$DB_PORT;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

    // SSL obrigatório na SquareCloud
    PDO::MYSQL_ATTR_SSL_CA => __DIR__ . '/ca.crt',
    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
];

try {
    $pdo = new PDO($dsnNoDb, $DB_USER, $DB_PASS, $options);
} catch (PDOException $e) {
    die("Erro na conexão inicial: " . $e->getMessage());
}

/*************************************************
 * MIGRAÇÃO: CRIAR BANCO
 *************************************************/
$pdo->exec("
    CREATE DATABASE IF NOT EXISTS `$DB_NAME`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci
");

/*************************************************
 * RECONECTA JÁ USANDO O BANCO
 *************************************************/
$dsnWithDb = "mysql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_NAME;charset=utf8mb4";
$pdo = new PDO($dsnWithDb, $DB_USER, $DB_PASS, $options);

/*************************************************
 * MIGRAÇÃO: CRIAR TABELAS
 *************************************************/

// Tabela animais
$pdo->exec("
CREATE TABLE IF NOT EXISTS animais (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150) NOT NULL,
  numero VARCHAR(50) DEFAULT NULL,
  pai VARCHAR(150) DEFAULT NULL,
  mae VARCHAR(150) DEFAULT NULL,
  telefone_dono VARCHAR(30) DEFAULT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

// Tabela prenhezes
$pdo->exec("
CREATE TABLE IF NOT EXISTS prenhezes (
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
  CONSTRAINT fk_prenhez_animal
    FOREIGN KEY (animal_id) REFERENCES animais(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

// Tabela usuarios
$pdo->exec("
CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100),
  email VARCHAR(150) UNIQUE,
  senha_hash VARCHAR(255),
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

// Tabela settings
$pdo->exec("
CREATE TABLE IF NOT EXISTS settings (
  `key` VARCHAR(100) PRIMARY KEY,
  `value` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

// === MIGRAÇÃO SEGURA: Adicionar coluna raca_touro (compatível com MySQL antigo) ===
try {
    $pdo->exec("ALTER TABLE prenhezes ADD COLUMN raca_touro VARCHAR(150) NULL AFTER touro");
} catch (PDOException $e) {
    // Ignora apenas se a coluna já existir (erro 1060 - Duplicate column name)
    if ($e->getCode() != '42S21' && strpos($e->getMessage(), 'Duplicate column name') === false) {
        throw $e; // Relança outros erros inesperados
    }
}

// === MIGRAÇÃO SEGURA: Adicionar coluna status_producao na tabela animais ===
try {
    $pdo->exec("
        ALTER TABLE animais 
        ADD COLUMN status_producao ENUM('Produzindo', 'Seca', 'Vazia') 
        DEFAULT 'Produzindo' 
        AFTER numero
    ");
} catch (PDOException $e) {
    // Ignora apenas se a coluna já existir
    if ($e->getCode() != '42S21' && strpos($e->getMessage(), 'Duplicate column name') === false) {
        throw $e;
    }
    // Coluna já existe → continua normalmente
}

/*************************************************
 * FUNÇÕES AUXILIARES
 *************************************************/
if (!function_exists('get_setting')) {
    function get_setting($key, $default = null) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT value FROM settings WHERE `key` = ?");
        $stmt->execute([$key]);
        $r = $stmt->fetch();
        return $r ? $r['value'] : $default;
    }
}

if (!function_exists('set_setting')) {
    function set_setting($key, $value) {
        global $pdo;
        $stmt = $pdo->prepare(
            "REPLACE INTO settings (`key`, `value`) VALUES (?, ?)"
        );
        return $stmt->execute([$key, $value]);
    }
}
?>