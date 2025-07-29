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

<div style="width: 70%; margin-left:25%; margin-top: 10%;">

  <form action="addcategory.php" method="post" name="categoryform" onsubmit="return validateform() ">
    <h1>Add Categories</h1>
    <hr>
    <div class="form-group">
      <label for="category">Category:</label>
      <input type="text" class="form-control" placeholder="Enter category" id="category" name="category">
    </div>
    <div class="form-group">
      <label for="comment">Description:</label>
      <textarea class="form-control" placeholder="Enter description" rows="5" id="comment" name="description"></textarea>
    </div>
    <input type="submit" name="submit" class="btn btn-primary" value="Add Category">

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
include("../include/footer.php");
?>
<?php
include("../db2/connection.php");
if (isset($_POST["submit"])) {   
    $category_name=$_POST['category'];
    $des=$_POST['description'];
    $cheak=mysqli_query($conn,"select * from category where category_name='$category_name'");
    if (mysqli_num_rows($cheak) > 0){
      echo "<script> alert('Category Name Already Exists');</script>";
      exit();
    }
    $query = mysqli_query($conn, "INSERT INTO category(category_name, des) VALUES ('$category_name','$des')");

    if($query){
        echo "<script> alert('Category add successfully')</script>";
    }else{
    echo "<script>alert('Please try again');</script>";
}
}
?>
