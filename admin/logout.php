<?php
session_start();
session_unset();

header("location: admin.php"); // กลับไปหน้า login
?>
