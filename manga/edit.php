<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM manga WHERE id = '$id'");
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['update'])) {
    $nama   = mysqli_real_escape_string($conn, $_POST['nama']);
    $gambar = mysqli_real_escape_string($conn, $_POST['gambar']);
    $status = $_POST['status'];

    $query = "UPDATE manga SET nama = '$nama', gambar = '$gambar', status = '$status' WHERE id = '$id'";

    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
        exit();
    }
}

$title = "Edit Manga";
include '../templates/header.php';
include '../templates/navbar.php';
?>

<div class="dashboard-container d-flex flex-column flex-md-row">
    <?php include '../templates/sidebar.php'; ?>

    <main class="main-content w-100">
        <div class="container-fluid px-3 px-md-4 mt-4">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card p-3 p-md-4 border-0 shadow-sm rounded-4" style="background-color: #ffebf0;">
                        <h4 class="fw-bold mb-4" style="color: var(--primary);">Edit Manga</h4>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nama Manga</label>
                                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Gambar</label>
                                <input type="url" name="gambar" class="form-control" value="<?= htmlspecialchars($data['gambar']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="Reading" <?= $data['status'] == 'Reading' ? 'selected' : ''; ?>>Reading</option>
                                    <option value="Unread" <?= $data['status'] == 'Unread' ? 'selected' : ''; ?>>Unread</option>
                                    <option value="Finished" <?= $data['status'] == 'Finished' ? 'selected' : ''; ?>>Finished</option>
                                </select>
                            </div>
                            <div class="d-flex flex-column flex-sm-row gap-2 mt-4">
                                <button type="submit" name="update" class="btn btn-pink-primary rounded-pill px-4">Update Data</button>
                                <a href="index.php" class="btn btn-pink-secondary rounded-pill px-4 text-center">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <?php include '../templates/footer.php'; ?>
        </div>
    </main>
</div>