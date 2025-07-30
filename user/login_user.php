<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_start(); // เพิ่มบรรทัดนี้
session_start();
include("db.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") 
{
    $email = $_POST['email'];
    $password = $_POST['pass'];

    if (!empty($email) && !empty($password) && !is_numeric($email))
    {
        $query = "SELECT * FROM form WHERE email = '$email' LIMIT 1";
        $result = mysqli_query($con, $query); 

        if ($result && mysqli_num_rows($result) > 0)
        {
            $user_data = mysqli_fetch_assoc($result);

            if ($user_data["pass"] == $password)
            {
                $_SESSION['user_id'] = $user_data['id'];

                // เพิ่ม 2 บรรทัดนี้เพื่อเก็บ email และ username ไว้ใน session
                $_SESSION['email'] = $user_data['email'];
                $_SESSION['username'] = $user_data['username'];

                header("Location: /web5/admin/homepage_news.php");
                exit();
            }
            else {
                echo "<script>alert('Wrong email or password');</script>"; 
            }
        }
        else {
            echo "<script>alert('Wrong email or password');</script>"; 
        }
    }
    else {
        echo "<script>alert('Please enter valid email and password');</script>";
    }
}
?>
<!-- HTML ส่วน login form เหมือนเดิม -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
  <div class="bg-gray-50 min-h-screen flex items-center justify-center py-10 px-4">
  <div class="w-full max-w-md mx-auto">
    <div class="p-6 sm:p-8 bg-white rounded-xl border border-gray-200 shadow-md">
      <h1 class="text-slate-900 text-center text-2xl font-semibold mb-6">Login User</h1>
      <form class="space-y-5" method="POST">
        <div>
          <label class="text-sm font-medium block mb-1 text-slate-900">Email</label>
          <input name="email" type="text" required class="w-full border border-slate-300 rounded-md px-4 py-2 text-sm outline-blue-600" placeholder="Enter email" />
        </div>
        <div>
          <label class="text-sm font-medium block mb-1 text-slate-900">Password</label>
          <input name="pass" type="password" required class="w-full border border-slate-300 rounded-md px-4 py-2 text-sm outline-blue-600" placeholder="Enter password" />
        </div>
        <div class="flex items-center justify-between">
          <div class="flex items-center justify-between">
            <a href="forgetpassword.php" class="text-sm text-red-600 hover:underline font-semibold">Forgot your password?</a>
          </div>
        </div>
        <button type="submit" class="w-full py-2 px-4 text-white bg-blue-600 hover:bg-blue-700 rounded-md text-sm font-medium">
          Login
        </button>
        <p class="text-center text-sm text-slate-900">
          Don't have an account?
          <a href="signup.php" class="text-blue-600 hover:underline font-semibold ml-1">Register here</a>
        </p>
      </form>
    </div>
  </div>
</div>
</body>
</html>
