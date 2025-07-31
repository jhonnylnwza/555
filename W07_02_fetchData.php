<?php

require_once 'W07_01_Connectdd.php';

$sql = "SELECT * FROM products";

$result = $conn->query($sql);//ใช้ๅqury เพื่อรันคำสั่ง sql และผลลัพธ์

//ตรวจสอบผลลัพธ์
if ($result->rowCount() > 0) {
    // output data of each row
    echo "<h2>พบข้อมูลในตาราง Product</h2>";
    $data = $resuli->fetchALL(PDO::FETCH_NUM);
    //echo "$data [0][0]<br>";
    print_r($data);

} else {
    echo "<h2>ไม่พบข้อมูลในตาราง Product</h2>";
    //echo "0 results";
}
?>