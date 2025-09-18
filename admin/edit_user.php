<?php
require '../config.php'; // เชื่อมต่อฐานข้อมูลด้วย PDO
require 'auth_admin.php'; // การ์ดสิทธิ์ (Admin Guard)

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: users.php");
    exit;
}

$user_id = (int) $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ? AND role = 'member'");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "<h3>ไม่พบสมาชิก</h3>";
    exit;
}

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($username === '' || $email === '') {
        $error = "กรุณากรอกข้อมูลให้ครบถ้วน";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "รูปแบบอีเมลไม่ถูกต้อง";
    } elseif ($password !== '' && $password !== $confirm_password) {
        $error = "รหัสผ่านใหม่และยืนยันรหัสผ่านไม่ตรงกัน";
    }

    if (!$error) {
        $chk = $conn->prepare("SELECT 1 FROM users WHERE (username = ? OR email = ?) AND user_id != ?");
        $chk->execute([$username, $email, $user_id]);
        if ($chk->fetch()) {
            $error = "ชื่อผู้ใช้หรืออีเมลนี้มีอยู่แล้วในระบบ";
        }
    }

    $updatePassword = false;
    $hashed = null;
    if (!$error && ($password !== '' || $confirm_password !== '')) {
        if (strlen($password) < 6) {
            $error = "รหัสผ่านต้องมีความยาวอย่างน้อย 6 ตัวอักษร";
        } elseif ($password !== $confirm_password) {
            $error = "รหัสผ่านใหม่กับยืนยันรหัสผ่านไม่ตรงกัน";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $updatePassword = true;
        }
    }

    if (!$error) {
        if ($updatePassword) {
            $sql = "UPDATE users
                    SET username = ?, full_name = ?, email = ?, password = ?
                    WHERE user_id = ?";
            $args = [$username, $full_name, $email, $hashed, $user_id];
        } else {
            $sql = "UPDATE users
                    SET username = ?, full_name = ?, email = ?
                    WHERE user_id = ?";
            $args = [$username, $full_name, $email, $user_id];
        }
        $upd = $conn->prepare($sql);
        $upd->execute($args);

        header("Location: users.php");
        exit;
    }

    $user['username'] = $username;
    $user['full_name'] = $full_name;
    $user['email'] = $email;
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>แก้ไขสมาชิก</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f9f5f0;
            font-family: "Prompt", sans-serif;
        }
        .page-title {
            color: #7c4d1e;
            font-weight: bold;
        }
        .card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .btn-primary {
            background-color: #c49a6c;
            border: none;
        }
        .btn-primary:hover {
            background-color: #b5895a;
        }
        .btn-secondary {
            background-color: #8d6e63;
            border: none;
        }
        .form-label {
            font-weight: 600;
            color: #5d4037;
        }
    </style>
</head>

<body class="container py-4">

    <h2 class="page-title mb-4">แก้ไขข้อมูลสมาชิก</h2>
    <a href="users.php" class="btn btn-secondary mb-3">← กลับหน้ารายชื่อสมาชิก</a>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card p-4">
        <form method="post" class="row g-3">
            <div class="col-md-6">
                <label class="form-label">ชื่อผู้ใช้</label>
                <input type="text" name="username" class="form-control" required
                    value="<?= htmlspecialchars($user['username']) ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">ชื่อ - นามสกุล</label>
                <input type="text" name="full_name" class="form-control"
                    value="<?= htmlspecialchars($user['full_name']) ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">อีเมล</label>
                <input type="email" name="email" class="form-control" required
                    value="<?= htmlspecialchars($user['email']) ?>">
            </div>

           

            <div class="col-12">
                <button type="submit" class="btn btn-primary w-100">บันทึกการแก้ไข</button>
            </div>
            
             <div class="col-md-6">
                <label class="form-label">รหัสผ่านใหม่ 
                    <small class="text-muted">(หากไม่ต้องการเปลี่ยน ให้เว้นว่าง)</small>
                </label>
                <input type="password" name="password" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">ยืนยันรหัสผ่านใหม่</label>
                <input type="password" name="confirm_password" class="form-control">
            </div>
        </form>
    </div>

</body>

</html>
