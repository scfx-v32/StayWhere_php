<?php
ob_start();
require 'config.php';
include 'headers/header.php';

// Fetch stays from database with their first image
$stmt = $pdo->query("
    SELECT s.*, 
           (SELECT url FROM images WHERE stay_id = s.id LIMIT 1) as image_url
    FROM stays s
    WHERE s.available = 1
    ORDER BY s.id DESC
    LIMIT 12
");
$stays = $stmt->fetchAll(PDO::FETCH_ASSOC);
ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>StayWhere - Home</title>
  <link rel="icon" href="images\favicon.ico">
  <link rel="stylesheet" href="styles.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="script.js"></script>
</head>

<body>
  

  <!-- Hero Section -->
  <div class="container mx-auto px-6 py-12">
    <!-- Logo -->
    <div class="flex justify-center mb-8">
      <img src="images/logoQ.png" alt="StayWhere Logo" class="h-24">
    </div>
    <!-- Search Bar -->
    <form action="search.php" method="GET" class="bg-white p-6 rounded-xl shadow-lg flex flex-col md:flex-row gap-4">
      <input type="text" name="location" class="flex-1 p-3 border border-gray-300 rounded-xl" placeholder="Destination">
      <input type="date" name="check_in" class="p-3 border border-gray-300 rounded-xl" placeholder="Check-in">
      <input type="date" name="check_out" class="p-3 border border-gray-300 rounded-xl" placeholder="Check-out">
      <input type="number" name="guests" class="p-3 border border-gray-300 rounded-xl" placeholder="Guests" min="1">
      <button type="submit" class="bg-orange-500 text-white px-6 py-3 rounded-xl hover:bg-orange-600 transition">Search</button>
    </form>
  </div>

  <!-- Featured Stays -->
  <div class="container mx-auto px-6 py-12">
    <h2 class="text-3xl font-bold text-center mb-8">Featured Stays</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <?php foreach ($stays as $stay): ?>
      <a href="staydetails.php?id=<?= $stay['id'] ?>" class="bg-white rounded-xl shadow-lg overflow-hidden block hover:shadow-xl transition">
        <img src="<?= $stay['image_url'] ? $stay['image_url'] : 'https://placehold.co/400x300'.$stay['id'] ?>" 
             alt="<?= htmlspecialchars($stay['title']) ?>" 
             class="w-full h-48 object-cover">
        <div class="p-4">
          <h3 class="text-xl font-semibold"><?= htmlspecialchars($stay['title']) ?></h3>
          <p class="text-gray-600"><?= htmlspecialchars($stay['location']) ?></p>
          <p class="text-gray-700 font-bold">$<?= number_format($stay['price_per_night'], 2) ?>/night</p>
          <p class="text-sm text-gray-500">Max guests: <?= $stay['max_guests'] ?></p>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Include Footer -->
  <?php include 'headers/footer.php'; ?>

  <script>
  $(document).ready(function() {
    // Load header and footer
    $("#header").load("header.php");
    $("#footer").load("footer.php");
    
    // Set default dates for search form
    const today = new Date().toISOString().split('T')[0];
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const tomorrowStr = tomorrow.toISOString().split('T')[0];
    
    $('input[name="check_in"]').val(today);
    $('input[name="check_out"]').val(tomorrowStr);
  });
  </script>
</body>
</html>