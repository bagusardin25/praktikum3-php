<?php
require_once 'config/database.php';
require_once 'classes/Produk.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
  header("Location: index.php");
  exit();
}
$produk = new Produk();
if ($produk->delete($id)) {
  header("Location: index.php?message=deleted");
  exit();
} else {
  header("Location: detail.php?id=" . $id . "&error=delete_failed");
  exit();
}
?>
