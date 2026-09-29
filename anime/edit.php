<?php
session_start();
include '../config/database.php';

// Cek login user
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM anime WHERE id = '$id'");
$data = mysqli_fetch_assoc($result);

// Cek data anime
if (!$data) {
    header("Location: index.php");
    exit();
}

// Cek gambar saat in
$is_url = filter_var($data['gambar'], FILTER_VALIDATE_URL);

if (isset($_POST['update'])) {
    $nama   = mysqli_real_escape_string($conn, $_POST['nama']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $review = mysqli_real_escape_string($conn, $_POST['review']); 
    $gambar = $data['gambar']; 

    $tipe_input = $_POST['tipe_input'] ?? 'url';

    if ($tipe_input === 'url' && !empty($_POST['gambar_url'])) {
        $gambar = mysqli_real_escape_string($conn, $_POST['gambar_url']);
    } 

    elseif ($tipe_input === 'file' && isset($_FILES['gambar_file']) && $_FILES['gambar_file']['error'] === 0) {
        $file_name   = $_FILES['gambar_file']['name'];
        $file_tmp    = $_FILES['gambar_file']['tmp_name'];
        $file_ext    = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        if (in_array($file_ext, $allowed_ext)) {
            $new_file_name = uniqid('img_', true) . '.' . $file_ext;
            $upload_dir    = '../uploads/';

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            if (move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
  
                if (!filter_var($data['gambar'], FILTER_VALIDATE_URL) && file_exists($upload_dir . $data['gambar'])) {
                    unlink($upload_dir . $data['gambar']);
                }
                $gambar = $new_file_name;
            }
        }
    }

    $query = "UPDATE anime SET nama = '$nama', gambar = '$gambar', status = '$status', review = '$review' WHERE id = '$id'";

    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
        exit();
    }
}

$title = "Edit Anime";
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
                        <h4 class="fw-bold mb-4" style="color: var(--primary);">Edit Anime</h4>
                        
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Anime</label>
                                <input type="text" name="nama" class="form-control rounded-pill px-3" value="<?= htmlspecialchars($data['nama']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Gambar</label>
                                
                                <!-- Preview Gambar Saat Ini -->
                                <div class="mb-2">
                                    <small class="text-muted d-block mb-1">Gambar saat ini:</small>
                                    <?php if ($is_url): ?>
                                        <img src="<?= htmlspecialchars($data['gambar']); ?>" alt="Preview" class="rounded" style="width: 80px; height: 100px; object-fit: cover;">
                                    <?php else: ?>
                                        <img src="../uploads/<?= htmlspecialchars($data['gambar']); ?>" alt="Preview" class="rounded" style="width: 80px; height: 100px; object-fit: cover;">
                                    <?php endif; ?>
                                </div>

                                <div class="d-flex gap-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tipe_input" id="tipeUrl" value="url" <?= $is_url ? 'checked' : ''; ?> onclick="toggleInput('url')">
                                        <label class="form-check-label" for="tipeUrl">Gunakan URL</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tipe_input" id="tipeFile" value="file" <?= !$is_url ? 'checked' : ''; ?> onclick="toggleInput('file')">
                                        <label class="form-check-label" for="tipeFile">Upload File Baru</label>
                                    </div>
                                </div>

                                <div id="inputUrlContainer" style="<?= $is_url ? '' : 'display: none;'; ?>">
                                    <input type="url" name="gambar_url" id="inputUrl" class="form-control rounded-pill px-3" placeholder="https://..." value="<?= $is_url ? htmlspecialchars($data['gambar']) : ''; ?>">
                                </div>

                                <div id="inputFileContainer" style="<?= !$is_url ? '' : 'display: none;'; ?>">
                                    <input type="file" name="gambar_file" id="inputFile" class="form-control rounded-pill px-3" accept="image/*">
                                    <div class="form-text small text-muted mt-1">Biarkan kosong jika tidak ingin mengubah file gambar. Format diizinkan: JPG, JPEG, PNG, WEBP, GIF.</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <select name="status" class="form-select rounded-pill px-3" required>
                                    <option value="Watching" <?= $data['status'] == 'Watching' ? 'selected' : ''; ?>>Watching</option>
                                    <option value="Unwatched" <?= $data['status'] == 'Unwatched' ? 'selected' : ''; ?>>Unwatched</option>
                                    <option value="Finished" <?= $data['status'] == 'Finished' ? 'selected' : ''; ?>>Finished</option>
                                </select>
                            </div>

                            <!-- Tambahan Input Kolom Review -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Review / Catatan</label>
                                <textarea name="review" class="form-control rounded-4 px-3 py-2" rows="4" placeholder="Tulis review atau catatan tentang anime ini..."><?= htmlspecialchars($data['review'] ?? ''); ?></textarea>
                            </div>

                            <div class="d-flex flex-column flex-sm-row gap-2 mt-4">
                                <button type="submit" name="update" class="btn btn-pink-primary rounded-pill px-4 fw-bold">Update Data</button>
                                <a href="index.php" class="btn btn-pink-secondary rounded-pill px-4 text-center fw-bold">Batal</a>
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

<script>
    function toggleInput(type) {
        const urlContainer = document.getElementById('inputUrlContainer');
        const fileContainer = document.getElementById('inputFileContainer');
        const inputUrl = document.getElementById('inputUrl');

        if (type === 'url') {
            urlContainer.style.display = 'block';
            fileContainer.style.display = 'none';
            inputUrl.setAttribute('required', 'required');
        } else {
            urlContainer.style.display = 'none';
            fileContainer.style.display = 'block';
            inputUrl.removeAttribute('required');
        }
    }
</script>