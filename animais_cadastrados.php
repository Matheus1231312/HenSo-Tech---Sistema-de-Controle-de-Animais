<?php 
include 'header.php'; 
$busca = trim($_GET['busca'] ?? '');
if ($busca) {
    $stmt = $pdo->prepare("SELECT * FROM animais WHERE nome LIKE :b OR numero LIKE :b ORDER BY nome");
    $stmt->execute([':b' => "%$busca%"]);
    $animals = $stmt->fetchAll();
} else {
    $animals = $pdo->query("SELECT * FROM animais ORDER BY criado_em DESC")->fetchAll();
}
?>
<div class="borda_baixo"><p><br></p></div>
<h2 class="mb-4 text-center">Animais Cadastrados <?= $busca ? "(busca: \"$busca\")" : "" ?></h2>

<form class="mb-4 max-w-md mx-auto">
    <div class="input-group">
        <input type="text" class="form-control" name="busca" placeholder="Buscar por nome ou número" value="<?= htmlspecialchars($busca) ?>">
        <button class="btn btn-primary" type="submit">Buscar</button>
        <?php if ($busca): ?><a href="animais_cadastrados.php" class="btn btn-outline-secondary">Limpar</a><?php endif; ?>
    </div>
</form>

<?php if (empty($animals)): ?>
    <div class="text-center py-5">
        <p class="lead">Nenhum animal cadastrado ainda.</p>
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-primary">
                <tr>
                    <th>Nome</th>
                    <th>Número</th>
                    <th>Pai</th>
                    <th>Mãe</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($animals as $a): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($a['nome']) ?></strong></td>
                        <td><?= htmlspecialchars($a['numero'] ?: '—') ?></td>
                        <td><?= htmlspecialchars($a['pai'] ?: '—') ?></td>
                        <td><?= htmlspecialchars($a['mae'] ?: '—') ?></td>
                        <td class="text-center">
                            <a href="ver_animal.php?id=<?= $a['id'] ?>" class="btn btn-sm btn-outline-primary mb-2">Ver Prenhezes</a><br>
                            <form method="post" action="delete_animal.php" onsubmit="return confirm('Tem certeza que deseja EXCLUIR este animal?');" class="d-inline">
                                <input type="hidden" name="animal_id" value="<?= $a['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php include 'footer.php'; ?>