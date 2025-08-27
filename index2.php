<?php
session_start();
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าหลัก - ธีมวันพระ</title>
    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Kanit', sans-serif;
            background: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)),
                url('https://cdn.pixabay.com/photo/2016/11/23/15/33/buddha-1853166_1280.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #5c4320;
        }

        h1 {
            text-align: center;
            padding-top: 50px;
            color: #a67c00;
        }

        .container {
            text-align: center;
            margin-top: 40px;
            padding: 30px;
            background-color: rgba(255, 255, 255, 0.85);
            width: 50%;
            margin-left: auto;
            margin-right: auto;
            border-radius: 12px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-secondary {
            background-color: #b08b56;
            color: white;
        }

        .btn-primary {
            background-color: #6ca6cd;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body>

    <h1>ยินดีต้อนรับสู่หน้าหลัก</h1>

    <div class="container">
        <?php if (isset($_SESSION['username']) && isset($_SESSION['role'])): ?>
            <p>ผู้ใช้: <?= htmlspecialchars($_SESSION['username']) ?> (<?= htmlspecialchars($_SESSION['role']) ?>)</p>
            <a href="logout.php" class="btn btn-secondary">ออกจากระบบ</a>
        <?php else: ?>
            <p>คุณยังไม่ได้เข้าสู่ระบบ</p>
            <a href="login.php" class="btn btn-primary">เข้าสู่ระบบ</a>
        <?php endif; ?>
    </div>

</body>

</html>
