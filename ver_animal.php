<?php 
include 'header.php';

$id = intval($_GET['id'] ?? 0);
if (!$id) { header('Location: index.php'); exit; }

$animal = $pdo->prepare('SELECT * FROM animais WHERE id = ?');
$animal->execute([$id]);
$animal = $animal->fetch();
if (!$animal) { die('Animal não encontrado'); }

$prenhezes = $pdo->prepare('SELECT * FROM prenhezes WHERE animal_id = ? ORDER BY criado_em DESC');
$prenhezes->execute([$id]);
$prenhezes = $prenhezes->fetchAll();

function dataPrevista($data_insem) {
    $d = new DateTime($data_insem);
    $d->modify('+9 months');
    return $d->format('d/m/Y');
}

function dataSecagem($data_insem) {
    $d = new DateTime($data_insem);
    $d->modify('+7 months');
    return $d->format('d/m/Y');
}

$prenhez_atual = null;
foreach ($prenhezes as $p) {
    if ($p['status'] === 'EmGestacao') {
        $prenhez_atual = $p;
        break;
    }
}
?>

<divclass="borda_baixo"><p><br></p></div>
<h2 class="mb-4"><?= htmlspecialchars($animal['nome']) ?> <?= $animal['numero'] ? "({$animal['numero']})" : "" ?></h2>

<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Informações do Animal</h5>
    </div>
    <div class="card-body">
        <p><strong>Pai:</strong> <?= htmlspecialchars($animal['pai'] ?: '—') ?></p>
        <p><strong>Mãe:</strong> <?= htmlspecialchars($animal['mae'] ?: '—') ?></p>
        <p><strong>Status de Produção:</strong>
            <?php if ($animal['status_producao'] === 'Seca'): ?>
                <span class="badge bg-danger">Seca</span>
            <?php else: ?>
                <span class="badge bg-success">Produzindo</span>
            <?php endif; ?>
        </p>

        <!-- Botões para alterar status de produção -->
        <div class="mt-3">
            <form method="post" action="atualizar_status_producao.php" class="d-inline">
                <input type="hidden" name="animal_id" value="<?= $id ?>">
                <input type="hidden" name="novo_status" value="Seca">
                <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Marcar como seca?')">Marcar como Seca</button>
            </form>
            <form method="post" action="atualizar_status_producao.php" class="d-inline ms-2">
                <input type="hidden" name="animal_id" value="<?= $id ?>">
                <input type="hidden" name="novo_status" value="Produzindo">
                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Marcar como produzindo?')">Marcar como Produzindo</button>
            </form>
        </div>
    </div>
</div>

<!-- Tabela de prenhezes (igual antes) -->
<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Histórico de Prenhezes</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Data Inseminação</th>
                        <th>Touro</th>
                        <th>Raça</th>
                        <th>Tipo</th>
                        <th>Parto Previsto</th>
                        <th>Secagem</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prenhezes as $p): ?>
                    <tr>
                        <td><?= (new DateTime($p['data_inseminacao']))->format('d/m/Y') ?></td>
                        <td><?= htmlspecialchars($p['touro'] ?: '—') ?></td>
                        <td><?= htmlspecialchars($p['raca_touro'] ?: '—') ?></td>
                        <td><?= $p['tipo_inseminacao'] ?></td>
                        <td><?= dataPrevista($p['data_inseminacao']) ?></td>
                        <td><?= dataSecagem($p['data_inseminacao']) ?></td>
                        <td>
                            <?php if ($p['status'] == 'EmGestacao'): ?><span class="badge bg-warning">Em Gestação</span>
                            <?php elseif ($p['status'] == 'Pariu'): ?><span class="badge bg-success">Pariu</span>
                            <?php else: ?><span class="badge bg-danger">Perdeu</span><?php endif; ?>
                        </td>
                        <td>
                            <?php if ($p['status'] == 'EmGestacao'): ?>
                                <form method="post" action="confirm_perda.php" class="d-inline">
                                    <input type="hidden" name="prenhez_id" value="<?= $p['id'] ?>">
                                    <button class="btn btn-sm btn-danger">Perdeu</button>
                                </form>
                                <form method="post" action="confirm_parto.php" class="d-inline ms-2">
                                    <input type="hidden" name="prenhez_id" value="<?= $p['id'] ?>">
                                    <button class="btn btn-sm btn-success">Criou</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="text-center mt-4">
    <a href="index.php" class="btn btn-outline-secondary">← Voltar</a>
</div>

<?php include 'footer.php'; ?>