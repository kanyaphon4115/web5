<?php 
session_start();
$page = "category";

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
    <a href="addcategory.php" class="btn btn-primary">Add Category</a>
  </div>
<table class="table table-bordered">
  <tr>
    <th>Id</th>
    <th>Category Name</th>
    <th>Description</th>
    <th>Edit</th>
    <th>Delete</th>
  </tr>
  <?php
    include("../db2/connection.php");
    $query = mysqli_query($conn, "SELECT * FROM category");
while ($row = mysqli_fetch_array($query)) {

  ?>
  <tr>
    <td><?php echo $row["id"];?></td>
    <td><?php echo $row["category_name"];?></td>
    <td><?php echo $row["des"];?></td>
    <td>
  <a href="edit.php?edit=<?php echo $row['id']; ?>" class="btn btn-outline-primary btn-sm">
    <i class="bi bi-pencil-fill"></i>
  </a>
</td>
<td>
  <a href="delete.php?del=<?php echo $row['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Confirm deletion?')">
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