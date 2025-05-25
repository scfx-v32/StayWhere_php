<?php
session_start();
require_once 'config.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];

    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $error = "Email is already registered.";
        } else {
            // Hash password and insert with role "host"
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, telephone, password, role, created_at) VALUES (?, ?, ?, ?, 'host', NOW())");
            $stmt->execute([$name, $email, $phone, $hashed]);

            // Auto-login
            $id = $pdo->lastInsertId();
            $_SESSION['user'] = [
                'id'    => $id,
                'name'  => $name,
                'email' => $email,
                'role'  => 'host'
            ];

            header("Location: host-dashboard.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Become a Host - StayWhere</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-gray-100 py-10">

    <div class="max-w-2xl mx-auto flex flex-col items-center">
        <!-- Logo -->
        <a href="index.php" class="mb-8">
            <img src="images/logo.png" alt="StayWhere Logo" class="h-16">
        </a>

        <div class="bg-white p-10 rounded-xl shadow-md w-full">
            <h2 class="text-3xl font-bold text-center mb-8">Become a StayWhere Host</h2>

            <?php if ($error): ?>
                <div class="bg-red-100 text-red-600 p-3 rounded mb-6 text-center text-sm"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" class="grid grid-cols-2 gap-x-6 gap-y-5">
                <div class="col-span-1">
                    <label class="block text-gray-700 mb-1">Full Name</label>
                    <input type="text" name="name" required
                        class="w-full p-3 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400">
                </div>

                <div class="col-span-1">
                    <label class="block text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required
                        class="w-full p-3 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400">
                </div>

                <div class="col-span-1">
                    <label class="block text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" required
                        class="w-full p-3 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400">
                </div>

                <div class="col-span-1">
                    <label class="block text-gray-700 mb-1">Confirm Password</label>
                    <input type="password" name="confirm" required
                        class="w-full p-3 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400">
                </div>

                <div class="col-span-1">
                    <label class="block text-gray-700 mb-1">Phone Number</label>
                    <input type="tel" name="phone" required
                        class="w-full p-3 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400">
                </div>

                <div class="col-span-1 flex items-end">
                    <button type="submit"
                        class="w-full bg-orange-500 text-white p-3 rounded-md hover:bg-orange-400 transition text-sm font-medium">
                        Register as Host
                    </button>
                </div>
            </form>

            <p class="mt-6 text-center text-sm text-gray-600">
                Already have a host account? <a href="login.php" class="text-blue-500 hover:underline">Log in</a>
            </p>
        </div>
    </div>

</body>

</html>
