<?php
session_start();
require_once 'config.php';
include_once 'headers/header.php';

// Only admin can access
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Handle delete action
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$delete_id]);
    header("Location: hosts.php");
    exit;
}

// Fetch guest users (not admin, not host)
$stmt = $pdo->query("SELECT id, name, email, telephone, created_at FROM users WHERE role='host' ORDER BY created_at DESC");
$hosts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Host Users | StayWhere</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-10">
        <h1 class="text-3xl font-bold mb-8">Host Users</h1>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-xl shadow-lg">
                <thead>
                    <tr>
                        <th class="py-3 px-4 border-b">ID</th>
                        <th class="py-3 px-4 border-b">Name</th>
                        <th class="py-3 px-4 border-b">Email</th>
                        <th class="py-3 px-4 border-b">Telephone</th>
                        <th class="py-3 px-4 border-b">Joined</th>
                        <th class="py-3 px-4 border-b">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($hosts as $host): ?>
                    <tr class="hover:bg-gray-100">
                        <td class="py-2 px-4 border-b"><?= $host['id'] ?></td>
                        <td class="py-2 px-4 border-b"><?= htmlspecialchars($host['name']) ?></td>
                        <td class="py-2 px-4 border-b"><?= htmlspecialchars($host['email']) ?></td>
                        <td class="py-2 px-4 border-b"><?= htmlspecialchars($host['telephone']) ?></td>
                        <td class="py-2 px-4 border-b"><?= htmlspecialchars($host['created_at']) ?></td>
                        <td class="py-2 px-4 border-b">
                            <a href="about-user.php?id=<?= $host['id'] ?>" class="text-blue-600 hover:underline mr-3">View</a>
                            <a href="guests.php?delete=<?= $host['id'] ?>" class="text-red-600 hover:underline"
                               onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($hosts)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-6 text-gray-500">No Host users found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>