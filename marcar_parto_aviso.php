<?php
require 'config.php';
$prenhez_id = intval($_POST['prenhez_id'] ?? 0);
if (!$prenhez_id) { header('Location: index.php'); exit; }
$sql = "UPDATE prenhezes SET aviso_parto = 1, notificacao_pre_parto_enviada = 1 WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id'=>$prenhez_id]);
header('Location: index.php');
