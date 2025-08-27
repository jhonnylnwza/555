<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameOrEmail = trim($_POST['username_or_email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$usernameOrEmail, $usernameOrEmail]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        if ($user['role'] === 'admin') {
            header("Location: admin/index.php");
        } else {
            header("Location: index2.php");
        }
        exit();
    } else {
        $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ | วันพระ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            background: #fdf8f0 url('https://cdn-icons-png.flaticon.com/512/3575/3575040.png') no-repeat right bottom;
            background-size: 150px;
            min-height: 100vh;
        }

        .login-container {
            background-color: #ffffff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 0 15px rgba(200, 170, 120, 0.3);
            max-width: 600px;
            margin: auto;
            margin-top: 80px;
        }

        .form-label {
            font-weight: 600;
        }

        .btn-success {
            background-color: #c9a368;
            border-color: #c9a368;
        }

        .btn-success:hover {
            background-color: #b28b4f;
            border-color: #b28b4f;
        }

        .theme-title {
            text-align: center;
            color: #9c7e4e;
            margin-bottom: 25px;
        }

        .lotus-icon {
            display: block;
            margin: 0 auto 10px auto;
            width: 60px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="login-container">

            <h2 class="theme-title">เข้าสู่ระบบ</h2>

            <?php if (isset($_GET['register']) && $_GET['register'] === 'คำ่ ทสี่ ง่ มำกับ register'): ?>
                <div class="alert alert-success">สมัครสมาชิกสำเร็จ กรุณาเข้าสู่ระบบ</div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="post" class="row g-3">
                <div class="col-12">
                    <label for="username_or_email" class="form-label">ชื่อผู้ใช้ หรือ อีเมล</label>
                    <input type="text" name="username_or_email" id="username_or_email" class="form-control" required>
                </div>
                <div class="col-12">
                    <label for="password" class="form-label">รหัสผ่าน</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <div class="col-12 d-flex justify-content-between align-items-center mt-3">
                    <button type="submit" class="btn btn-success">เข้าสู่ระบบ</button>
                    <a href="register.php" class="btn btn-link">สมัครสมาชิก</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
