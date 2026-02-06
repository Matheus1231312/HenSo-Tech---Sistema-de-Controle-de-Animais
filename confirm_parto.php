<?php
require_once 'config.php';

$prenhez_id = intval($_POST['prenhez_id'] ?? 0);
if (!$prenhez_id) { header('Location: index.php'); exit; }

$stmt = $pdo->prepare("SELECT p.*, a.id AS animal_id, a.nome AS mae_nome FROM prenhezes p JOIN animais a ON p.animal_id = a.id WHERE p.id = ?");
$stmt->execute([$prenhez_id]);
$prenhez = $stmt->fetch();

if (!$prenhez) { die('Prenhez não encontrada'); }

// Se já enviou o sexo
if (isset($_POST['sexo'])) {
    $sexo = $_POST['sexo'];

    // Atualiza status da prenhez
    $pdo->prepare("UPDATE prenhezes SET status = 'Pariu' WHERE id = ?")->execute([$prenhez_id]);

    if ($sexo === 'fêmea') {
        $nome_filha = trim($_POST['nome_filha'] ?? '');
        if (empty($nome_filha)) {
            die('Nome da terneira é obrigatório.');
        }
        $pai = $prenhez['touro'] ?: null;
        $stmt = $pdo->prepare("INSERT INTO animais (nome, pai, mae, status_producao) VALUES (?, ?, ?, 'Produzindo')");
        $stmt->execute([$nome_filha, $pai, $prenhez['mae_nome']]);
    }

    // Mensagem de sucesso (pode ser melhorada com flash message)
    header('Location: ver_animal.php?id=' . $prenhez['animal_id'] . '&parto=success');
    exit;
}

include 'header.php';
?>

<div class="card shadow">
    <div class="card-header bg-success text-white">
        <h5>Confirmar Parto - <?= htmlspecialchars($prenhez['mae_nome']) ?></h5>
    </div>
    <div class="card-body">
        <form method="post">
            <input type="hidden" name="prenhez_id" value="<?= $prenhez_id ?>">

            <div class="mb-3">
                <label class="form-label fw-bold">Sexo do bezerro:</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sexo" id="macho" value="macho" required>
                        <label class="form-check-label" for="macho">Terneiro (Macho)</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sexo" id="femea" value="femea" required>
                        <label class="form-check-label" for="femea">Terneira (Fêmea)</label>
                    </div>
                </div>
            </div>

            <div id="campo-nome" class="mb-3 d-none">
                <label class="form-label">Nome da Terneira:</label>
                <input type="text" name="nome_filha" class="form-control" placeholder="Ex: Lua">
                <small>Pai: <?= htmlspecialchars($prenhez['touro'] ?: 'Não definido') ?> | Mãe: <?= htmlspecialchars($prenhez['mae_nome']) ?></small>
            </div>

            <button type="submit" class="btn btn-success">Confirmar Parto</button>
            <a href="ver_animal.php?id=<?= $prenhez['animal_id'] ?>" class="btn btn-secondary ms-2">Cancelar</a>
        </form>
    </div>
</div>

<script>
    document.querySelectorAll('input[name="sexo"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('campo-nome').classList.toggle('d-none', this.value !== 'femea');
        });
    });
</script>

<?php include 'footer.php'; ?>