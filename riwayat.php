<?php
session_start();
if (!isset($_SESSION["nama"])) {
  header("Location: login.php");
  exit();
}
require_once "koneksi.php";

$nama = $_SESSION["nama"];
$query = "SELECT r.*, b.judul, b.gambar, b.kategori 
          FROM riwayat r 
          JOIN buku b ON r.id_buku = b.id 
          WHERE r.nama = ? 
          ORDER BY r.tanggal_pinjam DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $nama);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Riwayat Peminjaman | Perpustakaan Digital</title>
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
      --success: #4cc9f0;
      --warning: #f8961e;
      --danger: #f72585;
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
      background:   linear-gradient(135deg, rgba(26, 26, 46, 0.9) 0%, rgba(22, 33, 62, 0.9) 100%),
        url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f') center/cover fixed;
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
    
    .page-header {
      text-align: center;
      margin: 2rem 0 3rem;
    }
    
    .page-header h1 {
      font-size: 2.2rem;
      margin-bottom: 0.5rem;
      color: var(--light);
    }

    p {
      color: var(--light)
    }
    
    .history-container {
      background: var(--card-bg);
      border-radius: 16px;
      padding: 2rem;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }
    
    .history-list {
      display: grid;
      gap: 1.5rem;
    }
    
    .history-card {
      display: flex;
      gap: 1.5rem;
      padding: 1.5rem;
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      transition: all 0.3s ease;
    }
    
    .history-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }
    
    .book-cover {
      flex: 0 0 120px;
      height: 160px;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .book-cover img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;
    }
    
    .history-card:hover .book-cover img {
      transform: scale(1.05);
    }
    
    .book-info {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    
    .book-title {
      font-size: 1.2rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
      color: var(--dark);
    }
    
    .book-meta {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      margin-bottom: 0.5rem;
      font-size: 0.9rem;
      color: var(--gray);
    }
    
    .meta-item {
      display: flex;
      align-items: center;
      gap: 0.3rem;
    }
    
    .status-container {
      margin-top: 0.5rem;
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      align-items: center;
    }
    
    .status {
      display: inline-block;
      padding: 0.3rem 0.8rem;
      border-radius: 50px;
      font-size: 0.8rem;
      font-weight: 500;
    }
    
    .status-dipinjam {
      background-color: rgba(75, 192, 192, 0.1);
      color: #4bc0c0;
    }
    
    .status-dikembalikan {
      background-color: rgba(54, 162, 235, 0.1);
      color: #36a2eb;
    }
    
    .status-terlambat {
      background-color: rgba(255, 99, 132, 0.1);
      color: #ff6384;
    }
    
    .action-buttons {
      display: flex;
      gap: 1rem;
      margin-top: 1rem;
    }
    
    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      padding: 0.6rem 1.2rem;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s ease;
      font-size: 0.9rem;
    }
    
    .btn-primary {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      color: white;
      box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
    }
    
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba(67, 97, 238, 0.4);
    }
    
    .btn-outline {
      border: 1px solid var(--primary);
      color: var(--primary);
      background: transparent;
    }
    
    .btn-outline:hover {
      background: rgba(67, 97, 238, 0.1);
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
    
    .empty-state {
      text-align: center;
      padding: 3rem;
      color: var(--gray);
    }
    
    .empty-state .material-icons-round {
      font-size: 3rem;
      margin-bottom: 1rem;
      color: var(--gray);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
      .container {
        padding: 1.5rem;
      }
      
      .history-card {
        flex-direction: column;
      }
      
      .book-cover {
        width: 100%;
        height: 200px;
      }
      
      .action-buttons {
        flex-direction: column;
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
    
    <div class="page-header">
      <h1>Riwayat Peminjaman</h1>
      <p>Daftar buku yang pernah Anda pinjam</p>
    </div>
    
    <div class="history-container">
      <?php if ($result->num_rows > 0): ?>
        <div class="history-list">
          <?php while ($row = $result->fetch_assoc()): 
            // Determine status
            $statusClass = 'status-dikembalikan';
            $statusText = 'Sudah Dikembalikan';
            
            if ($row['status'] == 'Dipinjam') {
              $today = new DateTime();
              $returnDate = new DateTime($row['tanggal_kembali']);
              
              if ($today > $returnDate) {
                $statusClass = 'status-terlambat';
                $statusText = 'Terlambat Dikembalikan';
              } else {
                $statusClass = 'status-dipinjam';
                $statusText = 'Sedang Dipinjam';
              }
            }
          ?>
            <div class="history-card">
              <div class="book-cover">
                <img src="img/buku/<?php echo htmlspecialchars($row['gambar']); ?>" alt="<?php echo htmlspecialchars($row['judul']); ?>">
              </div>
              <div class="book-info">
                <h3 class="book-title"><?php echo htmlspecialchars($row['judul']); ?></h3>
                <div class="book-meta">
                  <span class="meta-item">
                    <span class="material-icons-round" style="font-size: 1rem;">category</span>
                    <?php echo ucfirst($row['kategori']); ?>
                  </span>
                  <span class="meta-item">
                    <span class="material-icons-round" style="font-size: 1rem;">event</span>
                    <?php echo date('d M Y', strtotime($row['tanggal_pinjam'])); ?>
                  </span>
                  <?php if ($row['status'] == 'Dipinjam'): ?>
                    <span class="meta-item">
                      <span class="material-icons-round" style="font-size: 1rem;">event_available</span>
                      <?php echo date('d M Y', strtotime($row['tanggal_kembali'])); ?>
                    </span>
                  <?php elseif ($row['tanggal_kembali']): ?>
                    <span class="meta-item">
                      <span class="material-icons-round" style="font-size: 1rem;">event_available</span>
                      <?php echo date('d M Y', strtotime($row['tanggal_kembali'])); ?>
                    </span>
                  <?php endif; ?>
                </div>
                
                <div class="status-container">
                  <span class="status <?php echo $statusClass; ?>">
                    <?php echo $statusText; ?>
                  </span>
                  
                  <?php if ($row['status'] == 'Dipinjam'): ?>
                    <span class="meta-item">
                      <span class="material-icons-round" style="font-size: 1rem;">access_time</span>
                      <?php 
                        $today = new DateTime();
                        $returnDate = new DateTime($row['tanggal_kembali']);
                        $interval = $today->diff($returnDate);
                        echo $interval->format('%a hari lagi');
                      ?>
                    </span>
                  <?php endif; ?>
                </div>
                
                <div class="action-buttons">
                  <a href="detail.php?id=<?php echo $row['id_buku']; ?>" class="btn btn-outline">
                    <span class="material-icons-round">visibility</span>
                    Lihat Detail
                  </a>
                  <?php if ($row['status'] == 'Dipinjam'): ?>
                    <a href="perpanjang.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">
                      <span class="material-icons-round">update</span>
                      Perpanjang
                    </a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
      <?php else: ?>
        <div class="empty-state">
          <span class="material-icons-round">history</span>
          <h3>Belum ada riwayat peminjaman</h3>
          <p>Anda belum pernah meminjam buku dari perpustakaan kami</p>
          <a href="peminjaman.php" class="btn btn-primary" style="margin-top: 1rem;">
            <span class="material-icons-round">menu_book</span>
            Pinjam Buku Sekarang
          </a>
        </div>
      <?php endif; ?>
      
      <a href="index.php" class="back-link">
        <span class="material-icons-round">home</span>
        Kembali ke Beranda
      </a>
    </div>
  </div>
</body>
</html>