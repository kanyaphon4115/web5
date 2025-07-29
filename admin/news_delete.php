<?php
include("../db2/connection.php");
$id = $_GET['del'];
$query = mysqli_query($conn, "DELETE FROM news WHERE id='$id'");
if($query){
    echo "<script>alert('Deleted successfully');</script>";
    echo "<script>window.location='news.php';</script>";
} else {
    echo "Try again";
}
?>
