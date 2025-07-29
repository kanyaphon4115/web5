<?php
session_start();
$page = "admin";

// ตรวจสอบว่าล็อกอินอยู่หรือไม่
if (!isset($_SESSION["email"])) {
    header("location: admin.php");
    exit();
}

// เชื่อมต่อฐานข้อมูล
include("../db2/connection.php");

// ตรวจสอบว่ามี id ที่จะลบ
if (isset($_GET['del'])) {
    $id = intval($_GET['del']); // แปลงเป็นตัวเลขเพื่อความปลอดภัย

    // สั่งลบข้อมูล
    $deleteQuery = mysqli_query($conn, "DELETE FROM admin_login WHERE id='$id'");

    if ($deleteQuery) {
        echo "<script>alert('successfully deleted'); window.location='profile.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error try again'); window.location='profile.php';</script>";
        exit();
    }
} else {
    // ถ้าไม่มี id ส่งมา
    header("location: profile.php");
    exit();
}
?>
