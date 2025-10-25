<?php
require_once 'config/database.php';
require_once 'classes/Produk.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header("location: index.php");
    exit();
}
$produkObj = new Produk();
$data = $produkObj->readOne($id);

if (!$data){
    header("location: index.php");
    exit();
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $produk = new Produk();

    $produk->setNama($_POST['nama']);
    $produk->setDeskripsi($_POST['deskripsi']);
    $produk->setHarga($_POST['harga']);

     // Handle upload foto

if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $new_foto = $produk->uploadFoto($_FILES['foto']);
    if ($new_foto) {
        if ($data['foto'] && file_exists('uploads/' . $data['foto'])) {
            unlink('uploads/' . $data['foto']);
        }
        $produk->setFoto($new_foto);
    } else {
        $error = "Gagal upload foto. Pastikan file adalah gambar (JPG, PNG, GIF) dan ukuran < 2MB";
    }
} else {
    $produk->setFoto($data['foto']);
}

if (empty($error)){
    if ($produk->update($id)) {
        $message = "Produk berhasil diupdate!";
        header("refresh:2;url=detail.php?id=$id");
    } else {
        $error = "Gagal mengupdate produk!";
    }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>✏️ Edit Produk</h1>

    <!-- Tampilkan pesan sukses atau error -->
    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Form edit produk -->
    <form method="POST" enctype="multipart/form-data" class="form">

        <div class="form-group">
            <label for="nama">Nama Produk:</label>
            <input type="text" id="nama" name="nama"
                   value="<?php echo htmlspecialchars($data['nama']); ?>" required>
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi:</label>
            <textarea id="deskripsi" name="deskripsi" rows="5"><?php echo htmlspecialchars($data['deskripsi']); ?></textarea>
        </div>

        <div class="form-group">
            <label for="harga">Harga (Rp):</label>
            <input type="number" id="harga" name="harga"
                   value="<?php echo htmlspecialchars($data['harga']); ?>" step="0.01" required>
        </div>

        <div class="form-group">
            <label>Foto Saat Ini:</label>
            <?php if ($data['foto']): ?>
                <img src="uploads/<?php echo $data['foto']; ?>" alt="Foto Produk" style="max-width: 200px; display: block; margin: 10px 0;">
            <?php else: ?>
                <div class="no-image">Tidak ada gambar</div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="foto">Ganti Foto (optional):</label>
            <input type="file" id="foto" name="foto" accept="image/*">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Update</button>
            <a href="detail.php?id=<?php echo $id; ?>" class="btn btn-secondary">❌ Batal</a>
        </div>

    </form>
</div>
</body>
</html>