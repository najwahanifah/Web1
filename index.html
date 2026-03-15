<?php
session_start();

// Hardcoded users (no database)
$users = [
    'admin' => ['password' => 'admin123', 'role' => 'Admin', 'name' => 'Administrator'],
    'user1' => ['password' => 'user123',  'role' => 'User',  'name' => 'Budi Santoso'],
    'petugas' => ['password' => 'petugas123', 'role' => 'Petugas', 'name' => 'Siti Rahayu'],
];

$login_error = '';
$register_success = '';
$register_error = '';
$show_modal = '';

// In-session registered users
if (!isset($_SESSION['registered_users'])) {
    $_SESSION['registered_users'] = [];
}

// Handle Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role     = $_POST['role'] ?? '';

    $all_users = array_merge($users, $_SESSION['registered_users']);

    if (isset($all_users[$username]) && $all_users[$username]['password'] === $password) {
        if ($all_users[$username]['role'] === $role) {
            $_SESSION['logged_in'] = true;
            $_SESSION['username']  = $username;
            $_SESSION['role']      = $role;
            $_SESSION['name']      = $all_users[$username]['name'];
            header('Location: dashboard.php');
            exit;
        } else {
            $login_error = 'Role tidak sesuai dengan akun ini.';
            $show_modal = 'login';
        }
    } else {
        $login_error = 'Username atau password salah.';
        $show_modal = 'login';
    }
}

// Handle Register
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    $new_name     = trim($_POST['reg_name'] ?? '');
    $new_username = trim($_POST['reg_username'] ?? '');
    $new_password = $_POST['reg_password'] ?? '';
    $new_confirm  = $_POST['reg_confirm'] ?? '';
    $new_role     = $_POST['reg_role'] ?? 'User';

    $all_users = array_merge($users, $_SESSION['registered_users']);

    if (empty($new_name) || empty($new_username) || empty($new_password)) {
        $register_error = 'Semua field wajib diisi.';
        $show_modal = 'register';
    } elseif ($new_password !== $new_confirm) {
        $register_error = 'Password dan konfirmasi tidak cocok.';
        $show_modal = 'register';
    } elseif (isset($all_users[$new_username])) {
        $register_error = 'Username sudah digunakan.';
        $show_modal = 'register';
    } else {
        $_SESSION['registered_users'][$new_username] = [
            'password' => $new_password,
            'role'     => $new_role,
            'name'     => $new_name,
        ];
        $register_success = 'Registrasi berhasil! Silakan login.';
        $show_modal = 'login';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartTrashcan - Tempat Sampah Pintar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<nav>
  <!-- Baris Atas -->
  <div class="nav-logo">
    <div class="nav-logo-icon">
      ♻️
    </div>
    <span class="nav-logo-text">SmartTrashcan</span>
  </div>

  <!-- Baris Bawah -->
  <div class="nav-menu">
    <a href="#">Beranda</a>
    <a href="#">Lokasi</a>
    <a href="#">Edukasi</a>
    <button class="btn-login" onclick="openModal('login')">Login</button>
  </div>
</nav>

<!-- HERO -->
<section class="hero" id="beranda">
    <div class="hero-content">
        <div class="hero-text">
            <h1>Smart<span>Trashcan</span></h1>
            <p>
                Tempat sampah pintar (<em>smart trash can</em>) merevolusi manajemen sampah dengan
                mengintegrasikan teknologi canggih untuk mengidentifikasi dan memilah sampah organik
                dan anorganik secara otomatis langsung pada sumbernya. Dirancang untuk efisiensi dan
                keberlanjutan, perangkat ini menggunakan kombinasi sensor dan kecerdasan buatan (AI)
                untuk menyederhanakan proses daur ulang.
            </p>
        </div>
        <div class="hero-image">
            <!-- SVG Illustration -->
            <svg viewBox="0 0 340 340" xmlns="http://www.w3.org/2000/svg" class="trashcan-illustration" style="width:320px;height:320px">
                <!-- Glow rings -->
                <ellipse cx="170" cy="295" rx="110" ry="18" fill="none" stroke="#52B788" stroke-width="3" opacity="0.5"/>
                <ellipse cx="170" cy="295" rx="140" ry="24" fill="none" stroke="#74C69D" stroke-width="2" opacity="0.3"/>
                <!-- WiFi signal -->
                <path d="M210 80 Q230 65 250 80" fill="none" stroke="#52B788" stroke-width="3" stroke-linecap="round"/>
                <path d="M202 70 Q230 50 258 70" fill="none" stroke="#52B788" stroke-width="3" stroke-linecap="round"/>
                <path d="M194 60 Q230 35 266 60" fill="none" stroke="#74C69D" stroke-width="2.5" stroke-linecap="round"/>
                <circle cx="230" cy="87" r="4" fill="#52B788"/>
                <!-- Sparkles -->
                <text x="90"  y="155" font-size="22" fill="#95D5B2">✦</text>
                <text x="265" y="195" font-size="16" fill="#74C69D">✦</text>
                <text x="105" y="245" font-size="12" fill="#52B788">✦</text>
                <!-- Bin body -->
                <rect x="110" y="155" width="120" height="130" rx="12" fill="#3D3D3D"/>
                <rect x="115" y="160" width="110" height="120" rx="10" fill="#4A4A4A"/>
                <!-- Green glow inside -->
                <ellipse cx="170" cy="180" rx="40" ry="15" fill="#52B788" opacity="0.6"/>
                <ellipse cx="170" cy="182" rx="30" ry="10" fill="#74C69D" opacity="0.8"/>
                <!-- Lid -->
                <rect x="100" y="140" width="140" height="22" rx="8" fill="#2C2C2C"/>
                <rect x="152" y="132" width="36" height="14" rx="5" fill="#222"/>
                <!-- Screen on bin -->
                <rect x="128" y="200" width="84" height="48" rx="6" fill="#1a1a1a"/>
                <rect x="132" y="204" width="76" height="40" rx="4" fill="#0d2818"/>
                <text x="147" y="222" font-size="9" fill="#52B788" font-family="monospace">ORGANIC</text>
                <text x="145" y="237" font-size="9" fill="#74C69D" font-family="monospace">LEVEL: 42%</text>
            </svg>
        </div>
    </div>
</section>



<!-- ═══════════════════════════════ MODAL LOGIN ═══════════════════════════════ -->
<div class="modal-overlay <?= $show_modal === 'login' ? 'active' : '' ?>" id="modalLogin">
    <div class="modal">
        <button class="modal-close" onclick="closeModal('login')">✕</button>
        <h2>Login</h2>

        <?php if ($login_error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($login_error) ?></div>
        <?php endif; ?>
        <?php if ($register_success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($register_success) ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php">
            <input type="hidden" name="action" value="login">
            <div class="form-group">
                <select name="role" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="Admin"   <?= ($_POST['role'] ?? '') === 'Admin'   ? 'selected' : '' ?>>Admin</option>
                    <option value="User"    <?= ($_POST['role'] ?? '') === 'User'    ? 'selected' : '' ?>>User</option>
                    <option value="Petugas" <?= ($_POST['role'] ?? '') === 'Petugas' ? 'selected' : '' ?>>Petugas</option>
                </select>
            </div>
            <div class="form-group">
                <input type="text" name="username" placeholder="Masukkan Username"
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Masukkan Password" required>
            </div>
            <button type="submit" class="btn-submit btn-primary">Login</button>
        </form>
        <button class="btn-submit btn-secondary" onclick="switchModal('login','register')">Registrasi</button>
    </div>
</div>

<!-- ═══════════════════════════════ MODAL REGISTER ═══════════════════════════════ -->
<div class="modal-overlay <?= $show_modal === 'register' ? 'active' : '' ?>" id="modalRegister">
    <div class="modal">
        <button class="modal-close" onclick="closeModal('register')">✕</button>
        <h2>Registrasi</h2>

        <?php if ($register_error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($register_error) ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php">
            <input type="hidden" name="action" value="register">
            <div class="form-group">
                <input type="text" name="reg_name" placeholder="Nama Lengkap"
                       value="<?= htmlspecialchars($_POST['reg_name'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <input type="text" name="reg_username" placeholder="Username"
                       value="<?= htmlspecialchars($_POST['reg_username'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <select name="reg_role">
                    <option value="User">User</option>
                    <option value="Petugas">Petugas</option>
                </select>
            </div>
            <div class="form-group">
                <input type="password" name="reg_password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <input type="password" name="reg_confirm" placeholder="Konfirmasi Password" required>
            </div>
            <button type="submit" class="btn-submit btn-primary">Daftar Sekarang</button>
        </form>
        <button class="btn-submit btn-secondary" onclick="switchModal('register','login')">Sudah punya akun? Login</button>
    </div>
</div>

<script>
    function openModal(type) {
        document.getElementById('modal' + cap(type)).classList.add('active');
    }
    function closeModal(type) {
        document.getElementById('modal' + cap(type)).classList.remove('active');
    }
    function switchModal(from, to) {
        closeModal(from); openModal(to);
    }
    function cap(s) { return s.charAt(0).toUpperCase() + s.slice(1); }

    // Close on overlay click
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', e => {
            if (e.target === overlay) overlay.classList.remove('active');
        });
    });
</script>
</body>
</html>
