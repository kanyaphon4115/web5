<?php 
session_start();
error_reporting(0);
$page = "category";

// ตรวจสอบว่าล็อกอินอยู่หรือไม่
if (!isset($_SESSION["email"])) {
    header("location: admin.php");
    exit();
}

include("../include/header.php");
?>

<?php
include("../db2/connection.php");
$id=$_GET['edit'];
$query = mysqli_query($conn, "SELECT * FROM category WHERE id='$id' ");

    while ($row = mysqli_fetch_array($query)) { 
    $category=$row['category_name'];
    $des=$row['des'];
} 
?>

<div style="width: 70%; margin-left:25%; margin-top: 10%;">

  <form action="edit.php" method="post" name="categoryform" onsubmit="return validateform() ">
    <h1>Update Category</h1>
    <hr>
    <div class="form-group">
      <label for="category">Category:</label>
      <input type="text" class="form-control" placeholder="Enter category" value="<?php echo $category; ?>" id="category" name="category">
    </div>
    <div class="form-group">
      <label for="comment">Description:</label>
      <textarea class="form-control" rows="5" id="comment" name="description"><?php echo $des; ?></textarea>
    </div>
    <input type="hidden" name="id" value="<?php echo $_GET['edit']?>">
    <input type="submit" name="submit" class="btn btn-primary" value="Update Category">

  </form>
  <script>
    function validateform(){
        var x = document.forms['categoryform']['category'].value;
        if (x==''){
            alert('Category must be filled out');
            return false;
        }
        return true;
    }
  </script>
</div>
<?php

if (isset($_POST["submit"])) {  
    $category = $_POST['category'];
    $id=$_POST['id'];
    $des=$_POST['description'];
    $query = mysqli_query($conn,"update category set category_name='$category', des='$des' where id='$id' ");

    if($query){
    echo "<script>alert('Category Updated Successfully');</script>";
    echo "<script>window.location='http://localhost/web5/admin/categories.php';</script>";
    exit();
}else{
    echo "<script>alert('Category Not Update');</script>";
}
}  
?>
<?php
include("../include/footer.php");
?>