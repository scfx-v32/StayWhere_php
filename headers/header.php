<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// If user is logged in, check if they still exist in the database
if (isset($_SESSION['user']['id'])) {
  require_once __DIR__ . '/../config.php';
  $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
  $stmt->execute([$_SESSION['user']['id']]);
  if (!$stmt->fetchColumn()) {
    session_destroy();
    header("Location: /login.php");
    exit();
  }
}
?>

<header class="bg-white shadow sticky top-0 z-50">
  <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
    <!-- Logo -->
    <?php
    $logoHref = 'index.php';
    if (isset($_SESSION['user']['role'])) {
      if ($_SESSION['user']['role'] === 'admin') {
        $logoHref = 'admin-dashboard.php';
      } elseif ($_SESSION['user']['role'] === 'host') {
        $logoHref = 'host-dashboard.php';
      } elseif ($_SESSION['user']['role'] === 'guest') {
        $logoHref = 'index.php';
      }
    }
    ?>
    <a href="<?= $logoHref ?>" class="text-2xl font-bold text-orange-600">
      <img src="images/logo.png" alt="StayWhere Logo" class="h-11">
    </a>

    <!-- Buttons -->
    <div class="flex items-center space-x-4">
      <?php if (!isset($_SESSION['user'])): ?>
        <!-- Not logged in -->
        <a href="become-host.php" class="bg-orange-500 text-white px-4 py-2 rounded-xl hover:bg-orange-600 transition">Become a Host</a>
        <a href="login.php" class="text-orange-500 hover:text-orange-600">Login</a>
      <?php else: ?>
        <?php
        $role = $_SESSION['user']['role'];
        ?>

        <?php if ($role === 'guest'): ?>
          <div
            onclick="location.href='my-reservations.php'"
            class="cursor-pointer text-orange-500 hover:text-orange-600 px-4 py-2 rounded-xl transition border border-orange-100 hover:bg-orange-50 shadow-sm"
            style="display: flex; align-items: center; gap: 0.5rem;">
            <svg class="w-5 h-5 mr-2 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            My Reservations
          </div>
          <div
            onclick="location.href='wishlist.php'"
            class="cursor-pointer text-orange-500 hover:text-orange-600 px-4 py-2 rounded-xl transition border border-orange-100 hover:bg-orange-50 shadow-sm"
            style="display: flex; align-items: center; gap: 0.5rem;">
            <svg class="w-5 h-5 mr-2 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 21.364l-7.682-7.682a4.5 4.5 0 010-6.364z" />
            </svg>
            Wishlist
          </div>
        <?php elseif ($role === 'host'): ?>
          <div
            onclick="location.href='host-dashboard.php'"
            class="cursor-pointer text-orange-500 hover:text-orange-600 px-4 py-2 rounded-xl transition border border-orange-100 hover:bg-orange-50 shadow-sm"
            style="display: flex; align-items: center; gap: 0.5rem;">
            <svg class="w-5 h-5 mr-2 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Host Dashboard
          </div>
          <div
            onclick="location.href='add-stay.php'"
            class="cursor-pointer bg-orange-500 text-white px-4 py-2 rounded-xl hover:bg-orange-600 transition shadow"
            style="display: flex; align-items: center; gap: 0.5rem;">
            <svg class="w-5 h-5 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Stay
          </div>
        <?php elseif ($role === 'admin'): ?>
          <span class="font-semibold text-orange-600 mr-2">
            <?php echo strtoupper(htmlspecialchars($_SESSION['user']['name'])); ?>
          </span>
            <div
            onclick="location.href='support.php'"
            class="cursor-pointer text-orange-500 hover:text-orange-600 px-4 py-2 rounded-xl transition border border-orange-100 hover:bg-orange-50 shadow-sm"
            style="display: flex; align-items: center; gap: 0.5rem;">
            <svg class="w-5 h-5 mr-2 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 11-12.728 0M12 3v9" />
            </svg>
            Support
            </div>
          <div
            onclick="location.href='admin-dashboard.php'"
            class="cursor-pointer text-orange-500 hover:text-orange-600 px-4 py-2 rounded-xl transition border border-orange-100 hover:bg-orange-50 shadow-sm"
            style="display: flex; align-items: center; gap: 0.5rem;">
            <svg class="w-5 h-5 mr-2 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Admin Panel
          </div>
        <?php endif; ?>

        <!-- Hamburger Menu for Profile and Logout -->
        <div class="relative">
          <button id="menu-btn" class="text-orange-500 focus:outline-none">
            <!-- Hamburger icon -->
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
          <div id="menu-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg z-50">
            <a href="profile.php" class="block px-4 py-2 text-orange-500 hover:bg-orange-50 hover:text-orange-600">Profile</a>
            <a href="logout.php" class="block px-4 py-2 text-orange-500 hover:bg-orange-50 hover:text-orange-600">Logout</a>
          </div>
        </div>

        <script>
          // Simple JS to toggle the dropdown
          document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('menu-btn');
            const menu = document.getElementById('menu-dropdown');
            btn.addEventListener('click', function(e) {
              e.stopPropagation();
              menu.classList.toggle('hidden');
            });
            document.addEventListener('click', function() {
              menu.classList.add('hidden');
            });
          });
        </script>
      <?php endif; ?>
    </div> <!-- Correct closing of flex container -->
  </nav>
</header>