<?php
session_start();
require 'config.php';

// Get user ID from query string
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  die("Invalid user ID.");
}
$user_id = (int)$_GET['id'];

// Fetch user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
  die("User not found.");
}

// Check if logged-in user is admin
$is_admin = isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';

// Check if viewed user is host
$is_host = $user['role'] === 'host';

// Fetch host's listings if applicable
$stays = [];
if ($is_host) {
  $stmt = $pdo->prepare("SELECT s.*, (SELECT url FROM images WHERE stay_id = s.id LIMIT 1) as image_url FROM stays s WHERE user_id = ?");
  $stmt->execute([$user_id]);
  $stays = $stmt->fetchAll(PDO::FETCH_ASSOC);
}



include_once 'headers/header.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="images/favicon.ico" type="image/x-icon">
  <title>About <?= htmlspecialchars($user['name']) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    .sticky-card {
      position: sticky;
      top: 1rem;
    }
  </style>
</head>

<body class="bg-gray-100 min-h-screen">
  <div class="container mx-auto px-4 py-8 grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- Profile Card -->
    <div class="bg-white p-6 rounded-xl shadow-md sticky top-6">
      <div class="text-center">
        <img src="<?= $user['profile_picture'] ? htmlspecialchars($user['profile_picture']) : 'uploads\profile_pics\no_pfp.jpg' ?>" class="mx-auto h-24 w-24 rounded-full object-cover" alt="Profile Picture">
        <h2 class="text-2xl font-semibold mt-4"><?= htmlspecialchars($user['name']) ?></h2>
        <p class="text-gray-500 capitalize"><?= htmlspecialchars($user['role']) ?> account</p>
      </div>

      <?php if ($is_host): ?>
        <!-- About Me -->
        <div class="mt-6">
          <h3 class="text-lg font-semibold text-gray-700">About Me</h3>
          <p class="text-gray-600 mt-2"><?= nl2br(htmlspecialchars($user['about_me'])) ?></p>
        </div>

        <!-- Contact Info -->
        <div class="text-center mt-6">
          <p class="text-gray-700 font-semibold">Contact me:</p>
          <a href="https://wa.me/<?= htmlspecialchars($user['telephone']) ?>" target="_blank" class="flex justify-center items-center mt-2 text-green-500 text-lg">
            <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp Logo" class="h-6 w-6 mr-2">
            <?= htmlspecialchars($user['telephone']) ?>
          </a>
        </div>
      <?php endif; ?>

      <?php if ($is_admin): ?>
        <form action="delete_account.php" method="POST" class="mt-6">
          <input type="hidden" name="user_id" value="<?= $user_id ?>">
          <button type="submit" class="w-full bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600">Delete Account</button>
        </form>
      <?php endif; ?>
    </div>

    <!-- User Info & Listings -->
    <div class="lg:col-span-2">
      <div class="bg-white p-6 rounded-xl shadow-md mb-8">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">User Info</h3>
        <p class="mb-2"><span class="font-semibold">Name:</span> <?= htmlspecialchars($user['name']) ?></p>
        <p class="mb-2"><span class="font-semibold">Email:</span> <?= htmlspecialchars($user['email']) ?></p>
        <p class="mb-2"><span class="font-semibold">Telephone:</span> <?= htmlspecialchars($user['telephone']) ?></p>

        <?php if ($user['role'] === 'guest'): ?>
          <br>
          <p class="mt-4"><span class="font-semibold">About Me:</span><br><?= nl2br(htmlspecialchars($user['about_me'])) ?></p>
        <?php endif; ?>
      </div>
      <?php if ($is_host): ?>
        <!-- host's listings from the database -->
        <div class="container px-6 py-12">
          <h2 class="text-3xl font-bold text-center mb-8">Host Listings</h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($stays as $stay): ?>
              <a href="staydetails.php?id=<?= $stay['id'] ?>" class="bg-white rounded-xl shadow-lg overflow-hidden block hover:shadow-xl transition">
                <img src="<?= $stay['image_url'] ? $stay['image_url'] : 'https://placehold.co/400x300?text=Stay+' . urlencode($stay['id']) ?>" alt="<?= htmlspecialchars($stay['title']) ?>" class="w-full h-48 object-cover">
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
      <?php endif; ?>

    </div>


  </div>
  <?php if ($user['role'] === 'admin'):?>
    <br><br><br><br>
  <?php endif; ?>
  <?php include 'headers/footer.php'; ?>
</body>

</html>