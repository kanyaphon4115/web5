<?php
session_start();
include("db.php");

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm-password']);

    if (empty($email) || empty($password) || empty($confirm_password)) {
        $message = "กรุณากรอกข้อมูลให้ครบถ้วน";
    } elseif ($password !== $confirm_password) {
        $message = "รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน";
    } else {
        $email_safe = mysqli_real_escape_string($con, $email);
        $query = "SELECT id FROM form WHERE email = '$email_safe' LIMIT 1";
        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) === 1) {
            // อัปเดตรหัสผ่านแบบเก็บตรง ๆ (plaintext)
            $update_query = "UPDATE form SET pass = '$password' WHERE email = '$email_safe'";
            if (mysqli_query($con, $update_query)) {
                $message = "เปลี่ยนรหัสผ่านสำเร็จ";
            } else {
                $message = "เกิดข้อผิดพลาดในการอัปเดตรหัสผ่าน";
            }
        } else {
            $message = "ไม่พบอีเมลในระบบ";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Forget Password</title>

  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet" />

  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          colors: {
            primary: {
              50: "#eff6ff",
              100: "#dbeafe",
              200: "#bfdbfe",
              300: "#93c5fd",
              400: "#60a5fa",
              500: "#3b82f6",
              600: "#2563eb",
              700: "#1d4ed8",
              800: "#1e40af",
              900: "#1e3a8a",
              950: "#172554"
            }
          }
        },
        fontFamily: {
          body: [
            "Inter", "ui-sans-serif", "system-ui", "-apple-system", "system-ui",
            "Segoe UI", "Roboto", "Helvetica Neue", "Arial", "Noto Sans", "sans-serif",
            "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"
          ],
          sans: [
            "Inter", "ui-sans-serif", "system-ui", "-apple-system", "system-ui",
            "Segoe UI", "Roboto", "Helvetica Neue", "Arial", "Noto Sans", "sans-serif",
            "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"
          ]
        }
      }
    }
  </script>

  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white">

<section class="bg-gray-50 dark:bg-gray-900 min-h-screen flex items-center justify-center px-6 py-8">
  <div class="w-full max-w-md p-6 bg-white rounded-lg shadow dark:bg-gray-800 dark:border dark:border-gray-700 sm:p-8">
      <h2 class="mb-6 text-2xl font-bold text-center text-gray-900 dark:text-white">Forget Password</h2>

      <?php if ($message): ?>
        <div class="mb-4 text-center text-sm font-semibold text-red-600 dark:text-red-400">
          <?= htmlspecialchars($message) ?>
        </div>
      <?php endif; ?>

      <form action="" method="POST" class="space-y-5">
          <div>
              <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
              <input type="email" name="email" id="email" placeholder="name@company.com" required
                     class="w-full p-2.5 text-gray-900 border border-gray-300 rounded-lg
                            bg-gray-50 focus:ring-primary-600 focus:border-primary-600
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" />
          </div>
          <div>
              <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">New Password</label>
              <input type="password" name="password" id="password" placeholder="••••••••" required
                     class="w-full p-2.5 text-gray-900 border border-gray-300 rounded-lg
                            bg-gray-50 focus:ring-primary-600 focus:border-primary-600
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" />
          </div>
          <div>
              <label for="confirm-password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm Password</label>
              <input type="password" name="confirm-password" id="confirm-password" placeholder="••••••••" required
                     class="w-full p-2.5 text-gray-900 border border-gray-300 rounded-lg
                            bg-gray-50 focus:ring-primary-600 focus:border-primary-600
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" />
          </div>
          <button type="submit"
                  class="w-full text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300
                         font-medium rounded-lg text-sm px-5 py-2.5 text-center
                         dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
            Reset Password
          </button>
         <p class="text-center text-sm">
          <span class="text-white">Try again</span>
  <a href="login_user.php" class="text-green-600 hover:underline font-semibold ml-1">login here</a>
</p>

      </form>
  </div>
</section>

</body>
</html>
