<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['animal_id'])) {
    header('Location: index.php');
    exit;
}

$animal_id = intval($_POST['animal_id']);

// Primeiro, excluir prenhezes relacionadas (para evitar erro de foreign key)
$pdo->prepare('DELETE FROM prenhezes WHERE animal_id = ?')->execute([$animal_id]);

// Depois excluir o animal
$pdo->prepare('DELETE FROM animais WHERE id = ?')->execute([$animal_id]);

// Redireciona com mensagem de sucesso (opcional)
header('Location: index.php?deletado=1');
exit;
?>