<?php
    include("../db2/connection.php");
    $id=$_GET['del'];
    $query=mysqli_query($conn,"delete from category where id='$id'");
     if ($query){
    echo "<script>alert('category deleted'); window.location.href='categories.php';</script>";
    
     }else{
        echo "try agin";
     }
?>