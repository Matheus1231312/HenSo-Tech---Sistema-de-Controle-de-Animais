<nav class="sidebar bg-light vh-100 pt-4">
    <ul class="nav flex-column px-3">
        <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>" href="index.php">🏠 Home</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'cadastrar_animal.php' ? 'active' : '' ?>" href="cadastrar_animal.php">➕ Cadastrar Animais</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'lancar_inseminacao.php' ? 'active' : '' ?>" href="lancar_inseminacao.php">🐮 Lançar Inseminação</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'animais_cadastrados.php' ? 'active' : '' ?>" href="animais_cadastrados.php">📋 Animais Cadastrados</a>
        </li>
    </ul>
</nav>