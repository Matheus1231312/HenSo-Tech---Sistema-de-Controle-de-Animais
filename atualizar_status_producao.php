<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $animal_id = intval($_POST['animal_id'] ?? 0);
    $novo_status = $_POST['novo_status'] ?? 'Produzindo';

    if ($animal_id > 0 && in_array($novo_status, ['Produzindo', 'Seca'])) {
        $stmt = $pdo->prepare("UPDATE animais SET status_producao = ? WHERE id = ?");
        $stmt->execute([$novo_status, $animal_id]);
    }
}

header('Location: ver_animal.php?id=' . $animal_id);
exit;
?>