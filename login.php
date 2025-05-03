<?php
session_start();
$conn = new mysqli("localhost", "root", "", "db_perpustakaan"); // Ganti nama DB

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST["nama"]);
    $kelas = $_POST["kelas"];
    $jurusan = trim($_POST["jurusan"]);

    if ($nama && $kelas && $jurusan) {
        // Cek apakah sudah ada di DB
        $stmt = $conn->prepare("SELECT * FROM siswa WHERE nama = ? AND kelas = ? AND jurusan = ?");
        $stmt->bind_param("sss", $nama, $kelas, $jurusan);
        $stmt->execute();
        $result = $stmt->get_result();

        // Kalau belum ada, insert
        if ($result->num_rows == 0) {
            $stmt = $conn->prepare("INSERT INTO siswa (nama, kelas, jurusan) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nama, $kelas, $jurusan);
            $stmt->execute();
        }

        $_SESSION["nama"] = $nama;
        $_SESSION["kelas"] = $kelas;
        $_SESSION["jurusan"] = $jurusan;

        header("Location: index.php");
        exit();
    } else {
        $error = "Semua kolom wajib diisi.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Perpustakaan</title>
  <style>
    * {
      margin: 0; padding: 0;
      box-sizing: border-box;
      font-family: Arial, sans-serif;
    }
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background: #f3f4f6;
    }
    .login-container {
      background: white;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 400px;
    }
    h2 {
      margin-bottom: 1.5rem;
      text-align: center;
      color: #333;
    }
    label {
      display: block;
      margin-bottom: 0.5rem;
      color: #555;
    }
    input, select {
      width: 100%;
      padding: 0.7rem;
      margin-bottom: 1rem;
      border: 1px solid #ccc;
      border-radius: 8px;
    }
    button {
      width: 100%;
      padding: 0.8rem;
      background: #2563eb;
      color: white;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s;
    }
    button:hover {
      background: #1d4ed8;
    }
    .error {
      color: red;
      margin-bottom: 1rem;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>Login Perpustakaan</h2>
    <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
    <form method="POST">
      <label for="nama">Nama</label>
      <input type="text" name="nama" id="nama" required>

      <label for="kelas">Kelas</label>
      <select name="kelas" id="kelas" required>
        <option value="">Pilih Kelas</option>
        <option value="X">X</option>
        <option value="XI">XI</option>
        <option value="XII">XII</option>
      </select>

      <label for="jurusan">Jurusan</label>
      <input type="text" name="jurusan" id="jurusan" required>

      <button type="submit">Masuk</button>
    </form>
  </div>
</body>
</html>
