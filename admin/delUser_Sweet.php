<?php
require '../config.php';
require 'auth_admin.php'; // ตรวจสอบสิทธิ์ของผู้ดูแลระบบ (admin)

// ตรวจสอบการส่งข้อมูลจากฟอร์ม
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['u_id'])) {
    $user_id = $_POST['u_id'];

    // ลบผู้ใช้งานจากฐานข้อมูล (เฉพาะผู้ที่มีบทบาทเป็น member)
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ? AND role = 'member'");
    $stmt->execute([$user_id]);

    // ส่งผลลัพธ์กลับไปยังหน้า users.php
    header("Location: users.php");
    exit;
}
?>