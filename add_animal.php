<?php
require 'config.php';
$nome = trim($_POST['nome'] ?? '');
$numero = trim($_POST['numero'] ?? null);
$pai = trim($_POST['pai'] ?? null);
$mae = trim($_POST['mae'] ?? null);
$telefone = trim($_POST['telefone_dono'] ?? null);

if (!$nome) { header('Location: index.php'); exit; }

$sql = "INSERT INTO animais (nome, numero, pai, mae, telefone_dono) VALUES (:nome, :numero, :pai, :mae, :tel)";
$stmt = $pdo->prepare($sql);
$stmt->execute([':nome'=>$nome, ':numero'=>$numero ?: null, ':pai'=>$pai ?: null, ':mae'=>$mae ?: null, ':tel'=>$telefone ?: null]);

header('Location: index.php');
