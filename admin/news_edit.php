<?php 
session_start();


// ตรวจสอบว่าล็อกอินอยู่หรือไม่
if (!isset($_SESSION["email"])) {
    header("location: admin.php");
    exit();
}

include("../include/header.php");
include("../db2/connection.php"); // ต้อง include connection ก่อนใช้งาน $conn
?>
<?php
$id = $_GET["id"]; 
$query= mysqli_query($conn,"select * from news where id ='$id'");
while ($row=mysqli_fetch_array($query)) {
$id=$row["id"];
$title=$row["title"]; 
$description=$row["description"];
$date=$row["date"];
$thumbnail=$row["thumbnail"];
$category=$row["category"]; 
}
?>

<div style="margin-left:17%; width:80%">
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="homeadmin.php">Home</a></li>
        <li class="breadcrumb-item"><a href="news.php">News</a></li>
        <li class="breadcrumb-item">Add News</li>
    </ul>
</div>
<div style="width: 70%; margin-left:25%;">
  <form action="news_edit.php" method="post" enctype="multipart/form-data" name="categoryform" onsubmit="return validateform()">
    <h1>Update News</h1>
    <hr>
    <div class="form-group">
      <label for="title">Title:</label>
      <input type="text" value="<?php echo $title;?>" class="form-control" placeholder="Title...." id="Title" name="Title">
    </div>
    <div class="form-group">
      <label for="description">Description:</label>
      <textarea class="form-control" placeholder="Description...." rows="5" id="description" name="description"><?php echo $description;?></textarea>
    </div>

    <div class="form-group">
      <label for="date">Date:</label>
      <input type="date" class="form-control" id="date" name="date" value="<?php echo $date;?>">
    </div>
   <div class="form-group">
  <label for="thumbnail">Thumbnail:</label>
  <input type="file" class="form-control img-thumbnail" id="thumbnail" name="thumbnail">
  <?php if (!empty($thumbnail)) { ?>
    <br>
    <img src="../images/<?php echo htmlspecialchars($thumbnail); ?>" alt="Current Thumbnail" style="width:150px; margin-top:10px;">
    <!-- เก็บชื่อไฟล์เก่าไว้ เผื่อยังไม่เปลี่ยนรูปตอนอัปเดต -->
    <input type="hidden" name="old_thumbnail" value="<?php echo htmlspecialchars($thumbnail); ?>">
  <?php } ?>
</div>
<input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

    <div class="form-group">
      <label for="category">Category:</label>
      <select class="form-control" name="category">
        <?php
        $query = mysqli_query($conn,"select * from category");
        while($row = mysqli_fetch_array($query)) {
            $category_name=$row['category_name'];
        ?>
            <option value="<?php echo $category_name;?>" <?php if($category_name==$category) echo 'selected'; ?>><?php echo $category_name;?></option>
        <?php } ?>
      </select>
    </div>
    <input type="submit" name="submit" class="btn btn-primary" value="Update">
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
<?php include("../include/footer.php"); 
include("../db2/connection.php");

if (isset($_POST["submit"])) {
    $id = $_POST['id'];
    $title = $_POST["Title"];
    $date = $_POST["date"];
    $description = $_POST["description"];
    $category = $_POST["category"];

    if (!empty($_FILES['thumbnail']['name'])) {
        $thumbnail = $_FILES['thumbnail']['name'];
        $tmp_thumbnail = $_FILES['thumbnail']['tmp_name'];
        move_uploaded_file($tmp_thumbnail, "../images/$thumbnail");
    } else {
        $thumbnail = $_POST['old_thumbnail'];
    }

    $sql = mysqli_query($conn, "UPDATE news SET title='$title', description='$description', date='$date', thumbnail='$thumbnail', category='$category' WHERE id='$id'");

    if ($sql) {
        echo "<script>alert('News update successfully')</script>";
        echo "<script>window.location='news.php';</script>";
    } else {
        echo "<script>alert('Try again')</script>";
    }
}
?>
