<?php
// -------------------------------
// File: delete.php
// Fungsi: Menangani proses hapus produk berdasarkan ID
// -------------------------------

// 1️⃣ Include file konfigurasi database dan class Produk
require_once 'config/database.php';
require_once 'classes/Produk.php';

// 2️⃣ Ambil ID produk dari URL (misal: delete.php?id=5)
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 3️⃣ Jika ID tidak valid, kembalikan ke halaman utama
if ($id <= 0) {
  header("Location: index.php");
  exit();
}

// 4️⃣ Buat object dari class Produk
$produk = new Produk();

// 5️⃣ Jalankan proses delete menggunakan method di class Produk
if ($produk->delete($id)) {
  // 6️⃣ Jika berhasil, redirect ke halaman index dengan pesan sukses
  header("Location: index.php?message=deleted");
  exit();
} else {
  // 7️⃣ Jika gagal, redirect ke detail produk dengan pesan error
  header("Location: detail.php?id=" . $id . "&error=delete_failed");
  exit();
}
?>
