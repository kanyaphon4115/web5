<?php
   session_start();
   include("db.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['pass'];
    $gender = $_POST['gender'];

    if (!empty($email) && !empty($password) && !is_numeric($email)) {
        $query = "INSERT INTO form (email, pass, gender) VALUES ('$email', '$password', '$gender')";
        mysqli_query($con, $query);

        echo "<script type='text/javascript'>alert('Successfully');</script>";
    } else {
        echo "<script type='text/javascript'>alert('Try again');</script>";// กรอกข้อมูลไม่ครบ
    }
}
?>
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
      <h1 class="text-slate-900 text-center text-2xl font-semibold mb-6">Sign up</h1>
      <form class="space-y-5" method="POST">
        <div>
          <label class="text-sm font-medium block mb-1 text-slate-900">Email</label>
          <input name="email" type="text" required class="w-full border border-slate-300 rounded-md px-4 py-2 text-sm outline-blue-600" placeholder="Enter email" />
        </div>
        <div>
          <label class="text-sm font-medium block mb-1 text-slate-900">Password</label>
          <input name="pass" type="password" required class="w-full border border-slate-300 rounded-md px-4 py-2 text-sm outline-blue-600" placeholder="Enter password" />
        </div>
         <div>
          <label class="text-sm font-medium block mb-1 text-slate-900">Gender</label>
          <input name="gender" type="gender" required class="w-full border border-slate-300 rounded-md px-4 py-2 text-sm outline-blue-600" placeholder="Enter gender" />
        </div>
        <button type="submit" class="w-full py-2 px-4 text-white bg-blue-600 hover:bg-blue-700 rounded-md text-sm font-medium">
          Sign up
        </button>
        <p class="text-center text-sm text-slate-900">
         Already have an account?
          <a href="login_user.php" class="text-blue-600 hover:underline font-semibold ml-1">Login here</a>
        </p>
      </form>
    </div>
  </div>
</div>
</body>
</html>


