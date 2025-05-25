<?php

session_start();
require_once 'config.php';
include_once 'headers/header.php';

// Fetch all admins
$stmt = $pdo->query("SELECT id, name, email, telephone FROM users WHERE role = 'admin' ORDER BY name ASC");
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get current user id if logged in
$current_user_id = $_SESSION['user']['id'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Contact Support | StayWhere</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-10">
        <h1 class="text-3xl font-bold mb-8">Need support?</h1>
        <h2 class="text-xxl font-bold mb-8">Contact the Admins.</h2>

        <div class="overflow-x-auto">

            <table class="min-w-full bg-white rounded-xl shadow-lg overflow-hidden">
                <thead>
                    <tr>
                        <th class="py-3 px-4 border-b text-left">Name</th>
                        <th class="py-3 px-4 border-b text-left">Email</th>
                        <th class="py-3 px-4 border-b text-left">Telephone</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($admins as $i => $admin): ?>
                        <tr class="even:bg-grey-50 <?= ($current_user_id == $admin['id']) ? 'bg-grey-300 opacity-40' : '' ?>">
                            <td class="py-2 px-4 border-b whitespace-nowrap">
                                <a href="about-user.php?id=<?= $admin['id'] ?>" class="text-blue-700 hover:underline font-semibold">
                                    <?= htmlspecialchars($admin['name']) ?>
                                </a>
                            </td>
                            <td class="py-2 px-4 border-b whitespace-nowrap">
                                <a href="mailto:<?= htmlspecialchars($admin['email']) ?>" class="text-blue-600 hover:underline">
                                    <?= htmlspecialchars($admin['email']) ?>
                                </a>
                            </td>
                            <td class="py-2 px-4 border-b whitespace-nowrap"><?= htmlspecialchars($admin['telephone']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($admins)): ?>
                        <tr>
                            <td colspan="3" class="text-center py-6 text-gray-500">No admin support found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>