<?php
session_start();
require_once 'config.php';
include_once 'headers/header.php';

// Redirect if not logged in or not host
if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'host') {
    header('Location: login.php');
    exit;
}

$name = htmlspecialchars($_SESSION['user']['name']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Host Dashboard | StayWhere</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-12">
        <div class="mb-10 text-center">
            <h1 class="text-3xl font-bold mb-2">Welcome <?= $name ?></h1>
            <div class="text-orange-600 font-semibold text-lg">host</div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            <a href="requests.php" class="bg-white rounded-xl shadow-lg p-8 flex flex-col items-center hover:shadow-xl transition group">
                <i class="fas fa-envelope-open-text text-4xl text-orange-500 mb-4 group-hover:text-orange-600"></i>
                <div class="text-xl font-semibold mb-2">Reservation Requests</div>
                <div class="text-gray-500">View and manage requests</div>
            </a>
            <a href="my-stays.php" class="bg-white rounded-xl shadow-lg p-8 flex flex-col items-center hover:shadow-xl transition group">
                <i class="fas fa-home text-4xl text-orange-500 mb-4 group-hover:text-orange-600"></i>
                <div class="text-xl font-semibold mb-2">My Stays</div>
                <div class="text-gray-500">Manage your stays</div>
            </a>
            <a href="support.php" class="bg-white rounded-xl shadow-lg p-8 flex flex-col items-center hover:shadow-xl transition group">
                <i class="fas fa-headset text-4xl text-orange-500 mb-4 group-hover:text-orange-600"></i>
                <div class="text-xl font-semibold mb-2">Contact Support</div>
                <div class="text-gray-500">Get help from our team</div>
            </a>
        </div>
        <div class="flex justify-center">
            <a href="index.php" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold shadow hover:bg-blue-700 transition">
                Browse Other Stays on the Website
            </a>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</body>
</html>