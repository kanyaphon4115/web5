<?php
// เริ่ม session และเชื่อมต่อฐานข้อมูลตามต้องการ
session_start();

// ตรวจสอบว่าผู้ใช้ล็อกอินแล้วหรือไม่
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
$message = "";

// ตรวจสอบการส่งฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];

    // เชื่อมต่อฐานข้อมูล
    include("../user/db.php"); // สมมุติว่าที่นี่มี $con = ...

    $sql = "SELECT pass FROM form WHERE email = '$email'";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        die("Query error: " . mysqli_error($con));
    }

    $row = mysqli_fetch_assoc($result);
if ($row) {
    if ($oldPassword === $row['pass']) {
        $updateSql = "UPDATE form SET pass='$newPassword' WHERE email='$email'";
        if (mysqli_query($con, $updateSql)) {
            $message = "Password successfully changed";
        } else {
            $message = "Error change ";
        }
    } else {
        $message = "Old password is incorrect";
    }
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Settings</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 py-10">
    <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Account Settings</h2>

        <form method="post">
            <div class="mb-4">
                <label class="block font-semibold mb-1">Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="w-full border px-3 py-2 rounded bg-gray-100" readonly>
            </div>

            <h3 class="text-md font-bold mt-4 mb-2">Change Password</h3>

            <div class="flex gap-4">
                <div class="w-1/2">
                    <label class="block mb-1 text-sm">Old password</label>
                    <input type="password" name="old_password" required class="w-full border px-3 py-2 rounded">
                </div>

                <div class="w-1/2">
                    <label class="block mb-1 text-sm">New password</label>
                    <input type="password" name="new_password" required class="w-full border px-3 py-2 rounded">
                </div>
            </div>

            <button type="submit" class="mt-6 w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                Save Changes
            </button>
            <a href="homepage_news.php" class="mt-4 block text-center w-full bg-gray-400 text-white py-2 rounded hover:bg-gray-500">
            Back Home
            </a>
            <?php if ($message): ?>
                <p class="mt-4 text-center text-red-500 font-semibold"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
