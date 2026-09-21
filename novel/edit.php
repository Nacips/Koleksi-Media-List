<?php
session_start();
include '../config/database.php';

// Cek apakah user sudah login
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Cek apakah parameter ID tersedia
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM novel WHERE id = '$id'");
$data = mysqli_fetch_assoc($result);

// Cek apakah data novel ditemukan
if (!$data) {
    header("Location: index.php");
    exit();
}

// Proses form update
if (isset($_POST['update'])) {
    $nama   = mysqli_real_escape_string($conn, $_POST['nama']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $gambar = $data['gambar']; // Default menggunakan gambar lama

    $tipe_input = $_POST['tipe_input'] ?? 'url';

    // Logika jika menggunakan URL
    if ($tipe_input === 'url' && !empty($_POST['gambar_url'])) {
        $gambar = mysqli_real_escape_string($conn, $_POST['gambar_url']);
    } 
    // Logika jika menggunakan Upload File
    elseif ($tipe_input === 'file' && isset($_FILES['gambar_file']) && $_FILES['gambar_file']['error'] === 0) {
        $file_name   = $_FILES['gambar_file']['name'];
        $file_tmp    = $_FILES['gambar_file']['tmp_name'];
        $file_ext    = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        if (in_array($file_ext, $allowed_ext)) {
            $new_file_name = uniqid('img_', true) . '.' . $file_ext;
            $upload_dir    = '../uploads/'; // Sesuaikan dengan folder penyimpanan Anda

            // Buat folder jika belum ada
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            if (move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
                // Hapus file lama jika ada dan bukan berupa URL eksternal
                if (!filter_var($data['gambar'], FILTER_VALIDATE_URL) && file_exists($upload_dir . $data['gambar'])) {
                    unlink($upload_dir . $data['gambar']);
                }
                $gambar = $new_file_name;
            }
        }
    }

    // Eksekusi Query Update
    $query = "UPDATE novel SET nama = '$nama', gambar = '$gambar', status = '$status' WHERE id = '$id'";

    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
        exit();
    }
}

$title = "Edit Novel";
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
                        <h4 class="fw-bold mb-4" style="color: var(--primary);">Edit Novel</h4>
                        
                        <!-- Tambahkan enctype agar form bisa memproses file upload -->
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Nama Novel</label>
                                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Gambar</label>
                                <div class="d-flex gap-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tipe_input" id="tipeUrl" value="url" checked onclick="toggleInput('url')">
                                        <label class="form-check-label" for="tipeUrl">Gunakan URL</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tipe_input" id="tipeFile" value="file" onclick="toggleInput('file')">
                                        <label class="form-check-label" for="tipeFile">Upload File</label>
                                    </div>
                                </div>

                                <div id="inputUrlContainer">
                                    <input type="url" name="gambar_url" id="inputUrl" class="form-control rounded-pill px-3" placeholder="https://...">
                                </div>

                                <div id="inputFileContainer" style="display: none;">
                                    <input type="file" name="gambar_file" id="inputFile" class="form-control rounded-pill px-3" accept="image/*">
                                    <div class="form-text small text-muted mt-1">Format yang diizinkan: JPG, JPEG, PNG, WEBP, GIF.</div>
                                </div>
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