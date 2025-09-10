<?php
session_start();
require_once '../config.php';
require_once 'auth_admin.php';

// ตรวจสอบสิทธิ์ admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// ลบสมาชิก
if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];

    // ป้องกันลบตัวเอง
    if ($user_id != $_SESSION['user_id']) {
        $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ? AND role = 'member'");
        $stmt->execute([$user_id]);
    }
    header("Location: users.php");
    exit;
}

// ดึงข้อมูลสมาชิก
$stmt = $conn->prepare("SELECT * FROM users WHERE role = 'member' ORDER BY created_at DESC");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>จัดการสมาชิก</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(to bottom, #fff8e7, #fff);
            font-family: "Sarabun", sans-serif;
            color: #4b3b2f;
        }

        h2 {
            color: #b8860b;
            font-weight: bold;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .table {
            background-color: #fffaf0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(184, 134, 11, 0.15);
        }

        .table thead {
            background-color: #f7c948;
            color: #4b3b2f;
        }

        .btn-custom {
            border-radius: 8px;
            font-weight: bold;
            padding: 0.4rem 0.8rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .btn-warning {
            background-color: #ffd966;
            border: none;
            color: #4b3b2f;
        }

        .btn-danger {
            background-color: #e6b800;
            border: none;
            color: #fff;
        }

        .btn-secondary {
            background-color: #d6c8a2;
            border: none;
            color: #4b3b2f;
        }

        .alert-warning {
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            border-radius: 10px;
        }

        footer {
            margin-top: 2rem;
            text-align: center;
            font-size: 0.9rem;
            color: #7c6f57;
        }
    </style>
</head>

<body class="container mt-4">

    <h2>จัดการสมาชิก</h2>
    <a href="index.php" class="btn btn-secondary mb-3">← กลับหน้าผู้ดูแล</a>

    <?php if (count($users) === 0): ?>
        <div class="alert alert-warning text-center">ยังไม่มีสมาชิกในระบบ</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>ชื่อผู้ใช้</th>
                        <th>ชื่อ - นามสกุล</th>
                        <th>อีเมล</th>
                        <th>วันที่สมัคร</th>
                        <th class="text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td><?= htmlspecialchars($user['full_name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= $user['created_at'] ?></td>
                            <td class="text-center">
                                <a href="edit_user.php?id=<?= $user['user_id'] ?>" 
                                   class="btn btn-sm btn-custom btn-warning">แก้ไข</a>
                                <a href="users.php?delete=<?= $user['user_id'] ?>" 
                                   class="btn btn-sm btn-custom btn-danger"
                                   onclick="return confirm('คุณต้องการลบสมาชิกนี้หรือไม่?')">ลบ</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <footer>
        © <?= date("Y") ?> ระบบผู้ดูแล
    </footer>
</body>
</html>
