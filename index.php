<?php
session_start();
if (!isset($_SESSION["nama"])) {
  header("Location: login.php");
  exit();
}

$nama = $_SESSION["nama"];
$kelas = $_SESSION["kelas"];
$jurusan = $_SESSION["jurusan"];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Beranda Perpustakaan Digital</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <style>
    :root {
      --primary: #3b82f6;
      --primary-light: #60a5fa;
      --primary-dark: #2563eb;
      --secondary: #6366f1;
      --dark: #0f172a;
      --dark-light: #1e293b;
      --light: #f1f5f9;
      --light-accent: #e2e8f0;
      --text-primary: #f8fafc;
      --text-secondary: #cbd5e1;
      --accent: #8b5cf6;
      --success: #10b981;
      --danger: #ef4444;
      --warning: #f59e0b;
      --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
      --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
      --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
      --card-bg: rgba(30, 41, 59, 0.7);
      --header-bg: rgba(15, 23, 42, 0.9);
    }
    
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    
    body {
      font-family: 'Inter', sans-serif;
      margin: 0;
      padding: 0;
      min-height: 100vh;
      color: var(--text-primary);
      line-height: 1.6;
      background: 
     linear-gradient(135deg, rgba(26, 26, 46, 0.9) 0%, rgba(22, 33, 62, 0.9) 100%),
      url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f') center/cover fixed;
      background-color: var(--dark);
    }
    
    .container {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      max-width: 1280px;
      margin: 0 auto;
      position: relative;
    }
    
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1.5rem 2rem;
      background: transparent;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 100;
    }
    
    .header-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 100%;
      max-width: 1280px;
      margin: 0 auto;
    }
    
    .logo {
      font-size: 1.5rem;
      font-weight: 700;
      color: white;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .logo .material-icons-round {
      font-size: 1.8rem;
      color: var(--primary-light);
    }
    
    .user-info {
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    
    .user-avatar {
      width: 40px;
      height: 40px;
      background: linear-gradient(135deg, var(--accent), var(--primary));
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
      font-size: 1.1rem;
      color: white;
    }
    
    .user-details {
      text-align: right;
    }
    
    .user-details .name {
      font-weight: 600;
      font-size: 0.95rem;
    }
    
    .user-details .class {
      font-size: 0.8rem;
      color: var(--text-secondary);
    }
    
    .main-content {
      flex: 1;
      display: flex;
      flex-direction: column;
      padding: 5rem 2rem 2rem;
    }
    
    .welcome-section {
      margin-bottom: 2rem;
      text-align: center;
    }
    
    .welcome-section h1 {
      font-size: 2.2rem;
      font-weight: 800;
      margin-bottom: 0.7rem;
      background: linear-gradient(90deg, var(--primary-light), var(--accent));
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      line-height: 1.2;
    }
    
    .welcome-section p {
      font-size: 1rem;
      max-width: 500px;
      margin: 0 auto;
      color: var(--text-secondary);
    }
    
    .categories-title {
      font-size: 1.3rem;
      font-weight: 600;
      margin-bottom: 1.2rem;
      text-align: center;
      color: var(--text-primary);
    }
    
    .cards-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
      width: 100%;
    }
    
    .card {
      background: var(--card-bg);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border-radius: 16px;
      overflow: hidden;
      transition: all 0.3s ease;
      box-shadow: var(--shadow-md);
      display: flex;
      flex-direction: column;
      height: 100%;
      border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .card:hover {
      transform: translateY(-8px);
      box-shadow: var(--shadow-lg);
      border-color: rgba(255, 255, 255, 0.2);
    }
    
    .card-header {
      padding: 1.2rem 1rem;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    
    .card-icon {
      width: 50px;
      height: 50px;
      border-radius: 10px;
      background: rgba(59, 130, 246, 0.15);
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 0.8rem;
    }
    
    .card-icon .material-icons-round {
      font-size: 2rem;
      color: var(--primary-light);
    }
    
    .card:nth-child(2) .card-icon {
      background: rgba(139, 92, 246, 0.15);
    }
    
    .card:nth-child(2) .card-icon .material-icons-round {
      color: var(--accent);
    }
    
    .card:nth-child(3) .card-icon {
      background: rgba(16, 185, 129, 0.15);
    }
    
    .card:nth-child(3) .card-icon .material-icons-round {
      color: var(--success);
    }
    
    .card-title {
      font-size: 1.1rem;
      font-weight: 600;
      margin-bottom: 0.3rem;
      text-align: center;
    }
    
    .card-body {
      padding: 0 1rem 1rem;
      text-align: center;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
    }
    
    .card-text {
      color: var(--text-secondary);
      font-size: 0.85rem;
      margin-bottom: 1rem;
    }
    
    .card-footer {
      padding: 1rem;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      text-align: center;
      background: rgba(15, 23, 42, 0.3);
    }
    
    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.4rem;
      padding: 0.6rem 1.2rem;
      border-radius: 6px;
      font-weight: 500;
      font-size: 0.85rem;
      transition: all 0.3s ease;
      border: none;
      cursor: pointer;
      text-decoration: none;
    }
    
    .btn-primary {
      background: var(--primary);
      color: white;
      box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    
    .btn-primary:hover {
      background: var(--primary-dark);
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
    }
    
    .btn-accent {
      background: var(--accent);
      color: white;
      box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }
    
    .btn-accent:hover {
      background: #7c3aed;
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(139, 92, 246, 0.4);
    }
    
    .btn-success {
      background: var(--success);
      color: white;
      box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    
    .btn-success:hover {
      background: #059669;
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
    }
    
    .btn-outline {
      background: transparent;
      border: 2px solid var(--primary);
      color: var(--primary);
      box-shadow: none;
    }
    
    .btn-outline:hover {
      background: var(--primary);
      color: white;
    }
    
    .btn-icon {
      font-size: 1.2rem;
    }
    
    .footer {
      padding: 1.2rem;
      text-align: center;
      color: var(--text-secondary);
      font-size: 0.8rem;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      margin-top: auto;
    }
    
    .logout-btn {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      color: var(--text-secondary);
      text-decoration: none;
      font-weight: 500;
      font-size: 0.9rem;
      padding: 0.5rem 1rem;
      border-radius: 8px;
      transition: all 0.3s ease;
      margin-top: 0.5rem;
    }
    
    .logout-btn:hover {
      background: rgba(239, 68, 68, 0.15);
      color: var(--danger);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
      header {
        padding: 1rem;
      }
      
      .header-container {
        flex-direction: column;
        gap: 1rem;
      }
      
      .main-content {
        padding: 8rem 1rem 2rem;
      }
      
      .welcome-section h1 {
        font-size: 1.8rem;
      }
      
      .welcome-section p {
        font-size: 1rem;
      }
      
      .cards-container {
        grid-template-columns: 1fr;
        gap: 1.5rem;
      }
    }
    
    @media (min-width: 769px) and (max-width: 1024px) {
      .cards-container {
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      }
    }
  </style>
</head>
<body>
  <header>
    <div class="header-container">
      <div class="logo">
        <span class="material-icons-round">menu_book</span>
        <span>DigitalLib</span>
      </div>
      <div class="user-info">
        <div class="user-details">
          <div class="name"><?php echo htmlspecialchars($nama); ?></div>
          <div class="class"><?php echo $kelas; ?> - <?php echo htmlspecialchars($jurusan); ?></div>
        </div>
        <div class="user-avatar">
          <?php echo substr(htmlspecialchars($nama), 0, 1); ?>
        </div>
      </div>
    </div>
  </header>
  
  <div class="container">  
    <main class="main-content">
      <section class="welcome-section">
        <h1>Perpustakaan Digital</h1>
        <p>Koleksi buku terbaik untuk pembelajaran dan hiburan</p>
      </section>
      
      <h2 class="categories-title">Kategori Buku</h2>
      
      <div class="cards-container">
        <div class="card">
          <div class="card-header">
            <div class="card-icon">
              <span class="material-icons-round">school</span>
            </div>
            <h3 class="card-title">Buku Pelajaran</h3>
          </div>
          <div class="card-body">
            <p class="card-text">Pinjam buku pelajaran untuk mendukung studi Anda. Tersedia berbagai mata pelajaran untuk semua tingkatan.</p>
          </div>
          <div class="card-footer">
            <a href="peminjaman.php?kategori=mapel" class="btn btn-primary">
              <span class="material-icons-round btn-icon">book</span>
              Pinjam Sekarang
            </a>
          </div>
        </div>
        
        <div class="card">
          <div class="card-header">
            <div class="card-icon">
              <span class="material-icons-round">auto_stories</span>
            </div>
            <h3 class="card-title">Komik</h3>
          </div>
          <div class="card-body">
            <p class="card-text">Koleksi komik terbaru untuk waktu santai Anda. Dari komik lokal hingga manga Jepang populer.</p>
          </div>
          <div class="card-footer">
            <a href="peminjaman.php?kategori=komik" class="btn btn-accent">
              <span class="material-icons-round btn-icon">auto_stories</span>
              Pinjam Sekarang
            </a>
          </div>
        </div>
        
        <div class="card">
          <div class="card-header">
            <div class="card-icon">
              <span class="material-icons-round">article</span>
            </div>
            <h3 class="card-title">Novel</h3>
          </div>
          <div class="card-body">
            <p class="card-text">Berbagai genre novel untuk menemani hari-hari Anda. Dari fiksi, romance, hingga misteri.</p>
          </div>
          <div class="card-footer">
            <a href="peminjaman.php?kategori=novel" class="btn btn-success">
              <span class="material-icons-round btn-icon">article</span>
              Pinjam Sekarang
            </a>
          </div>
        </div>
      </div>
    </main>
    
    <footer class="footer">
      <p>© 2025 DigitalLib - Perpustakaan Digital Sekolah</p>
      <a href="logout.php" class="logout-btn">
        <span class="material-icons-round">logout</span>
        Logout
      </a>
    </footer>
  </div>
</body>
</html>