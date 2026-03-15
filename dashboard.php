<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: index.php');
    exit;
}

// Initialize data in session (simulating database)
if (!isset($_SESSION['trashcans'])) {
    $_SESSION['trashcans'] = [
        ['id' => 1, 'nama' => 'TPS Fakultas Ilmu Terapan',       'lokasi' => 'lantai 1, dekat lift',       'kapasitas' => 100, 'terisi' => 78,  'status' => 'Aktif',    'jenis' => 'Organik & Anorganik', 'tanggal' => '2026-03-10'],
        ['id' => 2, 'nama' => 'TPS Fakultas Ilmu Terapan',      'lokasi' => 'lantai 2, dekat lift',     'kapasitas' => 80,  'terisi' => 45,  'status' => 'Aktif',    'jenis' => 'Organik',             'tanggal' => '2026-03-11'],
        ['id' => 3, 'nama' => 'GKU',      'lokasi' => 'lantai 7, dekat tangga',     'kapasitas' => 150, 'terisi' => 130, 'status' => 'Penuh',    'jenis' => 'Anorganik',           'tanggal' => '2026-03-12'],
    ];
    $_SESSION['next_id'] = 6;
}

$message = '';
$msg_type = '';

// Handle CREATE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create') {
    $nama     = trim($_POST['nama'] ?? '');
    $lokasi   = trim($_POST['lokasi'] ?? '');
    $kapasitas = (int)($_POST['kapasitas'] ?? 0);
    $jenis    = $_POST['jenis'] ?? 'Organik';

    if ($nama && $lokasi && $kapasitas > 0) {
        $_SESSION['trashcans'][] = [
            'id'        => $_SESSION['next_id']++,
            'nama'      => $nama,
            'lokasi'    => $lokasi,
            'kapasitas' => $kapasitas,
            'terisi'    => 0,
            'status'    => 'Aktif',
            'jenis'     => $jenis,
            'tanggal'   => date('Y-m-d'),
        ];
        $message = "Tempat sampah \"$nama\" berhasil ditambahkan!";
        $msg_type = 'success';
    } else {
        $message = 'Semua field wajib diisi dengan benar.';
        $msg_type = 'error';
    }
}

// Handle DELETE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $del_id = (int)($_POST['del_id'] ?? 0);
    $_SESSION['trashcans'] = array_values(
        array_filter($_SESSION['trashcans'], fn($t) => $t['id'] !== $del_id)
    );
    $message = 'Data tempat sampah berhasil dihapus.';
    $msg_type = 'success';
}

$trashcans = $_SESSION['trashcans'];

// Stats
$total    = count($trashcans);
$aktif    = count(array_filter($trashcans, fn($t) => $t['status'] === 'Aktif'));
$penuh    = count(array_filter($trashcans, fn($t) => $t['status'] === 'Penuh'));
$avg_fill = $total ? round(array_sum(array_map(fn($t) => $t['terisi'] / $t['kapasitas'] * 100, $trashcans)) / $total) : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — SmartTrashcan</title>
    <link rel="stylesheet">
    <style>
        :root {
            --green-primary: #2D6A4F;
            --green-light:   #52B788;
            --green-accent:  #74C69D;
            --green-bright:  #95D5B2;
            --green-soft:    #D8F3DC;
            --dark:          #1a1a1a;
            --dark-mid:      #2C2C2C;
            --sidebar-bg:    #1E3A2F;
            --white:         #FFFFFF;
            --gray-100:      #F5F7F5;
            --gray-200:      #E8EDE8;
            --gray-400:      #9CA89C;
            --text-muted:    #6B7B6B;
            --shadow:        0 4px 20px rgba(0,0,0,0.08);
            --shadow-green:  0 6px 24px rgba(82,183,136,0.3);
            --red:           #E53935;
            --orange:        #FB8C00;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--gray-100);
            color: var(--dark);
            display: flex; min-height: 100vh;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width: 256px; background: var(--sidebar-bg);
            display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; bottom: 0; z-index: 50;
        }

        .sidebar-logo {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-logo h1 {
            font-family: 'Syne', sans-serif;
            font-size: 1.3rem; color: var(--white); font-weight: 800;
        }
        .sidebar-logo p { font-size: 0.75rem; color: var(--green-bright); margin-top: 2px; }

        .sidebar-user {
            padding: 16px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex; align-items: center; gap: 12px;
        }
        .avatar {
            width: 40px; height: 40px; border-radius: 50%;
            background: linear-gradient(135deg, var(--green-light), var(--green-primary));
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 700; font-size: 1rem;
            flex-shrink: 0;
        }
        .sidebar-user-info .name { color: white; font-weight: 600; font-size: 0.9rem; }
        .sidebar-user-info .role {
            color: var(--green-bright); font-size: 0.75rem;
            background: rgba(82,183,136,0.2); padding: 2px 8px; border-radius: 20px;
            display: inline-block; margin-top: 2px;
        }

        .sidebar-nav { padding: 16px 12px; flex: 1; }
        .nav-item {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 14px; border-radius: 10px;
            color: rgba(255,255,255,0.7); font-size: 0.9rem; font-weight: 500;
            cursor: pointer; transition: all .2s; text-decoration: none;
            margin-bottom: 4px;
        }
        .nav-item:hover { background: rgba(255,255,255,0.07); color: white; }
        .nav-item.active { background: var(--green-light); color: white; }
        .nav-icon { font-size: 1.1rem; }

        .sidebar-bottom {
            padding: 16px 12px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        .btn-logout {
            display: flex; align-items: center; gap: 10px;
            width: 100%; padding: 11px 14px; border-radius: 10px;
            background: rgba(229,57,53,0.15); border: 1px solid rgba(229,57,53,0.3);
            color: #FF7070; font-size: 0.9rem; font-weight: 600;
            cursor: pointer; transition: all .2s;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .btn-logout:hover { background: rgba(229,57,53,0.25); color: #FF5252; }

        /* ── MAIN ── */
        .main { margin-left: 256px; flex: 1; display: flex; flex-direction: column; }

        .topbar {
            background: white; padding: 16px 32px;
            border-bottom: 1px solid var(--gray-200);
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 40;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .topbar h2 { font-size: 1.2rem; font-weight: 700; color: var(--dark); }
        .topbar-right { display: flex; align-items: center; gap: 16px; }
        .topbar-date { font-size: 0.85rem; color: var(--text-muted); }
        .badge-role {
            background: var(--green-soft); color: var(--green-primary);
            padding: 4px 12px; border-radius: 20px;
            font-size: 0.8rem; font-weight: 700;
        }

        .content { padding: 32px; flex: 1; }

        /* ── ALERT ── */
        .alert {
            padding: 14px 18px; border-radius: 10px;
            font-size: 0.9rem; font-weight: 600; margin-bottom: 24px;
            display: flex; align-items: center; gap: 10px;
        }
        .alert-success { background: #E8F5E9; color: #2E7D32; border: 1px solid #A5D6A7; }
        .alert-error   { background: #FDECEA; color: #C62828; border: 1px solid #FFCDD2; }

        /* ── STATS ── */
        .stats-grid {
            display: grid; grid-template-columns: repeat(4,1fr); gap: 20px;
            margin-bottom: 32px;
        }
        .stat-card {
            background: white; border-radius: 14px; padding: 22px 24px;
            box-shadow: var(--shadow); transition: transform .2s;
        }
        .stat-card:hover { transform: translateY(-2px); }
        .stat-label { font-size: 0.8rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: .05em; }
        .stat-value { font-family: 'Syne', sans-serif; font-size: 2.4rem; font-weight: 800; margin: 4px 0; }
        .stat-sub   { font-size: 0.8rem; color: var(--text-muted); }
        .stat-icon  { font-size: 1.6rem; margin-bottom: 8px; }

        .stat-green  .stat-value { color: var(--green-primary); }
        .stat-teal   .stat-value { color: #00897B; }
        .stat-orange .stat-value { color: var(--orange); }
        .stat-red    .stat-value { color: var(--red); }

        /* ── PANELS ── */
        .panels { display: grid; grid-template-columns: 1fr 380px; gap: 24px; }

        .panel {
            background: white; border-radius: 16px; overflow: hidden;
            box-shadow: var(--shadow);
        }
        .panel-header {
            padding: 20px 24px; border-bottom: 1px solid var(--gray-200);
            display: flex; align-items: center; justify-content: space-between;
        }
        .panel-title { font-size: 1.05rem; font-weight: 700; }

        /* ── TABLE ── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            padding: 12px 16px; text-align: left;
            font-size: 0.78rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: .04em; color: var(--text-muted);
            background: var(--gray-100); border-bottom: 1px solid var(--gray-200);
        }
        tbody td {
            padding: 14px 16px; font-size: 0.875rem;
            border-bottom: 1px solid var(--gray-200);
            vertical-align: middle;
        }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: #F9FBF9; }

        .td-name { font-weight: 600; color: var(--dark); }
        .td-loc  { color: var(--text-muted); font-size: 0.8rem; }

        /* Fill bar */
        .fill-bar { width: 90px; }
        .fill-track {
            height: 6px; background: var(--gray-200); border-radius: 3px; overflow: hidden;
        }
        .fill-fill { height: 100%; border-radius: 3px; transition: width .6s; }
        .fill-pct  { font-size: 0.75rem; color: var(--text-muted); margin-top: 3px; }

        .fill-low    { background: var(--green-light); }
        .fill-mid    { background: var(--orange); }
        .fill-high   { background: var(--red); }

        /* Status badge */
        .badge {
            padding: 4px 10px; border-radius: 20px;
            font-size: 0.75rem; font-weight: 700; white-space: nowrap;
        }
        .badge-aktif  { background: var(--green-soft); color: var(--green-primary); }
        .badge-penuh  { background: #FDECEA; color: var(--red); }
        .badge-hampir { background: #FFF3E0; color: var(--orange); }

        /* Action buttons */
        .btn-del {
            background: none; border: 1.5px solid #FFCDD2;
            color: var(--red); padding: 6px 14px; border-radius: 8px;
            font-size: 0.8rem; font-weight: 600; cursor: pointer;
            transition: all .2s; font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .btn-del:hover { background: #FDECEA; }

        /* ── ADD FORM PANEL ── */
        .form-panel { padding: 24px; }

        .form-group { margin-bottom: 16px; }
        .form-group label {
            display: block; font-size: 0.82rem; font-weight: 700;
            color: var(--text-muted); text-transform: uppercase;
            letter-spacing: .04em; margin-bottom: 6px;
        }
        .form-group input,
        .form-group select {
            width: 100%; padding: 11px 14px;
            border: 1.5px solid var(--gray-200); border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.9rem; color: var(--dark);
            background: var(--gray-100); outline: none;
            transition: border-color .2s, box-shadow .2s;
        }
        .form-group input:focus,
        .form-group select:focus {
            border-color: var(--green-light);
            box-shadow: 0 0 0 3px rgba(82,183,136,0.15);
            background: white;
        }

        .btn-add {
            width: 100%; padding: 13px;
            background: linear-gradient(135deg, var(--green-primary), var(--green-light));
            color: white; border: none; border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.95rem; font-weight: 700; cursor: pointer;
            transition: transform .15s, box-shadow .15s; margin-top: 4px;
        }
        .btn-add:hover { transform: translateY(-2px); box-shadow: var(--shadow-green); }

        .form-note {
            font-size: 0.8rem; color: var(--text-muted); text-align: center;
            margin-top: 12px; padding-top: 12px;
            border-top: 1px solid var(--gray-200);
        }

        .count-badge {
            background: var(--green-soft); color: var(--green-primary);
            padding: 3px 10px; border-radius: 20px;
            font-size: 0.8rem; font-weight: 700;
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <h1>Trashcan</h1>
        <p>Sistem Pengelolaan Sampah Otomatis</p>
    </div>

    <div class="sidebar-user">
        <div class="avatar"><?= strtoupper(substr($_SESSION['name'], 0, 1)) ?></div>
        <div class="sidebar-user-info">
            <div class="name"><?= htmlspecialchars($_SESSION['name']) ?></div>
            <span class="role"><?= htmlspecialchars($_SESSION['role']) ?></span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <a class="nav-item active" href="dashboard.php"><span class="nav-icon"></span> Dashboard</a>
        <a class="nav-item" href="#"><span class="nav-icon"></span> Peta Lokasi</a>
        <a class="nav-item" href="#"><span class="nav-icon"></span> Laporan</a>
        <a class="nav-item" href="#"><span class="nav-icon"></span> Notifikasi</a>
        <a class="nav-item" href="#"><span class="nav-icon"></span> Pengaturan</a>
    </nav>

    <div class="sidebar-bottom">
        <form method="POST" action="logout.php">
            <button type="submit" class="btn-logout">Keluar</button>
        </form>
    </div>
</aside>

<!-- MAIN -->
<div class="main">
    <div class="topbar">
        <h2>Dashboard Pengelolaan Sampah</h2>
        <div class="topbar-right">
            <span class="topbar-date"><?= date('d F Y') ?></span>
            <span class="badge-role"><?= htmlspecialchars($_SESSION['role']) ?></span>
        </div>
    </div>

    <div class="content">

        <?php if ($message): ?>
        <div class="alert alert-<?= $msg_type ?>">
            <?= $msg_type === 'success' ? '' : '' ?>
            <?= htmlspecialchars($message) ?>
        </div>
        <?php endif; ?>

        <!-- STATS -->
        <div class="stats-grid">
            <div class="stat-card stat-green">
                <div class="stat-icon"></div>
                <div class="stat-label">Total Unit</div>
                <div class="stat-value"><?= $total ?></div>
                <div class="stat-sub">Tempat sampah terdaftar</div>
            </div>
            <div class="stat-card stat-teal">
                <div class="stat-icon"></div>
                <div class="stat-label">Aktif</div>
                <div class="stat-value"><?= $aktif ?></div>
                <div class="stat-sub">Unit beroperasi normal</div>
            </div>
            <div class="stat-card stat-orange">
                <div class="stat-icon"></div>
                <div class="stat-label">Hampir Penuh</div>
                <div class="stat-value"><?= count(array_filter($trashcans, fn($t) => $t['status'] === 'Hampir Penuh')) ?></div>
                <div class="stat-sub">Perlu perhatian</div>
            </div>
            <div class="stat-card stat-red">
                <div class="stat-icon"></div>
                <div class="stat-label">Penuh</div>
                <div class="stat-value"><?= $penuh ?></div>
                <div class="stat-sub">Segera dikosongkan</div>
            </div>
        </div>

        <!-- PANELS -->
        <div class="panels">

            <!-- TABLE -->
            <div class="panel">
                <div class="panel-header">
                    <span class="panel-title">Data Tempat Sampah</span>
                    <span class="count-badge"><?= $total ?> unit</span>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama & Lokasi</th>
                                <th>Jenis</th>
                                <th>Pengisian</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($trashcans)): ?>
                            <tr>
                                <td colspan="7" style="text-align:center;color:var(--text-muted);padding:32px">
                                    Belum ada data. Tambahkan tempat sampah baru.
                                </td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($trashcans as $i => $t):
                                $pct = $t['kapasitas'] > 0 ? round($t['terisi'] / $t['kapasitas'] * 100) : 0;
                                $fill_class = $pct < 50 ? 'fill-low' : ($pct < 85 ? 'fill-mid' : 'fill-high');
                                $badge_class = match($t['status']) {
                                    'Penuh'       => 'badge-penuh',
                                    'Hampir Penuh'=> 'badge-hampir',
                                    default       => 'badge-aktif'
                                };
                            ?>
                            <tr>
                                <td style="color:var(--text-muted);font-size:.8rem"><?= $i+1 ?></td>
                                <td>
                                    <div class="td-name"><?= htmlspecialchars($t['nama']) ?></div>
                                    <div class="td-loc"><?= htmlspecialchars($t['lokasi']) ?></div>
                                </td>
                                <td style="font-size:.82rem"><?= htmlspecialchars($t['jenis']) ?></td>
                                <td>
                                    <div class="fill-bar">
                                        <div class="fill-track">
                                            <div class="fill-fill <?= $fill_class ?>" style="width:<?= $pct ?>%"></div>
                                        </div>
                                        <div class="fill-pct"><?= $t['terisi'] ?>/<?= $t['kapasitas'] ?>L (<?= $pct ?>%)</div>
                                    </div>
                                </td>
                                <td><span class="badge <?= $badge_class ?>"><?= htmlspecialchars($t['status']) ?></span></td>
                                <td style="font-size:.8rem;color:var(--text-muted)"><?= htmlspecialchars($t['tanggal']) ?></td>
                                <td>
                                    <?php if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Petugas'): ?>
                                    <form method="POST" onsubmit="return confirm('Hapus data ini?')">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="del_id" value="<?= $t['id'] ?>">
                                        <button type="submit" class="btn-del">Hapus</button>
                                    </form>
                                    <?php else: ?>
                                    <span style="color:var(--text-muted);font-size:.8rem">—</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ADD FORM -->
            <div class="panel">
                <div class="panel-header">
                    <span class="panel-title"> Tambah Unit Baru</span>
                </div>
                <div class="form-panel">
                    <?php if ($_SESSION['role'] === 'User'): ?>
                    <div style="text-align:center;padding:32px;color:var(--text-muted)">
                        <div style="font-size:2.5rem;margin-bottom:12px"></div>
                        <div style="font-weight:600">Akses Terbatas</div>
                        <div style="font-size:.85rem;margin-top:6px">Hanya Admin & Petugas yang dapat menambah data.</div>
                    </div>
                    <?php else: ?>
                    <form method="POST">
                        <input type="hidden" name="action" value="create">
                        <div class="form-group">
                            <label>Nama Tempat Sampah</label>
                            <input type="text" name="nama" placeholder="cth. Gedung Kuliah Umum" required>
                        </div>
                        <div class="form-group">
                            <label>Alamat / Lokasi</label>
                            <input type="text" name="lokasi" placeholder="cth. berada di dekat tangga" required>
                        </div>
                        <div class="form-group">
                            <label>Jenis Sampah</label>
                            <select name="jenis">
                                <option>Organik</option>
                                <option>Anorganik</option>
                                <option>Organik & Anorganik</option>
                                <option>B3</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Kapasitas (Liter)</label>
                            <input type="number" name="kapasitas" placeholder="cth. 100" min="1" required>
                        </div>
                        <button type="submit" class="btn-add"> Tambah Tempat Sampah</button>
                    </form>
                    <div class="form-note">Data tersimpan sementara (sesi aktif)</div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div><!-- /content -->
</div><!-- /main -->

</body>
</html>
