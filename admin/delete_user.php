<?php
session_start();
$page = "user";

// ตรวจสอบว่าล็อกอินอยู่หรือไม่
if (!isset($_SESSION["email"])) {
    header("location: admin.php");
    exit();
}

// เชื่อมต่อฐานข้อมูล
$conn = mysqli_connect("localhost", "root", "", "register") or die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . mysqli_connect_error());

// ตรวจสอบว่ามี id ที่จะลบ
if (isset($_GET['del'])) {
    $id = intval($_GET['del']);

    // สั่งลบข้อมูล
    $deleteQuery = mysqli_query($conn, "DELETE FROM form WHERE id='$id'");

    if ($deleteQuery) {
        echo "<script>alert('Deleted successfully'); window.location='list_users.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error try again'); window.location='list_user.php';</script>";
        exit();
    }
} else {
    // ถ้าไม่มี id ส่งมา
    header("location: list_user.php");
    exit();
}
?>
