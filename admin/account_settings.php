<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    include("../user/db.php");

    if ($newPassword !== $confirmPassword) {
        $message = "New password and confirm password do not match";
    } else {
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

            <div class="mb-3">
                <label class="block mb-1 text-sm">Old password</label>
                <input type="password" name="old_password" id="old_password" required class="w-full border px-3 py-2 rounded">
            </div>

            <div class="mb-3">
                <label class="block mb-1 text-sm">New password</label>
                <input type="password" name="new_password" id="new_password" required class="w-full border px-3 py-2 rounded">
            </div>

            <div class="mb-3">
                <label class="block mb-1 text-sm">Confirm password</label>
                <input type="password" name="confirm_password" id="confirm_password" required class="w-full border px-3 py-2 rounded">
            </div>

            <!-- Checkbox toggle -->
            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" onclick="togglePasswords()" class="mr-2">
                    Show Passwords
                </label>
            </div>

            <button type="submit" class="mt-4 w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
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

    <script>
        function togglePasswords() {
            const ids = ['old_password', 'new_password', 'confirm_password'];
            ids.forEach(id => {
                const input = document.getElementById(id);
                input.type = (input.type === "password") ? "text" : "password";
            });
        }
    </script>
</body>
</html>
