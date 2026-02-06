<?php 
include 'header.php'; 

// Dados do banco
$hoje = new DateTime();

// Total de animais
$total_animais = $pdo->query("SELECT COUNT(*) FROM animais")->fetchColumn();

// Animais em lactação (produzindo) - assumindo que vacas em gestação e não secas estão produzindo
$lactantes = $pdo->query("
    SELECT COUNT(DISTINCT a.id)
    FROM animais a
    LEFT JOIN prenhezes p ON a.id = p.animal_id AND p.status = 'EmGestacao'
    WHERE 
        a.status_producao = 'Produzindo'
        OR (a.status_producao != 'Seca' AND p.vaca_seca = 0)
")->fetchColumn();

// Prenhes em gestação
$prenhes = $pdo->query("SELECT COUNT(*) FROM prenhezes WHERE status = 'EmGestacao'")->fetchColumn();

// Alertas (mesmo código anterior)
$stmtAlerts = $pdo->query("SELECT p.*, a.nome as animal_nome, a.numero as animal_numero FROM prenhezes p JOIN animais a ON a.id = p.animal_id WHERE p.status = 'EmGestacao'");
$alerts = $stmtAlerts->fetchAll();
?>

<!-- Estatísticas Principais (inspirado no MilkingCloud) -->
<divclass="borda_baixo"><p><br></p></div>
<div class="row mb-5 g-4">
    <div class="col-md-4 col-12">
        <div class="card text-center shadow border-0 h-100 bg-primary text-white">
            <div class="card-body py-5">
                <i class="fas fa-cow fa-3x mb-3"></i>
                <h2 class="display-4 fw-bold"><?= $total_animais ?></h2>
                <p class="fs-5 mb-0">Total de Animais</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="card text-center shadow border-0 h-100 bg-success text-white">
            <div class="card-body py-5">
                <i class="fas fa-tint fa-3x mb-3"></i>
                <h2 class="display-4 fw-bold"><?= $lactantes ?></h2>
                <p class="fs-5 mb-0">Em Lactação</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="card text-center shadow border-0 h-100 bg-warning text-white">
            <div class="card-body py-5">
                <i class="fas fa-baby fa-3x mb-3"></i>
                <h2 class="display-4 fw-bold"><?= $prenhes ?></h2>
                <p class="fs-5 mb-0">Prenhes</p>
            </div>
        </div>
    </div>
</div>

<!-- Grid de Funções Rápidas (inspirado no Livestock Manager) -->
<h3 class="mb-4 text-center fw-bold">Acesso Rápido</h3>
<div class="row row-cols-2 row-cols-md-4 g-4 mb-5">
    <div class="col">
        <a href="cadastrar_animal.php" class="text-decoration-none">
            <div class="card text-center shadow h-100 border-0 bg-light hover-shadow">
                <div class="card-body py-5">
                    <i class="fas fa-plus-circle fa-4x text-primary mb-3"></i>
                    <h5 class="fw-bold">Cadastrar Animais</h5>
                </div>
            </div>
        </a>
    </div>
    <div class="col">
        <a href="lancar_inseminacao.php" class="text-decoration-none">
            <div class="card text-center shadow h-100 border-0 bg-light hover-shadow">
                <div class="card-body py-5">
                    <i class="fas fa-syringe fa-4x text-success mb-3"></i>
                    <h5 class="fw-bold">Lançar Inseminação</h5>
                </div>
            </div>
        </a>
    </div>
    <div class="col">
        <a href="animais_cadastrados.php" class="text-decoration-none">
            <div class="card text-center shadow h-100 border-0 bg-light hover-shadow">
                <div class="card-body py-5">
                    <i class="fas fa-list-alt fa-4x text-info mb-3"></i>
                    <h5 class="fw-bold">Animais Cadastrados</h5>
                </div>
            </div>
        </a>
    </div>
    <div class="col">
        <a href="#calendario" class="text-decoration-none">
            <div class="card text-center shadow h-100 border-0 bg-light hover-shadow">
                <div class="card-body py-5">
                    <i class="fas fa-calendar-alt fa-4x text-danger mb-3"></i>
                    <h5 class="fw-bold">Calendário de Eventos</h5>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Alertas Importantes -->
<?php if (!empty($alerts)): ?>
    <h3 class="mb-4 text-center fw-bold text-danger">⚠️ Alertas Urgentes</h3>
    <div class="row g-4 mb-5">
        <?php foreach ($alerts as $p): 
            $data_ins = new DateTime($p['data_inseminacao']);
            $dias = $data_ins->diff($hoje)->days;
            
            if ($dias >= 210 && $p['vaca_seca'] == 0 && $p['aviso_secagem'] == 0): ?>
                <div class="col-lg-6">
                    <div class="alert alert-warning shadow d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?= htmlspecialchars($p['animal_nome']) ?></strong> completou 7 meses → <strong>Secar a vaca!</strong>
                        </div>
                        <form method="post" action="marcar_secagem.php">
                            <input type="hidden" name="prenhez_id" value="<?= $p['id'] ?>">
                            <button class="btn btn-warning btn-sm">Confirmar</button>
                        </form>
                    </div>
                </div>
            <?php endif;
            
            $prevParto = clone $data_ins; $prevParto->modify('+9 months');
            $diasParto = (int) $hoje->diff($prevParto)->format('%r%a');
            if ($diasParto >= 0 && $diasParto <= 7 && $p['aviso_parto'] == 0): ?>
                <div class="col-lg-6">
                    <div class="alert alert-danger shadow d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?= htmlspecialchars($p['animal_nome']) ?></strong> a <?= $diasParto ?> dias do parto!
                        </div>
                        <form method="post" action="marcar_parto_aviso.php">
                            <input type="hidden" name="prenhez_id" value="<?= $p['id'] ?>">
                            <button class="btn btn-danger btn-sm">Marcar Notificado</button>
                        </form>
                    </div>
                </div>
            <?php endif;
        endforeach; ?>
    </div>
<?php endif; ?>

<!-- Calendário -->
<div id="calendario" class="card shadow-lg border-0">
    <div class="card-header bg-primary text-white text-center py-4">
        <h3 class="mb-0">📅 Calendário de Eventos</h3>
    </div>
    <div class="card-body p-0">
        <div class="ratio ratio-21x9">
            <iframe src="https://calendar.google.com/calendar/embed?src=939db53fac90526475c3babb8446a9c0ef867d6372445b3c97f2356688c76da9%40group.calendar.google.com&ctz=America%2FSao_Paulo" style="border:0" allowfullscreen></iframe>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>