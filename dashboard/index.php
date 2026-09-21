<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit();
}

$search   = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : 'Semua';

$limit = 500; 
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;

$baseQuery = "
    SELECT id, nama, gambar, status, 'Anime' AS kategori FROM anime
    UNION ALL
    SELECT id, nama, gambar, status, 'Manga' AS kategori FROM manga
    UNION ALL
    SELECT id, nama, gambar, status, 'Manhua' AS kategori FROM manhua
    UNION ALL
    SELECT id, nama, gambar, status, 'Manhwa' AS kategori FROM manhwa
    UNION ALL
    SELECT id, nama, gambar, status, 'Novel' AS kategori FROM novel
";

$sqlFiltered = "SELECT * FROM ($baseQuery) AS all_data WHERE 1=1";

if (!empty($search)) {
    $sqlFiltered .= " AND nama LIKE '%$search%'";
}

if ($kategori !== 'Semua') {
    $kategoriEsc = mysqli_real_escape_string($conn, $kategori);
    $sqlFiltered .= " AND kategori = '$kategoriEsc'";
}

$resultTotal = mysqli_query($conn, $sqlFiltered);
$totalData   = mysqli_num_rows($resultTotal);
$totalPages  = ceil($totalData / $limit);

$sqlFinal = $sqlFiltered . " ORDER BY id DESC LIMIT $start, $limit";
$resultAll = mysqli_query($conn, $sqlFinal);

$title = "Dashboard List";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .card-item {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            background-color: #fff0f3;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 15px rgba(255, 105, 180, 0.25);
        }
        
        .card-img-container {
            position: relative !important;
            width: 100% !important;
            aspect-ratio: 2 / 3 !important;
            overflow: hidden !important;
        }
        .card-img-container img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
        }
        
        .badge-up {
            position: absolute;
            top: 6px;
            left: 6px;
            background-color: #ff3366;
            color: #ffffff;
            font-size: 0.60rem;
            font-weight: 800;
            padding: 2px 5px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            z-index: 2;
        }
        .badge-kategori {
            position: absolute;
            bottom: 6px;
            left: 6px;
            background: rgba(0, 0, 0, 0.65);
            color: #fff;
            font-size: 0.6rem;
            padding: 2px 6px;
            border-radius: 4px;
            backdrop-filter: blur(4px);
            z-index: 2;
        }
        .badge-status {
            position: absolute;
            top: 6px;
            right: 6px;
            background: rgba(255, 51, 102, 0.85);
            color: #fff;
            font-size: 0.55rem;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 4px;
            backdrop-filter: blur(4px);
            z-index: 2;
            text-transform: capitalize;
        }
        .card-title-text {
            font-weight: 700;
            font-size: 0.85rem;
            color: #333;
            text-align: center;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 2.5em;
            margin-top: 6px;
            padding: 0 3px;
        }
        .filter-pill {
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }
        
        @media (max-width: 767.98px) {
            .main-content {
                padding: 0.8rem;
            }
            .search-form-container {
                width: 100% !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body>

    <header class="top-navbar">
        <div class="d-flex align-items-center gap-2">
            <button class="btn-toggle" id="toggleBtn">☰</button>
            <a href="index.php" class="navbar-brand-text">🌸 Koleksi Media List</a>
        </div>
        <div class="user-profile">
            <a href="../auth/logout.php" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold">Logout</a>
        </div>
    </header>

    <div class="dashboard-container">
        <aside class="sidebar collapsed" id="sidebar">
            <ul class="sidebar-menu">
                <li><a href="index.php" class="active">📊 Dashboard</a></li>
                <li><a href="../anime/index.php">🎬 Anime</a></li>
                <li><a href="../manga/index.php">📖 Manga</a></li>
                <li><a href="../manhua/index.php">📜 Manhua</a></li>
                <li><a href="../manhwa/index.php">📱 Manhwa</a></li>
                <li><a href="../novel/index.php">📚 Novel</a></li>
                <li class="mt-4">
                    <a href="../auth/logout.php" class="text-danger fw-bold">🚪 Logout</a>
                </li>
            </ul>
        </aside>

        <main class="main-content d-flex flex-column justify-content-between">
            <div>
                <!-- Bagian Header Judul dan Search Bar -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-3">
                    <h2 class="fw-bold m-0 fs-4" style="color: var(--primary);">✨ Semua Koleksi</h2>
                    
                    <!-- Search Bar Form -->
                    <form method="GET" action="" class="d-flex search-form-container" style="max-width: 300px; width: 100%;">
                        <?php if($kategori !== 'Semua'): ?>
                            <input type="hidden" name="kategori" value="<?= htmlspecialchars($kategori); ?>">
                        <?php endif; ?>
                        <input type="text" name="search" class="form-control form-control-sm rounded-pill px-3" placeholder="Cari judul..." value="<?= htmlspecialchars($search); ?>">
                        <button type="submit" class="btn btn-pink-primary btn-sm rounded-pill ms-2 px-3 text-nowrap">Cari</button>
                    </form>
                </div>

                <!-- Filter Kategori Buttons -->
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <?php 
                        $categories = ['Semua', 'Anime', 'Manga', 'Manhua', 'Manhwa', 'Novel'];
                        foreach ($categories as $cat): 
                            $isActive = ($kategori === $cat) ? 'btn-pink-primary' : 'btn-pink-secondary';
                            $queryLink = "?kategori=$cat" . (!empty($search) ? "&search=" . urlencode($search) : "");
                    ?>
                        <a href="<?= $queryLink; ?>" class="btn <?= $isActive; ?> filter-pill px-3 py-1"><?= $cat; ?></a>
                    <?php endforeach; ?>
                </div>
                
                <!-- Layout Card Grid -->
                <div class="row row-cols-3 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-2 g-md-3">
                    <?php if (mysqli_num_rows($resultAll) > 0) : ?>
                        <?php while ($row = mysqli_fetch_assoc($resultAll)) : ?>
                            <div class="col">
                                <a href="../<?= strtolower($row['kategori']); ?>/edit.php?id=<?= $row['id']; ?>" class="text-decoration-none">
                                    <div class="card-item">
                                        <div class="card-img-container">
                                            <span class="badge-up">UP</span>
                                            <img src="<?= htmlspecialchars($row['gambar']); ?>" alt="<?= htmlspecialchars($row['nama']); ?>" onerror="this.src='https://via.placeholder.com/200x280?text=No+Image'">
                                            <span class="badge-kategori"><?= $row['kategori']; ?></span>
                                            <span class="badge-status"><?= htmlspecialchars($row['status']); ?></span>
                                        </div>
                                    </div>
                                    <div class="card-title-text" title="<?= htmlspecialchars($row['nama']); ?>">
                                        <?= htmlspecialchars($row['nama']); ?>
                                    </div>
                                </a>
                            </div>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <div class="col-12 w-100 text-center text-muted py-5 d-flex flex-column align-items-center justify-content-center" style="min-height: 400px; grid-column: 1 / -1;">
                            <div style="max-width: 350px;" class="mx-auto">
                                <h5 class="fw-bold mb-2" style="color: var(--primary);">Tidak ada data yang ditemukan.</h5>
                                <p class="small text-muted mb-3">Belum ada item untuk kategori atau pencarian ini.</p>
                                <a href="index.php" class="btn btn-sm btn-pink-secondary rounded-pill px-4 py-2">Reset Filter/Pencarian</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Pagination Links -->
                <?php if ($totalPages > 1): ?>
                    <nav class="mt-5">
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <?php 
                                    $pageLink = "?page=$i";
                                    if ($kategori !== 'Semua') $pageLink .= "&kategori=" . urlencode($kategori);
                                    if (!empty($search)) $pageLink .= "&search=" . urlencode($search);
                                    $pageActive = ($page == $i) ? 'active' : '';
                                ?>
                                <li class="page-item <?= $pageActive; ?>">
                                    <a class="page-link rounded-circle mx-1 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; <?= $page == $i ? 'background-color: var(--primary); border-color: var(--primary); color: #fff;' : 'color: var(--primary);'; ?>" href="<?= $pageLink; ?>"><?= $i; ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>

            <footer class="main-footer rounded-3 mt-5">
                &copy; 2026 <strong>Koleksi Media List</strong>. Created with ❤️
            </footer>
        </main>
    </div>

    <script>
        const toggleBtn = document.getElementById('toggleBtn');
        const sidebar = document.getElementById('sidebar');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    </script>
</body>
</html>