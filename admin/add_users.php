<?php 
session_start();
$page = "user";

// ตรวจสอบว่าล็อกอินอยู่หรือไม่
if (!isset($_SESSION["email"])) {
    header("location: admin.php");
    exit();
}

include("../include/header.php");

// เชื่อมต่อฐานข้อมูล
$conn = mysqli_connect("localhost", "root", "", "register") or die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . mysqli_connect_error());

// ถ้า submit ฟอร์ม
if (isset($_POST['add'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, $_POST['pass']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);

    // เพิ่มข้อมูลลงตาราง form
    $insertQuery = mysqli_query($conn, "INSERT INTO form (email, pass, gender) VALUES ('$email', '$pass', '$gender')");

    if ($insertQuery) {
        echo "<script>alert('User added successfully'); window.location='list_users.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error try again');</script>";
    }
}
?>

<div class="container mt-5" style="max-width: 600px;">
    <h3>Add new user</h3>
    <form method="post">
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="text" name="pass" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Gender</label>
            <select name="gender" class="form-control" required>
                <option value="" selected disabled>-- Select Gender --</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
        </div>
        <button type="submit" name="add" class="btn btn-primary">Add User</button>
       
    </form>
</div>

<?php
include("../include/footer.php");
?>
