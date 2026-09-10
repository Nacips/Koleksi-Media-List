<footer class="main-footer rounded-3 mt-5">
    &copy; 2026 <strong>🌸 Anime, Manhwa, Manhua, Manga, Novel List</strong>. Created with ❤️
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('toggleBtn');
        const sidebar = document.getElementById('sidebar');

        if (toggleBtn && sidebar) {
            // Gunakan event click dengan stopPropagation agar aman di Mobile & Desktop
            toggleBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                sidebar.classList.toggle('collapsed');
            });

            // Opsional tambahan: Klik di luar sidebar pada layar HP otomatis menutup menu
            document.addEventListener('click', function (e) {
                if (window.innerWidth <= 768) {
                    if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                        sidebar.classList.add('collapsed');
                    }
                }
            });
        }
    });
</script>

</body>
</html>