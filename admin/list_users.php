<?php 
session_start();
$page = "user";

// ตรวจสอบว่าล็อกอินอยู่หรือไม่
if (!isset($_SESSION["email"])) {
    header("location: admin.php");
    exit();
}

include("../include/header.php");
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<div style="width: 70%; margin-left:25%; margin-top: 10%;">
  <div class="text-right mb-2">
    <a href="add_users.php" class="btn btn-primary">Add User</a>
  </div>

  <table class="table table-bordered">
    <tr>
      <th>ID</th>
      <th>Email</th>
      <!-- <th>Password</th> ลบหัวตาราง Password ออก -->
      <th>Gender</th>
      <th>Edit</th>
      <th>Delete</th>
    </tr>

    <?php
      // เชื่อมต่อฐานข้อมูล register
      <?php
        include("../user/db.php");
        ?

      // ดึงข้อมูลจากตาราง user
      $query = mysqli_query($conn, "SELECT * FROM form");

      if (!$query) {
          die("Error: " . mysqli_error($conn));
      }

      while ($row = mysqli_fetch_array($query)) {
    ?>
    <tr>
      <td><?php echo $row["id"]; ?></td>
      <td><?php echo htmlspecialchars($row["email"]); ?></td>
      <!-- <td><?php echo htmlspecialchars($row["pass"]); ?></td> ลบการโชว์รหัสผ่านออก -->
      <td><?php echo htmlspecialchars($row["gender"]); ?></td>
      <td>
        <a href="edit_user.php?edit=<?php echo $row['id']; ?>" class="btn btn-outline-primary btn-sm">
          <i class="bi bi-pencil-fill"></i>
        </a>
      </td>
      <td>
        <a href="delete_user.php?del=<?php echo $row['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Confirm deletion?')">
          <i class="bi bi-trash-fill"></i>
        </a>
      </td>
    </tr>
    <?php } ?>
  </table>
</div>
<?php
include("../include/footer.php");
?>
