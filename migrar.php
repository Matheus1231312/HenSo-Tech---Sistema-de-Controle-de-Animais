<?php
require 'config.php';

$sql = file_get_contents(__DIR__ . '/db.sql');
$pdo->exec($sql);

echo "Banco HensoTech e tabelas criados com sucesso 🎉";
