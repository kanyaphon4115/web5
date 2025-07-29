<?php 
session_start();
error_reporting(0);
$page = "proflie";

// ตรวจสอบว่าล็อกอินอยู่หรือไม่
if (!isset($_SESSION["email"])) {
    header("location: admin.php");
    exit();
}

include("../include/header.php");
include("../db2/connection.php");

// ส่วนดึงข้อมูล admin ตาม id มาแสดงในฟอร์ม
$id = $_GET['edit'];
$query = mysqli_query($conn, "SELECT * FROM admin_login WHERE id='$id'");

while ($row = mysqli_fetch_array($query)) {
    $email = $row['email'];
    $pass = $row['pass'];
}
?>

<div style="width: 70%; margin-left:25%; margin-top: 10%;">
  <form action="edit_admin.php?edit=<?php echo $id; ?>" method="post" name="adminform" onsubmit="return validateform()">
    <h1>Update Admin</h1>
    <hr>
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="text" class="form-control" placeholder="Enter email" value="<?php echo $email; ?>" id="email" name="email">
    </div>
    <div class="form-group">
      <label for="pass">Password:</label>
      <input type="text" class="form-control" placeholder="Enter password" value="<?php echo $pass; ?>" id="pass" name="pass">
    </div>
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <input type="submit" name="submit" class="btn btn-primary" value="Update Admin">
  </form>

  <script>
    function validateform(){
        var email = document.forms['adminform']['email'].value;
        var pass = document.forms['adminform']['pass'].value;
        if (email == '' || pass == '') {
            alert('Email and Password must be filled out');
            return false;
        }
        return true;
    }
  </script>
</div>

<?php
// ส่วนอัปเดตข้อมูลเมื่อกด submit
if (isset($_POST["submit"])) {  
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $id = $_POST['id'];

    $query = mysqli_query($conn, "UPDATE admin_login SET email='$email', pass='$pass' WHERE id='$id'");

    if ($query) {
        echo "<script>alert('Admin Updated Successfully');</script>";
        echo "<script>window.location='profile.php';</script>";
        exit();
    } else {
        echo "<script>alert('Admin Not Updated');</script>";
    }
}  

include("../include/footer.php");
?>
