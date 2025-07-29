<?php
session_start();
include("../db2/connection.php");

if (isset($_POST["submit"])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['pass']; // ยังไม่เข้ารหัส

    // ดึงข้อมูลจากฐานข้อมูลตาม email
    $query = mysqli_query($conn, "SELECT * FROM admin_login WHERE email='$email' LIMIT 1");

    if ($query && mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        // ตรวจสอบรหัสผ่านแบบ plain text (ควรใช้ password_hash ในระบบจริง)
        if ($password === $row['pass']) {
            $_SESSION['email'] = $email;
            header("Location: homeadmin.php");
            exit();
        } else {
            echo "<script>alert('Incorrect password');</script>";
        }
    } else {
        echo "<script>alert('Email not found');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
  <div class="bg-gray-50 min-h-screen flex items-center justify-center py-10 px-4">
    <div class="w-full max-w-md mx-auto">
      <div class="p-6 sm:p-8 bg-white rounded-xl border border-gray-200 shadow-md">
        <h1 class="text-slate-900 text-center text-2xl font-semibold mb-6">Login Admin</h1>
        <form class="space-y-5" method="POST">
          <div>
            <label class="text-sm font-medium block mb-1 text-slate-900">Email</label>
            <input name="email" type="text" required class="w-full border border-slate-300 rounded-md px-4 py-2 text-sm outline-blue-600" placeholder="Enter email" />
          </div>
          <div>
            <label class="text-sm font-medium block mb-1 text-slate-900">Password</label>
            <input name="pass" type="password" required class="w-full border border-slate-300 rounded-md px-4 py-2 text-sm outline-blue-600" placeholder="Enter password" />
          </div>
          <button type="submit" name="submit" class="w-full py-2 px-4 text-white bg-blue-600 hover:bg-blue-700 rounded-md text-sm font-medium">
            Login
          </button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
