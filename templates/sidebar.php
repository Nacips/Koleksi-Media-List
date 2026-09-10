<?php
$currentFolder = basename(dirname($_SERVER['PHP_SELF']));
$currentPage   = basename($_SERVER['PHP_SELF']);
?>

<aside class="sidebar collapsed" id="sidebar">
    <div class="sidebar-brand d-block d-md-none p-3 fw-bold" style="color: var(--primary);">
        🌸 Menu Navigasi
    </div>
    
    <ul class="sidebar-menu">
        <!-- Tambahan menu Dashboard jika diakses dari sub-folder -->
        <li>
            <a href="../dashboard/index.php" class="<?= ($currentFolder == "dashboard") ? "active" : ""; ?>">
                📊 Dashboard
            </a>
        </li>
        <li>
            <a href="../anime/index.php" class="<?= ($currentFolder == "anime") ? "active" : ""; ?>">
                🎬 Anime
            </a>
        </li>
        <li>
            <a href="../manga/index.php" class="<?= ($currentFolder == "manga") ? "active" : ""; ?>">
                📖 Manga
            </a>
        </li>
        <li>
            <a href="../manhua/index.php" class="<?= ($currentFolder == "manhua") ? "active" : ""; ?>">
                📜 Manhua
            </a>
        </li>
        <li>
            <a href="../manhwa/index.php" class="<?= ($currentFolder == "manhwa") ? "active" : ""; ?>">
                📱 Manhwa
            </a>
        </li>
        <li>
            <a href="../novel/index.php" class="<?= ($currentFolder == "novel") ? "active" : ""; ?>">
                📚 Novel
            </a>
        </li>

        <li class="mt-4">
            <a href="../auth/logout.php" class="text-danger fw-bold">
                🚪 Logout
            </a>
        </li>
    </ul>
</aside>