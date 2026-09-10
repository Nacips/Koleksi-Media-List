<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit();
}

if (isset($_POST['simpan'])) {
    $nama   = mysqli_real_escape_string($conn, $_POST['nama']);
    $status = $_POST['status'];
    $gambar = "";

    $tipe_input = $_POST['tipe_input'];

    if ($tipe_input == 'file' && isset($_FILES['gambar_file']) && $_FILES['gambar_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath   = $_FILES['gambar_file']['tmp_name'];
        $fileName      = $_FILES['gambar_file']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        if (in_array($fileExtension, $allowedExtensions)) {
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

            $uploadFileDir = '../uploads/';

            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }

            $dest_path = $uploadFileDir . $newFileName;

            if(move_uploaded_file($fileTmpPath, $dest_path)) {
                $gambar = '../uploads/' . $newFileName;
            }
        }
    } else {
        $gambar = mysqli_real_escape_string($conn, $_POST['gambar_url']);
    }

    if (!empty($gambar)) {
        $query = "INSERT INTO manhua (nama, gambar, status) VALUES ('$nama', '$gambar', '$status')";

        if (mysqli_query($conn, $query)) {
            header("Location: index.php");
            exit();
        }
    }
}

$title = "Tambah Manhua";
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
                        <h4 class="fw-bold mb-4" style="color: var(--primary);">Tambah Manhua Baru</h4>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Manhua</label>
                                <input type="text" name="nama" class="form-control rounded-pill px-3" placeholder="Contoh: One Piece" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Sumber Gambar</label>
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
                                    <input type="url" name="gambar_url" id="inputUrl" class="form-control rounded-pill px-3" placeholder="https://..." >
                                </div>

                                <div id="inputFileContainer" style="display: none;">
                                    <input type="file" name="gambar_file" id="inputFile" class="form-control rounded-pill px-3" accept="image/*">
                                    <div class="form-text small text-muted mt-1">Format yang diizinkan: JPG, JPEG, PNG, WEBP, GIF.</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <select name="status" class="form-select rounded-pill px-3" required>
                                    <option value="Reading">Reading</option>
                                    <option value="Unread">Unread</option>
                                    <option value="Finished">Finished</option>
                                </select>
                            </div>

                            <div class="d-flex flex-column flex-sm-row gap-2 mt-4">
                                <button type="submit" name="simpan" class="btn btn-pink-primary rounded-pill px-4 fw-bold">Simpan</button>
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