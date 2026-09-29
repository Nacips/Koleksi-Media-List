<footer class="main-footer text-center py-4 mt-5 border-top border-pink-subtle" style="background: rgba(255, 240, 243, 0.6); backdrop-filter: blur(8px); color: #d63384; font-size: 0.9rem;">
    <div class="container">
        <p class="mb-1">&copy; 2026 <strong>🌸 Anime, Manhwa, Manhua, Manga, Novel List</strong></p>
        <p class="mb-0 text-muted small">Crafted with <span class="text-danger">❤️</span> for a better reading experience.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('toggleBtn');
        const sidebar = document.getElementById('sidebar');

        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                sidebar.classList.toggle('collapsed');
            });

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