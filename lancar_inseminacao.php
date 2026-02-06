<?php 
include 'header.php'; 
$animals = $pdo->query("SELECT * FROM animais ORDER BY nome")->fetchAll();
?>
<div class="borda_baixo"><p><br></p></div>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <h2 class="mb-4 text-center">Registrar Inseminação / Prenhez</h2>
        <div class="card shadow">
            <div class="card-body p-5">
                <form action="add_prenhez.php" method="post">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" id="animal_id" name="animal_id" required>
                                    <option value="">-- Selecione o animal --</option>
                                    <?php foreach($animals as $a): ?>
                                        <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['nome']) ?> <?= $a['numero'] ? "({$a['numero']})" : "" ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="animal_id">Animal *</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="data_inseminacao" name="data_inseminacao" required>
                                <label for="data_inseminacao">Data da Inseminação *</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="touro" name="touro">
                                <label for="touro">Nome do Touro</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="raca_touro" name="raca_touro">
                                <label for="raca_touro">Raça do Touro</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <select class="form-select" id="tipo_inseminacao" name="tipo_inseminacao" required>
                                    <option value="Normal">Normal</option>
                                    <option value="Sexado">Sexado</option>
                                </select>
                                <label for="tipo_inseminacao">Tipo de Inseminação *</label>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-5">
                        <button class="btn btn-primary btn-lg px-5" type="submit">Registrar Prenhez</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>