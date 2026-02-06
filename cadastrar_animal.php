<?php include 'header.php'; ?>
<divclass="borda_baixo"><p><br></p></div>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <h2 class="mb-4 text-center">Cadastrar Novo Animal</h2>
        <div class="card shadow">
            <div class="card-body p-5">
                <form action="add_animal.php" method="post">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="nome" name="nome" required>
                                <label for="nome">Nome do Animal *</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="numero" name="numero">
                                <label for="numero">Número (opcional)</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="pai" name="pai">
                                <label for="pai">Nome do Pai (opcional)</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="mae" name="mae">
                                <label for="mae">Nome da Mãe (opcional)</label>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-5">
                        <button class="btn btn-primary btn-lg px-5" type="submit">Cadastrar Animal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>