<?php
require_once '../config.php';
require_once 'auth_admin.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// ลบสมาชิก (ปุ่มจะใช้ SweetAlert2)
if (isset($_POST['u_id'])) {
    $user_id = (int)$_POST['u_id'];
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f9f5f0;
            font-family: "Prompt", sans-serif;
            color: #4b3b2f;
        }
        h2 {
            color: #7c4d1e;
            font-weight: bold;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            padding: 20px;
            background-color: #fffaf0;
        }
        .table thead {
            background-color: #e6d3b3;
            color: #4b3b2f;
        }
        .table td, .table th {
            vertical-align: middle;
        }
        .btn-custom {
            border-radius: 8px;
            font-weight: 600;
            padding: 0.4rem 0.8rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }
        .btn-warning {
            background-color: #f5c16c;
            border: none;
            color: #4b3b2f;
        }
        .btn-danger {
            background-color: #c94f4f;
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
<body class="container py-4">

    <h2>จัดการสมาชิก</h2>
    <a href="index.php" class="btn btn-secondary mb-3">← กลับหน้าผู้ดูแล</a>

    <?php if (count($users) === 0): ?>
        <div class="alert alert-warning text-center">ยังไม่มีสมาชิกในระบบ</div>
    <?php else: ?>
        <div class="card">
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead>
                        <tr>
                            <th>ชื่อผู้ใช้</th>
                            <th>ชื่อ - นามสกุล</th>
                            <th>อีเมล</th>
                            <th>วันที่สมัคร</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td><?= htmlspecialchars($user['full_name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= $user['created_at'] ?></td>
                            <td>
                                <a href="edit_user.php?id=<?= $user['user_id'] ?>" class="btn btn-sm btn-warning btn-custom">แก้ไข</a>
                                <?php if ($user['user_id'] != $_SESSION['user_id']): ?>
                                <button type="button" class="btn btn-sm btn-danger btn-custom delete-button" data-user-id="<?= $user['user_id'] ?>">ลบ</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <footer>
        © <?= date("Y") ?> ระบบผู้ดูแล
    </footer>

</body>

<script>
    const deleteButtons = document.querySelectorAll('.delete-button');

    deleteButtons.forEach(button => {
        button.addEventListener('click', () => {
            const userId = button.getAttribute('data-user-id');

            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: 'คุณจะไม่สามารถกู้คืนข้อมูลนี้ได้!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ลบ',
                cancelButtonText: 'ยกเลิก',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // สร้าง form ส่ง POST
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'users.php';

                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'u_id';
                    input.value = userId;

                    form.appendChild(input);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
</script>

</html>
