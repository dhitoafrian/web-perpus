<?php
session_start();
if (!isset($_SESSION["nama"])) {
  header("Location: login.php");
  exit();
}
require_once "koneksi.php";

$id_buku = $_POST['id_buku'];
$nama = $_SESSION["nama"];
$tanggal = date("Y-m-d");

$query = "INSERT INTO riwayat (nama, id_buku, tanggal_pinjam) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("sis", $nama, $id_buku, $tanggal);
$stmt->execute();

header("Location: riwayat.php");
exit();
