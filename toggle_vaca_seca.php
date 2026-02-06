<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['prenhez_id'])) {
    header('Location: index.php');
    exit;
}

$prenhez_id = intval($_POST['prenhez_id']);

// Busca o registro atual
$stmt = $pdo->prepare('SELECT * FROM prenhezes WHERE id = ? AND status = "EmGestacao"');
$stmt->execute([$prenhez_id]);
$prenhez = $stmt->fetch();

if (!$prenhez) {
    header('Location: index.php');
    exit;
}

// Alterna o status: se estava seca (1), volta para 0 e vice-versa
$novo_status = $prenhez['vaca_seca'] == 1 ? 0 : 1;

$pdo->prepare('UPDATE prenhezes SET vaca_seca = ? WHERE id = ?')
    ->execute([$novo_status, $prenhez_id]);

$animal_id = $prenhez['animal_id'];
header("Location: ver_animal.php?id=$animal_id");
exit;
?>