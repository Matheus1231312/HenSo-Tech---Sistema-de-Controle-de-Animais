    </main>

    <button class="theme-toggle btn btn-lg btn-primary rounded-circle" id="themeToggle">🌙</button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const toggle = document.getElementById('themeToggle');
        const html = document.documentElement;

        const aplicarTema = (dark) => {
            if (dark) {
                html.setAttribute('data-bs-theme', 'dark');
                toggle.innerHTML = '☀️';
                localStorage.setItem('darkMode', 'enabled');
            } else {
                html.setAttribute('data-bs-theme', 'light');
                toggle.innerHTML = '🌙';
                localStorage.setItem('darkMode', 'disabled');
            }
        };

        const preferenciaSalva = localStorage.getItem('darkMode');
        if (preferenciaSalva === 'enabled') {
            aplicarTema(true);
        } else if (preferenciaSalva === 'disabled') {
            aplicarTema(false);
        } else {
            const prefereDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            aplicarTema(prefereDark);
        }

        toggle.addEventListener('click', () => {
            const estaDark = html.getAttribute('data-bs-theme') === 'dark';
            aplicarTema(!estaDark);
        });
    </script>
</body>
</html>