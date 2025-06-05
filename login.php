<?php
session_start();

if (isset($_SESSION['user'])) {
    // Already logged in → redirect based on role
    $role = $_SESSION['user']['role'];
    switch ($role) {
        case 'host':
            header("Location: host-dashboard.php");
            break;
        case 'admin':
            header("Location: admin-dashboard.php");
            break;
        default:
            header("Location: index.php");
    }
    exit;
}

require_once 'config.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ];

        // Redirect based on role
        switch ($user['role']) {
            case 'host':
                header("Location: host-dashboard.php");
                break;
            case 'admin':
                header("Location: admin-dashboard.php");
                break;
            default:
                header("Location: index.php");
        }
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <title>StayWhere - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="flex flex-col items-center space-y-8">

  <!-- Logo -->
  <div>
    <a href="index.php">
    <img src="images/logo.png" alt="StayWhere Logo" class="h-16">
    </a>
  </div>

  <!-- Login Card -->
  <div class="bg-white py-8 px-10 rounded-xl shadow-lg w-[400px]">

        <h2 class="text-2xl font-bold text-center mb-6">Log in to StayWhere</h2>

        <?php if ($error): ?>
            <div class="bg-red-100 text-red-600 p-2 rounded mb-4 text-sm text-center"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700 mb-1">Email</label>
                <input type="email" name="email" class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400" required>
            </div>

            <div>
                <label class="block text-gray-700 mb-1">Password</label>
                <input type="password" name="password" class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400" required>
            </div>

            <div class="flex justify-between items-center text-sm">
                <label class="inline-flex items-center">
                    <input type="checkbox" class="mr-2">
                    <span class="text-gray-600">Remember Me</span>
                </label>
                <a href="#" class="text-blue-500 hover:underline">Forgot password?</a>
            </div>

            <button type="submit" class="w-full bg-orange-500 text-white p-2 rounded-md hover:bg-orange-400 transition">
                Log In
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
            New to StayWhere? <a href="register.php" class="text-blue-500 hover:underline">Sign up</a>
        </p>
    </div>
</div>

</body>
</html>
