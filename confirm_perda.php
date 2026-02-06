<?php
require 'config.php';
$prenhez_id = intval($_POST['prenhez_id'] ?? 0);
if (!$prenhez_id) { header('Location: index.php'); exit; }
$sql = "UPDATE prenhezes SET status='Perdeu', confirmado_perda=1 WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id'=>$prenhez_id]);
$row = $pdo->prepare("SELECT animal_id FROM prenhezes WHERE id = ?");
$row->execute([$prenhez_id]);
$r = $row->fetch();
header('Location: ver_animal.php?id=' . ($r['animal_id'] ?? ''));
