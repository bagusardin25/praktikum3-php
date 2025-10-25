<?php
require_once 'Database.php';

/**
 * Class Produk
 * Menangani operasi CRUD untuk produk
 */
class Produk
{
  // Properties
  private $db;
  private $conn;

  // Properties produk
  private $id;
  private $nama;
  private $deskripsi;
  private $harga;
  private $foto;

  /**
   * Constructor
   * Dipanggil otomatis saat object dibuat
   */
  public function __construct()
  {
    $this->db = new Database();
    $this->conn = $this->db->getConnection();
  }

  // ---------------------------
  // Setter Methods
  // ---------------------------

  public function setNama($nama)
  {
    $this->nama = $nama;
  }

  public function setDeskripsi($deskripsi)
  {
    $this->deskripsi = $deskripsi;
  }

  public function setHarga($harga)
  {
    $this->harga = $harga;
  }

  public function setFoto($foto)
  {
    $this->foto = $foto;
  }

  // ---------------------------
  // Getter Methods
  // ---------------------------

  public function getId()
  {
    return $this->id;
  }

  public function getNama()
  {
    return $this->nama;
  }

  public function getDeskripsi()
  {
    return $this->deskripsi;
  }

  public function getHarga()
  {
    return $this->harga;
  }

  public function getFoto()
  {
    return $this->foto;
  }

  // ---------------------------
  // CREATE: Tambah produk baru
  // ---------------------------
  public function create()
  {
    $query = "INSERT INTO produk (nama, deskripsi, harga, foto) VALUES (?, ?, ?, ?)";

    $stmt = $this->conn->prepare($query);
    $stmt->bind_param(
      "ssds",
      $this->nama,
      $this->deskripsi,
      $this->harga,
      $this->foto
    );

    if ($stmt->execute()) {
      return true;
    }
    return false;
  }

  // ---------------------------
  // READ: Ambil semua produk
  // ---------------------------
  public function readAll()
  {
    $query = "SELECT * FROM produk ORDER BY created_at DESC";
    $result = $this->conn->query($query);
    return $result;
  }

  // ---------------------------
  // READ: Ambil satu produk berdasarkan ID
  // ---------------------------
  public function readOne($id)
  {
    $query = "SELECT * FROM produk WHERE id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
  }

  // ---------------------------
  // UPLOAD FOTO: Simpan file gambar produk
  // ---------------------------
  public function uploadFoto($file)
  {
    $target_dir = "uploads/";
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

    $new_filename = uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;

    // Validasi ekstensi file
    $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
    if (!in_array($file_extension, $allowed_types)) {
      return false;
    }

    // Validasi ukuran file (maksimum 2MB)
    if ($file["size"] > 2000000) {
      return false;
    }

    // Upload file ke folder uploads/
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
      return $new_filename;
    }

    return false;
  }

// function baru untuk delete
  public function delete($id)
  {
    $data = $this->readOne($id);
    if (!$data) {
      return false;
    }
    if (!empty($data['foto']) && file_exists('uploads/' . $data['foto'])) {
      unlink('uploads/' . $data['foto']); 
    }
    $query = "DELETE FROM produk WHERE id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
  }

  // Function baru untuk update
  public function update($id){
    $query = "UPDATE produk SET nama = ?, deskripsi = ?, harga = ?, foto = ? WHERE id=?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param(
      "ssdsi",
      $this->nama,
      $this->deskripsi,
      $this->harga,
      $this->foto,
      $id
    );
    return $stmt->execute();
  }
}
