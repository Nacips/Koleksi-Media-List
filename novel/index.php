<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit();
}

$query = "SELECT * FROM novel ORDER BY id DESC";
$result = mysqli_query($conn, $query);

$title = "Daftar Novel";
include '../templates/header.php';
include '../templates/navbar.php';
?>

<div class="dashboard-container d-flex flex-column flex-md-row">
    <?php include '../templates/sidebar.php'; ?>

    <main class="main-content w-100 d-flex flex-column justify-content-between">
        <div>
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
                <h2 class="fw-bold m-0" style="color: var(--primary);">🎬 Daftar Novel</h2>
                <a href="tambah.php" class="btn btn-pink-primary rounded-pill px-4 fw-bold shadow-sm">+ Tambah Novel</a>
            </div>

            <div class="row row-cols-3 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-2 g-md-3">
                <?php if (mysqli_num_rows($result) > 0) : ?>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <div class="col">
                            <a href="edit.php?id=<?= $row['id']; ?>" class="text-decoration-none">
                                <div class="card-item shadow-sm">
                                    <div class="card-img-container">
                                        <?php 
                                            $status = $row['status'];
                                            $badgeClass = 'bg-secondary';
                                            if ($status == 'Reading') $badgeClass = 'bg-primary';
                                            elseif ($status == 'Finished') $badgeClass = 'bg-success';
                                            elseif ($status == 'Unread') $badgeClass = 'bg-warning text-dark';
                                        ?>
                                        <span class="badge rounded-pill <?= $badgeClass; ?> badge-up">
                                            <?= htmlspecialchars($status); ?>
                                        </span>
                                        
                                        <img src="<?= htmlspecialchars($row['gambar']); ?>" alt="<?= htmlspecialchars($row['nama']); ?>" onerror="this.src='https://via.placeholder.com/200x280?text=No+Image'">
                                        
                                        <span class="badge-kategori">Novel</span>
                                    </div>
                                </div>
                                <div class="card-title-text" title="<?= htmlspecialchars($row['nama']); ?>">
                                    <?= htmlspecialchars($row['nama']); ?>
                                </div>
                            </a>
                        </div>
                    <?php endwhile; ?>
                <?php else : ?>
                    <div class="col-12 w-100 text-center text-muted py-5 d-flex flex-column align-items-center justify-content-center" style="min-height: 350px; grid-column: 1 / -1;">
                        <div style="max-width: 350px;" class="mx-auto">
                            <h5 class="fw-bold mb-2" style="color: var(--primary);">Belum ada data novel.</h5>
                            <p class="small text-muted mb-3">Silakan tambahkan data novel terlebih dahulu.</p>
                            <a href="tambah.php" class="btn btn-sm btn-pink-primary rounded-pill px-4 py-2">+ Tambah Novel</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="mt-5">
            <?php include '../templates/footer.php'; ?>
        </div>
    </main>
</div>

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
        font-size: 0.6rem !important;
        font-weight: 700;
        padding: 3px 6px;
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
</style>