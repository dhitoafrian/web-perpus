<?php
session_start();
if (!isset($_SESSION["nama"])) {
  header("Location: login.php");
  exit();
}
require_once "koneksi.php";

$id = $_GET['id'] ?? 0;

$query = "SELECT * FROM buku WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
  echo "Buku tidak ditemukan.";
  exit;
}

$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $data['judul']; ?> | Perpustakaan Digital</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <style>
    :root {
      --primary: #4361ee;
      --primary-light: #4895ef;
      --secondary: #3f37c9;
      --dark: #1a1a2e;
      --light: #f8f9fa;
      --gray: #6c757d;
      --card-bg: white;
    }
    
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      min-height: 100vh;
      background: 
        linear-gradient(135deg, rgba(26, 26, 46, 0.9) 0%, rgba(22, 33, 62, 0.9) 100%),
        url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f') center/cover fixed;
      background-color: #1a1a2e;
      color: var(--light);
      color: var(--dark);
      line-height: 1.6;
    }
    
    .container {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      padding: 2rem;
      max-width: 1200px;
      margin: 0 auto;
    }
    
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }
    
    .logo {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--primary);
      display: flex;
      align-items: center;
      gap: 0.5rem;
      text-decoration: none;
    }
    
    .logo .material-icons-round {
      font-size: 1.8rem;
    }
    
    .user-info {
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    
    .user-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: var(--primary);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
    }
    
    .book-detail-container {
      display: flex;
      gap: 3rem;
      margin-top: 2rem;
      background: var(--card-bg);
      border-radius: 16px;
      padding: 2rem;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }
    
    .book-cover {
      flex: 0 0 350px;
      height: 450px;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    
    .book-cover img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;
    }
    
    .book-cover:hover img {
      transform: scale(1.03);
    }
    
    .book-info {
      flex: 1;
    }
    
    .book-title {
      font-size: 2.2rem;
      margin-bottom: 1rem;
      color: var(--dark);
    }
    
    .book-meta {
      display: flex;
      gap: 2rem;
      margin-bottom: 1.5rem;
    }
    
    .meta-item {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      color: var(--gray);
    }
    
    .book-description {
      line-height: 1.8;
      margin-bottom: 2rem;
    }
    
    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      color: white;
      padding: 0.8rem 1.5rem;
      border-radius: 12px;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s ease;
      border: none;
      cursor: pointer;
      box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    }
    
    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
    }
    
    .back-link {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      color: var(--primary);
      text-decoration: none;
      font-weight: 500;
      margin-top: 2rem;
      transition: opacity 0.3s ease;
    }
    
    .back-link:hover {
      opacity: 0.8;
    }
    
    /* Responsive */
    @media (max-width: 900px) {
      .book-detail-container {
        flex-direction: column;
      }
      
      .book-cover {
        flex: 1;
        height: 400px;
      }
    }
    
    @media (max-width: 600px) {
      .container {
        padding: 1.5rem;
      }
      
      .book-cover {
        height: 350px;
      }
      
      .book-title {
        font-size: 1.8rem;
      }
      
      .book-meta {
        flex-direction: column;
        gap: 0.5rem;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <header>
      <a href="index.php" class="logo">
        <span class="material-icons-round">menu_book</span>
        <span>DigitalLib</span>
      </a>
      <div class="user-info">
        <div class="user-avatar"><?php echo strtoupper(substr($_SESSION["nama"], 0, 1)); ?></div>
      </div>
    </header>
    
    <div class="book-detail-container">
      <div class="book-cover">
        <img src="img/buku/<?php echo htmlspecialchars($data['gambar']); ?>" alt="<?php echo htmlspecialchars($data['judul']); ?>">
      </div>
      
      <div class="book-info">
        <h1 class="book-title"><?php echo htmlspecialchars($data['judul']); ?></h1>
        
        <div class="book-meta">
          <div class="meta-item">
            <span class="material-icons-round">category</span>
            <span><?php echo ucfirst($data['kategori']); ?></span>
          </div>
          <div class="meta-item">
            <span class="material-icons-round">person</span>
            <span><?php echo htmlspecialchars($data['penulis'] ?? 'Penulis Tidak Diketahui'); ?></span>
          </div>
          <div class="meta-item">
            <span class="material-icons-round">calendar_today</span>
            <span><?php echo $data['tahun_terbit'] ?? '-'; ?></span>
          </div>
        </div>
        
        <div class="book-description">
          <?php echo nl2br(htmlspecialchars($data['deskripsi'])); ?>
        </div>
        
        <form action="proses_pinjam.php" method="post">
          <input type="hidden" name="id_buku" value="<?php echo $data['id']; ?>">
          <button type="submit" class="btn">
            <span class="material-icons-round">add_shopping_cart</span>
            Pinjam Buku Ini
          </button>
        </form>
        
        <a href="peminjaman.php?kategori=<?php echo $data['kategori']; ?>" class="back-link">
          <span class="material-icons-round">arrow_back</span>
          Kembali ke Daftar Buku
        </a>
      </div>
    </div>
  </div>
</body>
</html>