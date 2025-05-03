<?php
session_start();
if (!isset($_SESSION["nama"])) {
  header("Location: login.php");
  exit();
}

require_once "koneksi.php";

$kategori = $_GET['kategori'] ?? 'mapel';
$query = "SELECT * FROM buku WHERE kategori = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $kategori);
$stmt->execute();
$result = $stmt->get_result();

$nama = $_SESSION["nama"];
$kelas = $_SESSION["kelas"];
$jurusan = $_SESSION["jurusan"];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Peminjaman Buku - <?php echo ucfirst($kategori); ?> | Perpustakaan Digital</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <style>
    :root {
      --primary: #4361ee;
      --primary-light: #4895ef;
      --secondary: #3f37c9;
      --dark: #1a1a2e;
      --light: #f8f9fa;
      --danger: #f72585;
      --success: #4cc9f0;
      --card-bg: rgba(255, 255, 255, 0.1);
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
      margin-bottom: 3rem;
    }
    
    .logo {
      font-size: 1.8rem;
      font-weight: 700;
      color: white;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .logo .material-icons-round {
      font-size: 2rem;
      color: var(--primary-light);
    }
    
    .user-info {
      text-align: right;
    }
    
    .user-info .name {
      font-weight: 600;
      font-size: 1.1rem;
    }
    
    .user-info .class {
      font-size: 0.9rem;
      opacity: 0.8;
    }
    
    .main-content {
      flex: 1;
      display: flex;
      flex-direction: column;
    }
    
    .page-header {
      text-align: center;
      margin-bottom: 3rem;
    }
    
    .page-header h1 {
      font-size: 2.5rem;
      margin-bottom: 1rem;
      background: linear-gradient(90deg, var(--primary-light), var(--success));
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      line-height: 1.2;
    }
    
    .page-header .category-badge {
      display: inline-block;
      background: var(--primary);
      color: white;
      padding: 0.5rem 1.5rem;
      border-radius: 50px;
      font-size: 1rem;
      font-weight: 500;
      box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    }
    
    .books-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 1.5rem;
      margin-bottom: 3rem;
    }
    
    .book-card {
      background: var(--card-bg);
      backdrop-filter: blur(10px);
      border-radius: 16px;
      overflow: hidden;
      transition: all 0.3s ease;
      border: 1px solid rgba(255, 255, 255, 0.1);
      box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
    }
    
    .book-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.25);
      border-color: rgba(255, 255, 255, 0.2);
    }
    
    .book-cover {
      height: 320px;
      position: relative;
      overflow: hidden;
    }
    
    .book-cover img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;
    }
    
    .book-card:hover .book-cover img {
      transform: scale(1.05);
    }
    
    .book-title-overlay {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
      padding: 1.5rem 1rem 1rem;
    }
    
    .book-title-overlay h3 {
      margin: 0;
      font-size: 1.3rem;
      color: white;
      text-shadow: 0 2px 4px rgba(0,0,0,0.5);
    }
    
    .book-details {
      padding: 1.5rem;
    }
    
    .book-meta {
      display: flex;
      justify-content: space-between;
      margin-bottom: 1rem;
      font-size: 0.9rem;
      opacity: 0.9;
    }
    
    .book-author {
      display: flex;
      align-items: center;
      gap: 0.3rem;
    }
    
    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      color: white;
      padding: 0.8rem 1.5rem;
      border-radius: 50px;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s ease;
      border: none;
      cursor: pointer;
      box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
      width: 100%;
    }
    
    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
    }
    
    .back-link {
      margin-top: 2rem;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      color: var(--primary-light);
      text-decoration: none;
      font-weight: 500;
      transition: opacity 0.3s ease;
    }
    
    .back-link:hover {
      opacity: 0.8;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
      .container {
        padding: 1.5rem;
      }
      
      header {
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 2rem;
      }
      
      .user-info {
        text-align: center;
      }
      
      .page-header h1 {
        font-size: 2rem;
      }
      
      .books-grid {
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
      }
      
      .book-cover {
        height: 280px;
      }
    }
    
    @media (max-width: 480px) {
      .books-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <header>
      <div class="logo">
        <span class="material-icons-round">menu_book</span>
        <span>DigitalLib</span>
      </div>
      <div class="user-info">
        <div class="name"><?php echo htmlspecialchars($nama); ?></div>
        <div class="class"><?php echo $kelas; ?> - <?php echo htmlspecialchars($jurusan); ?></div>
      </div>
    </header>
    
    <main class="main-content">
      <div class="page-header">
        <h1>Peminjaman Buku</h1>
        <div class="category-badge">Kategori: <?php echo ucfirst($kategori); ?></div>
      </div>
      
      <div class="books-grid">
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="book-card">
            <div class="book-cover">
              <img src="img/buku/<?php echo htmlspecialchars($row['gambar']); ?>" alt="<?php echo htmlspecialchars($row['judul']); ?>">
              <div class="book-title-overlay">
                <h3><?php echo htmlspecialchars($row['judul']); ?></h3>
              </div>
            </div>
            <div class="book-details">
              <div class="book-meta">
                <span class="book-author">
                  <span class="material-icons-round" style="font-size: 1rem;">person</span>
                  <?php echo htmlspecialchars($row['penulis'] ?? 'Penulis Tidak Diketahui'); ?>
                </span>
                <span><?php echo $row['tahun_terbit'] ?? '-'; ?></span>
              </div>
              <a href="buku.php?id=<?php echo $row['id']; ?>" class="btn">
                <span class="material-icons-round">add_shopping_cart</span>
                Pinjam Buku
              </a>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
      
      <a href="index.php" class="back-link">
        <span class="material-icons-round">arrow_back</span>
        Kembali ke Beranda
      </a>
    </main>
  </div>
</body>
</html>