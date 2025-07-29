<?php 
session_start();
$page = "admin";

// ตรวจสอบว่าล็อกอินอยู่หรือไม่
if (!isset($_SESSION["email"])) {
    header("location: admin.php");
    exit();
}

include("../include/header.php");

// เชื่อมต่อฐานข้อมูล
include("../db2/connection.php");

// ถ้า submit ฟอร์ม
if (isset($_POST['add'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, $_POST['pass']);

    // เพิ่มข้อมูลลงตาราง admin_login
    $insertQuery = mysqli_query($conn, "INSERT INTO admin_login (email, pass) VALUES ('$email', '$pass')");

    if ($insertQuery) {
        echo "<script>alert('Admin added successfully'); window.location='profile.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error try again');</script>";
    }
}
?>

<div class="container mt-5" style="max-width: 600px;">
    <h3>Add admin</h3>
    <form method="post">
        <div class="mb-3">
            <label>Email</label>
            <input type="text" name="email" class="form-control">
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="text" name="pass" class="form-control">
        </div>
        <button type="submit" name="add" class="btn btn-primary">Add admin</button>

    </form>
</div>

<?php
include("../include/footer.php");
?>
