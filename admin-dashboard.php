<?php
session_start();
require_once 'config.php';

// Redirect if not logged in or not admin
if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$name = htmlspecialchars($_SESSION['user']['name']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <title>Host Dashboard | StayWhere</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen">
    <?php include_once 'headers/header.php'; ?>
    <div class="container mx-auto px-4 py-12">
        <div class="mb-10 text-center">
            <h1 class="text-3xl font-bold mb-2">Welcome <?= $name ?></h1>
            <div class="text-blue-600 font-semibold text-lg">admin</div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <a href="guests.php" class="bg-white rounded-xl shadow-lg p-8 flex flex-col items-center hover:shadow-xl transition group">
                <i class="fas fa-users text-4xl text-orange-500 mb-4 group-hover:text-orange-600"></i>
                <div class="text-xl font-semibold mb-2">Guest Users</div>
                <div class="text-gray-500">View all guests</div>
            </a>
            <a href="hosts.php" class="bg-white rounded-xl shadow-lg p-8 flex flex-col items-center hover:shadow-xl transition group">
                <i class="fas fa-user-tie text-4xl text-orange-500 mb-4 group-hover:text-orange-600"></i>
                <div class="text-xl font-semibold mb-2">Host Users</div>
                <div class="text-gray-500">View all hosts</div>
            </a>
            <a href="stays.php" class="bg-white rounded-xl shadow-lg p-8 flex flex-col items-center hover:shadow-xl transition group">
                <i class="fas fa-home text-4xl text-orange-500 mb-4 group-hover:text-orange-600"></i>
                <div class="text-xl font-semibold mb-2">Stays</div>
                <div class="text-gray-500">View all stays</div>
            </a>
        </div>

        <div class="flex justify-center mt-10">
            <a href="index.php" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold shadow hover:bg-blue-700 transition">
                Browse StayWhere
            </a>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</body>

</html>