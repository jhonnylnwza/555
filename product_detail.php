<?php
session_start();
require_once 'config.php';

if (!isset($_GET['id'])) {
    header('location: index2.php');
    exit();
}
$isLoggedIn = isset($_SESSION['user_id']) ? true : false;

$product_id = $_GET['id'];

$stmt = $conn->prepare("SELECT p.*, c.category_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.category_id
        WHERE p.product_id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>รายละเอียดสินค้า</title>
    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to bottom, #fff8e7, #fff);
            font-family: 'Kanit', sans-serif;
            color: #4b3b2f;
        }

        h3 {
            color: #b8860b;
            font-weight: bold;
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(184, 134, 11, 0.2);
            background-color: #fffaf0;
        }

        .btn {
            border-radius: 12px;
            font-weight: bold;
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

        .alert-info {
            background-color: #ffe08a;
            color: #4b3b2f;
            border: none;
            border-radius: 12px;
        }

        label {
            font-weight: bold;
            color: #4b3b2f;
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

    <a href="index2.php" class="btn btn-secondary mb-3">← กลับหน้ารายการสินค้า</a>

    <div class="card">
        <div class="card-body">
            <h3 class="card-title"><?= htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8') ?></h3>
            <h6 class="text-muted mb-3">
                หมวดหมู่: <?= htmlspecialchars($product['category_name'], ENT_QUOTES, 'UTF-8') ?>
            </h6>

            <p class="card-text"><?= nl2br(htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8')) ?></p>
            <p><strong>ราคา:</strong> <?= number_format($product['price'], 2) ?> บาท</p>
            <p><strong>คงเหลือ:</strong> <?= $product['stock'] ?> ชิ้น</p>

            <?php if ($isLoggedIn): ?>
                <form action="cart.php" method="post" class="mt-3">
                    <input type="hidden" name="product_id"
                        value="<?= htmlspecialchars($product['product_id'], ENT_QUOTES, 'UTF-8') ?>">
                    <div class="mb-3">
                        <label for="quantity">จำนวน:</label>
                        <input type="number" name="quantity" id="quantity" class="form-control w-auto d-inline"
                            value="1" min="1" max="<?= $product['stock'] ?>" required>
                    </div>
                    <button type="submit" class="btn btn-success">เพิ่มในตะกร้า</button>
                </form>
            <?php else: ?>
                <div class="alert alert-info mt-3 text-center">
                    กรุณาเข้าสู่ระบบเพื่อสั่งซื้อสินค้า
                </div>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        © <?= date("Y") ?> ร้านค้าออนไลน์
    </footer>

</body>

</html>
