<?php
session_start();
require '../config.php';
require 'auth_admin.php';

// เพิ่มสินค้ารายการใหม่
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = trim($_POST['product_name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $category_id = intval($_POST['category_id']);

    if ($name && $price > 0) {
        $stmt = $conn->prepare("INSERT INTO products (product_name, description, price, stock, category_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $stock, $category_id]);
        header("Location: products.php");
        exit;
    }
}

// ลบสินค้า
if (isset($_GET['delete'])) {
    $product_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->execute([$product_id]);
    header("Location: products.php");
    exit;
}

// ดึงรายการสินค้า พร้อมชื่อหมวดหมู่
$stmt = $conn->query("SELECT p.*, c.category_name FROM products p LEFT JOIN categories c ON p.category_id = c.category_id ORDER BY p.created_at DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ดึงหมวดหมู่ทั้งหมด
$categories = $conn->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>จัดการสินค้า</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f5f0;
            font-family: "Prompt", sans-serif;
        }
        h2, h5 {
            color: #7c4d1e;
            font-weight: bold;
        }
        .card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #c49a6c;
            border: none;
        }
        .btn-primary:hover {
            background-color: #b5895a;
        }
        .btn-warning {
            background-color: #f5c16c;
            border: none;
        }
        .btn-danger {
            background-color: #c94f4f;
            border: none;
        }
        .btn-secondary {
            background-color: #8d6e63;
            border: none;
        }
        .form-label {
            font-weight: 600;
            color: #5d4037;
        }
        .table thead {
            background-color: #e6d3b3;
        }
    </style>
</head>

<body class="container py-4">

    <h2 class="mb-4">จัดการสินค้า</h2>
    <a href="index.php" class="btn btn-secondary mb-3">← กลับหน้าผู้ดูแล</a>

    <!-- ฟอร์มเพิ่มสินค้า -->
       <div class="card">
            <div class="card-header">
                เพิ่มสินค้ารายการใหม่
            </div>
            <div class="card-body">
                <form method="post" class="row g-3">
                    <div class="col-md-6">
                        <input type="text" name="product_name" class="form-control" placeholder="ชื่อสินค้า" required>
                    </div>
                    <div class="col-md-6">
                        <textarea name="description" class="form-control" placeholder="รายละเอียดสินค้า" rows="1"></textarea>
                    </div>
                    <div class="col-md-3">
                        <input type="number" step="0.01" name="price" class="form-control" placeholder="ราคา (บาท)" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="stock" class="form-control" placeholder="จำนวนคงเหลือ" required>
                    </div>
                    <div class="col-md-3">
                        <select name="category_id" class="form-select" required>
                            <option value="">เลือกหมวดหมู่</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" name="add_product" class="btn btn-primary w-100">เพิ่มสินค้า</button>
                    </div>
                </form>
            </div>
        </div>

    <!-- รายการสินค้า -->
    <div class="card p-4">
        <h5 class="mb-3">รายการสินค้า</h5>
        <table class="table table-bordered align-middle text-center">
            <thead>
                <tr>
                    <th>ชื่อสินค้า</th>
                    <th>หมวดหมู่</th>
                    <th>ราคา</th>
                    <th>คงเหลือ</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['product_name']) ?></td>
                        <td><?= htmlspecialchars($p['category_name']) ?></td>
                        <td><?= number_format($p['price'], 2) ?> บาท</td>
                        <td><?= $p['stock'] ?></td>
                        <td>
                            <a href="products.php?delete=<?= $p['product_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('ยืนยันการลบสินค้านี้?')">ลบ</a>
                            <a href="edit_product.php?id=<?= $p['product_id'] ?>" class="btn btn-sm btn-warning">แก้ไข</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="5">ยังไม่มีสินค้า</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
