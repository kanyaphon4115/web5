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
include("../user/db.php"); 
// ตรวจสอบว่ามี id ที่จะทำการแก้ไข
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);

    // ดึงข้อมูลผู้ใช้ตาม id
    $query = mysqli_query($conn, "SELECT * FROM form WHERE id='$id'");
    $row = mysqli_fetch_assoc($query);

    if (!$row) {
        die("not found information.");
    }
} else {
    die("not found id ");
}

// ถ้า submit ฟอร์ม
if (isset($_POST['update'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, $_POST['pass']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);

    // อัปเดตข้อมูล
    $updateQuery = mysqli_query($conn, "UPDATE form SET email='$email', pass='$pass', gender='$gender' WHERE id='$id'");

    if ($updateQuery) {
        echo "<script>alert('User Updated Successfully'); window.location='list_users.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error try again');</script>";
    }
}
?>

<div class="container mt-5" style="max-width: 600px;">
    <h3>Update User</h3>
    <form method="post">
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="text" name="pass" value="<?php echo htmlspecialchars($row['pass']); ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Gender</label>
            <select name="gender" class="form-control" required>
                <option value="male" <?php if($row['gender'] == 'male') echo 'selected'; ?>>Male</option>
                <option value="female" <?php if($row['gender'] == 'female') echo 'selected'; ?>>Female</option>
                <option value="other" <?php if($row['gender'] == 'other') echo 'selected'; ?>>Other</option>
            </select>
        </div>
        <button type="submit" name="update" class="btn btn-primary">Update User</button>
    </form>
</div>

<?php
include("../include/footer.php");
?>
