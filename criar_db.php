<?php
$DB_HOST = 'square-cloud-db-781fbc2ffaac45a3942985a2a4ac73a7.squareweb.app';
$DB_PORT = '7114';
$DB_USER = 'squarecloud';
$DB_PASS = 'sBRdSeoEkZ3Yp0l1RCYNcehN';

$DSN = "mysql:host=$DB_HOST;port=$DB_PORT;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
];

$pdo = new PDO($DSN, $DB_USER, $DB_PASS, $options);

// 👇 CRIA O BANCO
$pdo->exec("
    CREATE DATABASE IF NOT EXISTS HensoTech
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci
");

echo "Banco HensoTech criado com sucesso 🎉";
