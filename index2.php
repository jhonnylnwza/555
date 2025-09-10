<?php
session_start();
require_once 'config.php';

$isLoggedIn = isset($_SESSION['user_id']);

$stmt = $conn->query("SELECT p.*, c.category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.category_id
    ORDER BY p.created_at DESC");
$products = $stmt->fetchAll();
?>  

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าหลัก</title>
    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to bottom, #fff8e7, #fff);
            font-family: 'Kanit', sans-serif;
            color: #4b3b2f;
        }

        h1 {
            color: #b8860b;
            font-weight: bold;
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(184, 134, 11, 0.15);
            background-color: #fffaf0;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }

        .btn {
            border-radius: 12px;
            font-weight: bold;
        }

        .btn-info {
            background-color: #f7c948;
            border: none;
            color: #4b3b2f;
        }

        .btn-warning {
            background-color: #ffe08a;
            border: none;
            color: #4b3b2f;
        }

        .btn-secondary {
            background-color: #d6c8a2;
            border: none;
            color: #4b3b2f;
        }

        .btn-success {
            background-color: #a3d977;
            border: none;
            color: #2d472c;
        }

        .btn-primary,
        .btn-outline-primary {
            background-color: #e6b800;
            border: none;
            color: #fff;
        }

        .btn-outline-primary:hover {
            background-color: #d4af37;
            color: #fff;
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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>รายการสินค้า</h1>
        <div>
            <?php if ($isLoggedIn): ?>
                <span class="me-3">ยินดีต้อนรับ, <?= htmlspecialchars($_SESSION['username']) ?>
                    (<?= htmlspecialchars($_SESSION['role']) ?>)</span>
                <a href="profile.php" class="btn btn-info">ข้อมูลส่วนตัว</a>
                <a href="cart.php" class="btn btn-warning">ดูตะกร้า</a>
                <a href="logout.php" class="btn btn-secondary">ออกจากระบบ</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-success">เข้าสู่ระบบ</a>
                <a href="register.php" class="btn btn-primary">สมัครสมาชิก</a>
            <?php endif; ?>
        </div>
    </div>

    <!--รายการแสดงสินค้า-->
    <div class="row">
        <?php foreach ($products as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title text-truncate"><?= htmlspecialchars($product['product_name']) ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($product['category_name']) ?></h6>
                        <p class="card-text" style="min-height: 60px;">
                            <?= nl2br(htmlspecialchars(mb_strimwidth($product['description'], 0, 100, "..."))) ?>
                        </p>
                        <p><strong>ราคา:</strong> <?= number_format($product['price'], 2) ?> บาท</p>

                        <?php if ($isLoggedIn): ?>
                            <form action="cart.php" method="post" class="d-inline">
                                <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-sm btn-success">เพิ่มในตะกร้า</button>
                            </form>
                        <?php else: ?>
                            <small class="text-muted">เข้าสู่ระบบเพื่อสั่งซื้อ</small>
                        <?php endif; ?>
                        <a href="product_detail.php?id=<?= $product['product_id'] ?>"
                            class="btn btn-sm btn-outline-primary float-end">ดูรายละเอียด</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <footer>
        © <?= date("Y") ?> ร้านค้าออนไลน์
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
