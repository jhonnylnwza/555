<?php
require_once 'config.php';

$error = [];  // Array to hold error messages
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าจาก form
    $username = trim($_POST['username']);
    $fname = trim($_POST['fname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // ตรวจสอบว่ากรอกข้อมูลมาครบหรือไม่ (empty)
    if (empty($username) || empty($fname) || empty($email) || empty($password) || empty($confirm_password)) {
        $error[] = "กรุณากรอกข้อมูลให้ครบทุกช่อง";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // ตรวจสอบรูปแบบอีเมล
        $error[] = "อีเมลไม่ถูกต้อง";
    } elseif ($password !== $confirm_password) {
        // ตรวจสอบรหัสผ่านและยืนยันรหัสผ่านตรงกันไหม
        $error[] = "รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน";
    } else {
        // ตรวจสอบว่ามีชื่อผู้ใช้หรืออีเมลถูกใช้ไปแล้วหรือไม่
        $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username, $email]);

        if ($stmt->rowCount() > 0) {
            $error[] = "ชื่อผู้ใช้หรืออีเมลนี้ถูกใช้ไปแล้ว";
        }
    }

    if (empty($error)) { // ถ้าไม่มีข้อผิดพลาด
        // นำข้อมูลบันทึกลงฐานข้อมูล
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users(username, full_name, email, password, role) VALUES (?,?,?,?, 'member')";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username, $fname, $email, $hashedPassword]);
        // ถ้าบันทึกสำเร็จ ให้เปลี่ยนเส้นทางไปหน้า login
        header("Location: login.php?register=success");
        exit(); // หยุดการทำงานของสคริปต์หลังจากเปลี่ยนเส้นทาง
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Register</title>
</head>
<style>
    body {
        background: linear-gradient(to bottom, #fffdf5, #f5eee1);
        font-family: 'Sarabun', sans-serif;
        color: #3e3e3e;
    }

    .container {
        background-color: #ffffffcc;
        border-radius: 10px;
        padding: 30px;
        max-width: 600px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        margin-bottom: 25px;
        color: #6b4f28;
    }

    label {
        color: #5e4632;
        font-weight: 600;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #c8b49a;
    }

    .btn-primary {
        background-color: #9e7c3c;
        border-color: #9e7c3c;
    }

    .btn-primary:hover {
        background-color: #cba74a;
        border-color: #cba74a;
    }

    a.btn-link {
        color: #6b4f28;
    }

    a.btn-link:hover {
        text-decoration: underline;
    }
</style>

<body>

    <div class="container mt-5">
        <h2> Register </h2>
        <?php if (!empty($error)): // ถ้ามีข้อผิดพลาดให้แสดงข้อควา ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($error as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <form action="" method="post">
            <div>
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" class="form-control" id="username" placeholder="ชื่่อผู้ใช้"
                    value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" required>
            </div>
            <div>
                <label for="fname" class="form-label">Full Name</label>
                <input type="text" name="fname" class="form-control" id="fname" placeholder="ชื่อ - นามสกุล"
                    value="<?= isset($_POST['fname']) ? htmlspecialchars($_POST['fname']) : '' ?>" required>
            </div>
            <div>
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" id="email" placeholder="อีเมล"
                    value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
            </div>
            <div>
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="รหัสผ่าน"
                    required>
            </div>
            <div>
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" id="confirm_password"
                    placeholder="ยืนยันรหัสผ่าน" required>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Register</button>
                <a href="Login.php" class="btn btn-link">Login</a>
            </div>
        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>