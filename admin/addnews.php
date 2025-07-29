<?php 
session_start();
$page = "prductt";

// ตรวจสอบว่าล็อกอินอยู่หรือไม่
if (!isset($_SESSION["email"])) {
    header("location: admin.php");
    exit();
}

include("../include/header.php");
?>
<div style="margin-left:17%; width:80%">
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="homeadmin.php">Home</a></li>
        <li class="breadcrumb-item"><a href="news.php">News</a></li>
        <li class="breadcrumb-item">Add News</li>
    </ul>
</div>
<div style="width: 70%; margin-left:25%;">
  <form action="addnews.php" method="post" enctype="multipart/form-data" name="categoryform" onsubmit="return validateform()">
    <h1>Add News</h1>
    <hr>
    <div class="form-group">
      <label for="title">Title:</label>
      <input type="text" class="form-control" placeholder="Title...." id="Title" name="Title">
    </div>
    <div class="form-group">
      <label for="description">Description:</label>
      <textarea class="form-control" placeholder="Description...." rows="5" id="description" name="description"></textarea>
    </div>
    <div class="form-group">
      <label for="date">Date:</label>
      <input type="date" class="form-control" id="date" name="date">
    </div>
    <div class="form-group">
      <label for="thumbnail">Thumbnail:</label>
      <input type="file" class="form-control img-thumbnail" id="thumbnail" name="thumbnail">
    </div>
    <div class="form-group">
      <label for="category">Category:</label>
      <select class="form-control" name="category">
        <?php
        include("../db2/connection.php");
        $query = mysqli_query($conn,"select * from category");
        while($row = mysqli_fetch_array($query)) {
            $category=$row['category_name'];
        ?>
            <option value="<?php echo $category;?>"><?php echo $category;?></option>
        <?php } ?>
      </select>
    </div>
    <input type="submit" name="submit" class="btn btn-primary" value="Add News">
  </form>

  <script>
    function validateform(){
        var title = document.forms['categoryform']['Title'].value;
        var description = document.forms['categoryform']['description'].value;
        var date = document.forms['categoryform']['date'].value;
        var category = document.forms['categoryform']['category'].value;

        if(title == ''){
            alert('Title must be filled out');
            return false;
        }
        if(description == ''){
            alert('Description must be filled out');
            return false;
        }
        if(date == ''){
            alert('Date must be filled out');
            return false;
        }
        if(category == ''){
            alert('Category must be selected');
            return false;
        }
        return true;
    }
  </script>
</div>
<?php include("../include/footer.php"); ?>

<?php 
include("../db2/connection.php");
if (isset($_POST["submit"])) {
    $title=$_POST["Title"];
    $date = $_POST["date"];
    $description=$_POST["description"];
    $thumbnail=$_FILES["thumbnail"]["name"];
    $tmp_thumbnail=$_FILES["thumbnail"]["tmp_name"];
    $category=$_POST["category"];

    if(!empty($thumbnail)){
        move_uploaded_file($tmp_thumbnail,"../images/$thumbnail");
    }

    $query=mysqli_query($conn,"INSERT INTO news (title,description,date,category,thumbnail) 
    VALUES ('$title','$description','$date','$category','$thumbnail')");

    if ($query) { 
       echo"<script>alert('News uploaded successfully')</script> ";
    }else{
       echo "<script>alert('Try again')</script> ";
    }
}
?>
