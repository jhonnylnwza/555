<?php
require_once '../config.php';
require_once 'auth_admin.php';
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>แผงควบคุมผู้ดูแลระบบ</title>
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
            margin-bottom: 1rem;
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(184, 134, 11, 0.2);
            background-color: #fffaf0;
        }

        .btn-custom {
            border-radius: 12px;
            font-weight: bold;
            padding: 1rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
        }

        .btn-products {
            background-color: #f7c948;
            /* เหลือง */
            color: #4b3b2f;
        }

        .btn-orders {
            background-color: #f4a261;
            /* ส้มอ่อน */
            color: #fff;
        }

        .btn-users {
            background-color: #9ec1a3;
            /* เขียวอ่อน */
            color: #fff;
        }

        .btn-categories {
            background-color: #6c5ce7;
            /* ม่วง */
            color: #fff;
        }

        .logout-btn {
            background-color: #d6c8a2;
            color: #4b3b2f;
        }

        footer {
            margin-top: 3rem;
            text-align: center;
            font-size: 0.9rem;
            color: #7c6f57;
        }
    </style>
</head>

<body class="container mt-4">

    <h2>ระบบผู้ดูแลระบบ</h2>

    <p class="text-center mb-4">
        ยินดีต้อนรับ, <?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'ผู้ดูแลระบบ' ?>
    </p>

    <div class="row g-3">
        <div class="col-md-3 col-sm-6">
            <div class="card p-3">
                <a href="products.php" class="btn btn-custom btn-products w-100">จัดการสินค้า</a>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card p-3">
                <a href="orders.php" class="btn btn-custom btn-orders w-100">จัดการคำสั่งซื้อ</a>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card p-3">
                <a href="users.php" class="btn btn-custom btn-users w-100">จัดการสมาชิก</a>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card p-3">
                <a href="category.php" class="btn btn-custom btn-category w-100">จัดการหมวดหมู่</a>
            </div>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="../logout.php" class="btn logout-btn">ออกจากระบบ</a>
    </div>

    <footer>
        © <?= date("Y") ?> ระบบผู้ดูแล
    </footer>
</body>

</html>