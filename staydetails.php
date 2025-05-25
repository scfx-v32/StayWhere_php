<?php
session_start();
require_once 'config.php';
include 'headers/header.php';

// --- User authentication (replace with your own logic) ---

$user_id = $_SESSION['user']['id'] ?? null;

// Get stay id from query string or default to 1
$stay_id = isset($_GET['id']) ? intval($_GET['id']) : 1;



// Handle AJAX wishlist toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_wishlist'])) {
  if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in.']);
    exit;
  }
  // Get or create user's wishlist
  $stmt = $pdo->prepare("SELECT id FROM wishlists WHERE user_id = ?");
  $stmt->execute([$user_id]);
  $wishlist_id = $stmt->fetchColumn();
  if (!$wishlist_id) {
    $stmt = $pdo->prepare("INSERT INTO wishlists (user_id, created_at, updated_at) VALUES (?, NOW(), NOW())");
    $stmt->execute([$user_id]);
    $wishlist_id = $pdo->lastInsertId();
  }
  // Check if stay is already in wishlist
  $stmt = $pdo->prepare("SELECT id FROM wishlist_stay WHERE wishlist_id = ? AND stay_id = ?");
  $stmt->execute([$wishlist_id, $stay_id]);
  $in_wishlist = $stmt->fetchColumn();

  if ($in_wishlist) {
    // Remove from wishlist
    $stmt = $pdo->prepare("DELETE FROM wishlist_stay WHERE wishlist_id = ? AND stay_id = ?");
    $stmt->execute([$wishlist_id, $stay_id]);
    echo json_encode(['success' => true, 'in_wishlist' => false]);
  } else {
    // Add to wishlist
    $stmt = $pdo->prepare("INSERT INTO wishlist_stay (wishlist_id, stay_id, created_at, updated_at) VALUES (?, ?, NOW(), NOW())");
    $stmt->execute([$wishlist_id, $stay_id]);
    echo json_encode(['success' => true, 'in_wishlist' => true]);
  }
  exit;
}


// Fetch stay details
$stmt = $pdo->prepare("SELECT * FROM stays WHERE id = ?");
$stmt->execute([$stay_id]);
$stay = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$stay) {
  die("Stay not found.");
}

// Check if stay has confirmed reservations
$stmt = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE stay_id = ? AND status = 'confirmed'");
$stmt->execute([$stay_id]);
$has_confirmed_reservation = $stmt->fetchColumn() > 0;

//Is Admin or Host? Is the stay's host?
$is_admin = isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin';
$is_host = isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'host';
$is_stay_host = $user_id && isset($stay['user_id']) && $stay['user_id'] == $user_id;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_stay'])) {
  // Only allow the host or admin to delete
  if ($is_stay_host || $is_admin) {
    if ($has_confirmed_reservation) {
      echo "<script>alert('You cannot delete a stay with confirmed reservations.');window.location.reload();</script>";
      exit;
    }
    $delete_id = intval($_POST['delete_stay']);
    // Delete images
    $stmt = $pdo->prepare("SELECT url FROM images WHERE stay_id = ?");
    $stmt->execute([$delete_id]);
    foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $img_url) {
      @unlink($img_url);
    }
    $pdo->prepare("DELETE FROM images WHERE stay_id = ?")->execute([$delete_id]);
    // Delete amenities/facilities
    $pdo->prepare("DELETE FROM stay_amenity WHERE stay_id = ?")->execute([$delete_id]);
    $pdo->prepare("DELETE FROM stay_facility WHERE stay_id = ?")->execute([$delete_id]);
    // Delete reservations
    $pdo->prepare("DELETE FROM reservations WHERE stay_id = ?")->execute([$delete_id]);
    // Delete the stay
    $pdo->prepare("DELETE FROM stays WHERE id = ?")->execute([$delete_id]);
    echo "<script>alert('Stay deleted successfully.');window.location='my-stays.php';</script>";
    exit;
  } else {
    echo "<script>alert('You are not allowed to delete this stay.');</script>";
  }
}

//Fetch user's reservation for this stay
$user_reservation = null;
if ($user_id) {
  $stmt = $pdo->prepare("SELECT * FROM reservations WHERE user_id = ? AND stay_id = ? ORDER BY created_at DESC LIMIT 1");
  $stmt->execute([$user_id, $stay_id]);
  $user_reservation = $stmt->fetch(PDO::FETCH_ASSOC);
}

//Booking a stay, adding a reservation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkIn'], $_POST['checkOut'])) {
  if (!$user_id) {
    echo "<script>alert('You must be logged in to book a stay.');window.location='login.php';</script>";
    exit;
  }
  $check_in = $_POST['checkIn'];
  $check_out = $_POST['checkOut'];

  // Calculate nights and total price
  $date1 = new DateTime($check_in);
  $date2 = new DateTime($check_out);
  $nights = $date1 < $date2 ? $date1->diff($date2)->days : 0;
  if ($nights < 1) {
    echo "<script>alert('Check-out must be after check-in.');window.history.back();</script>";
    exit;
  }
  $total_price = $nights * $stay['price_per_night'];

  // Check for overlapping reservations for this user (any stay)
  $stmt = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE user_id = ? AND NOT (check_out <= ? OR check_in >= ?)");
  $stmt->execute([$user_id, $check_in, $check_out]);
  if ($stmt->fetchColumn() > 0) {
    echo "<script>alert('You already have a reservation that overlaps with these dates.');window.history.back();</script>";
    exit;
  }

  // Insert reservation
  $stmt = $pdo->prepare("INSERT INTO reservations (user_id, stay_id, check_in, check_out, total_price, status, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, 'pending', NOW(), NOW())");
  $stmt->execute([$user_id, $stay_id, $check_in, $check_out, $total_price]);
  echo "<script>alert('Reservation successful!');window.location='my-reservations.php';</script>";
  exit;
}

// Cancel reservation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_reservation'])) {
  $reservation_id = intval($_POST['cancel_reservation']);
  // Only allow cancel if user owns it and it's pending or not approved
  $stmt = $pdo->prepare("SELECT * FROM reservations WHERE id = ? AND user_id = ? AND stay_id = ?");
  $stmt->execute([$reservation_id, $user_id, $stay_id]);
  $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($reservation && in_array($reservation['status'], ['pending', 'declined'])) {
    $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
    $stmt->execute([$reservation_id]);
    echo "<script>alert('Reservation cancelled.');window.location.reload();</script>";
    exit;
  }
}

// Fetch host details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$stay['user_id']]);
$host = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch facilities for this stay
$stmt = $pdo->prepare("SELECT f.name, f.icon_url, sf.count FROM stay_facility sf
    JOIN facilities f ON sf.facility_id = f.id
    WHERE sf.stay_id = ?");
$stmt->execute([$stay_id]);
$facilities = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch amenities for this stay
$stmt = $pdo->prepare("SELECT a.name, a.icon_url FROM stay_amenity sa
    JOIN amenities a ON sa.amenity_id = a.id
    WHERE sa.stay_id = ?");
$stmt->execute([$stay_id]);
$amenities = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch images for this stay
$stmt = $pdo->prepare("SELECT url FROM images WHERE stay_id = ?");
$stmt->execute([$stay_id]);
$image_urls = $stmt->fetchAll(PDO::FETCH_COLUMN);

$img_placeholder = "https://media.istockphoto.com/id/1226328537/vector/image-place-holder-with-a-gray-camera-icon.jpg?s=612x612&w=0&k=20&c=qRydgCNlE44OUSSoz5XadsH7WCkU59-l-dwrvZzhXsI=";

if (empty($image_urls)) {
  // No images at all, fill all with placeholder
  $image_urls = array_fill(0, 5, $img_placeholder);
} else {
  // Fill up to 5 images, use placeholder for missing slots
  $count = count($image_urls);
  for ($i = $count; $i < 5; $i++) {
    $image_urls[] = $img_placeholder;
  }
}

$in_wishlist = false;
if ($user_id) {
  $stmt = $pdo->prepare("SELECT ws.id FROM wishlists w
        JOIN wishlist_stay ws ON w.id = ws.wishlist_id
        WHERE w.user_id = ? AND ws.stay_id = ?");
  $stmt->execute([$user_id, $stay_id]);
  $in_wishlist = (bool)$stmt->fetchColumn();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>StayWhere - Stay Details</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
</head>

<body>
  <!-- Include Header -->
  <div id="header"></div>
  <div class="flex">
    <!-- Left Side (75% width) -->
    <div class="w-3/4 ml-8">
      <div class="container mx-auto px-6 py-12">
        <!-- Title -->
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-4xl font-bold"><?= htmlspecialchars($stay['title']) ?></h1>
          <div class="flex space-x-4">
            <button id="shareBtn" class="bg-orange-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-orange-600 transition">
              <i class="fas fa-share-alt mr-2"></i> Share
            </button>
            <?php if (!$is_admin && !$is_host): ?>
              <button id="wishlistBtn" class="bg-white border border-orange-500 text-orange-500 px-4 py-2 rounded-lg shadow-md hover:bg-orange-100 transition">
                <i id='wl' class="<?= $in_wishlist ? 'fas fa-check mr-2' : 'fas fa-heart mr-2' ?>"></i>
                <span id="wishlistText"><?= $in_wishlist ? 'In Wishlist' : 'Add to Wishlist' ?></span>
              </button>
            <?php endif; ?>
          </div>
        </div>
        <!-- Photo Gallery -->
        <div class="mb-8">
          <div class="grid grid-cols-3 gap-4">
            <div class="col-span-1">
              <img src="<?= htmlspecialchars($image_urls[0]) ?>" alt="Stay Image 1" class="w-full h-96 object-cover rounded-lg">
            </div>
            <div class="col-span-1">
              <img src="<?= htmlspecialchars($image_urls[1] ?? $image_urls[0]) ?>" alt="Stay Image 2" class="w-full h-48 object-cover rounded-lg">
              <img src="<?= htmlspecialchars($image_urls[2] ?? $image_urls[0]) ?>" alt="Stay Image 3" class="w-full h-48 object-cover rounded-lg mt-4">
            </div>
            <div class="col-span-1">
              <img src="<?= htmlspecialchars($image_urls[3] ?? $image_urls[0]) ?>" alt="Stay Image 4" class="w-full h-48 object-cover rounded-lg">
              <img src="<?= htmlspecialchars($image_urls[4] ?? $image_urls[0]) ?>" alt="Stay Image 5" class="w-full h-48 object-cover rounded-lg mt-4">
            </div>
          </div>
          <div class="relative mt-4">
            <button id="showAllPhotos"
              class="absolute bottom-4 right-4 bg-white text-black px-4 py-2 rounded-lg shadow-md hover:bg-gray-100 transition">
              <i class="fas fa-images mr-2"></i>See All Photos
            </button>
          </div>
        </div>
        <!-- Modal for All Photos -->
        <div id="photoModal"
          class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-8">
          <div class="bg-white rounded-lg w-full max-w-4xl h-[80vh] overflow-hidden flex flex-col">
            <div class="p-4 border-b">
              <h2 class="text-xl font-bold">All Photos</h2>
              <button id="closeModal" class="absolute top-4 right-4 text-gray-600 hover:text-gray-900">
                <i class="fas fa-times"></i>
              </button>
            </div>
            <div class="overflow-y-auto p-4 grid grid-cols-2 md:grid-cols-3 gap-4">
              <?php foreach ($image_urls as $img): ?>
                <img src="<?= htmlspecialchars($img) ?>" alt="Stay Image" class="w-full h-48 object-cover rounded-lg">
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        <!-- Host Details -->
        <div class="bg-white p-6 rounded-lg shadow-lg mb-8">
          <div class="flex items-center space-x-5">
            <div class="flex-shrink-0 w-16 h-16">
              <a href="about-user.php?id=<?= $host['id'] ?>">
                <img src="<?= htmlspecialchars($host['profile_picture'] ?? 'uploads\profile_pics\no_pfp.jpg') ?>" alt="Host" class="w-16 h-16 rounded-full object-cover">
              </a>
            </div>
            <div>
              <h3 class="text-xl font-semibold">Hosted by <a href="about-user.php?id=<?= $host['id'] ?>"><?= htmlspecialchars($host['name']) ?></a></h3>
              <p class="text-gray-600"><?= htmlspecialchars($host['about_me'] ?? '') ?></p>
            </div>
          </div>
        </div>
        <!-- Location (Google Maps Embed) -->
        <div class="mb-8">
          <h2 class="text-2xl font-bold mb-4">Location</h2>
          <div class="rounded-lg overflow-hidden shadow-lg">
            <?= $stay['iframe_embed'] ?>
          </div>
        </div>
        <!-- Accommodation Description -->
        <div class="mb-8">
          <h2 class="text-2xl font-bold mb-4">About this stay</h2>
          <p class="text-gray-700"><?= nl2br(htmlspecialchars($stay['description'])) ?></p>
        </div>
        <!-- Available Facilities -->
        <div class="mb-8">
          <h2 class="text-2xl font-bold mb-4">Facilities</h2>
          <div class="grid grid-cols-2 gap-4">
            <?php foreach ($facilities as $facility): ?>
              <div class="flex items-center space-x-2">
                <i class="<?= htmlspecialchars($facility['icon_url']) ?> text-orange-500"></i>
                <span class="text-gray-700">
                  <?= htmlspecialchars($facility['count'] ? $facility['count'] . ' ' : '') . htmlspecialchars($facility['name']) ?>
                </span>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <!-- Offered Amenities -->
        <div class="mb-8">
          <h2 class="text-2xl font-bold mb-4">Amenities</h2>
          <div class="grid grid-cols-2 gap-4">
            <?php foreach ($amenities as $amenity): ?>
              <div class="flex items-center space-x-2">
                <i class="<?= htmlspecialchars($amenity['icon_url']) ?> text-orange-500"></i>
                <span class="text-gray-700"><?= htmlspecialchars($amenity['name']) ?></span>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
    <!-- Right Side (25% width) -->
    <div class="w-1/4 bg-gray-50 p-6">
      <!-- Reservation Section -->

      <div class="bg-white p-6 rounded-lg shadow-lg sticky top-6">
        <?php if ($is_admin || $is_host): ?>
          <div class="mb-4 text-center">
            <p class="text-xl font-bold">Price per night:</p>
            <p class="text-2xl text-orange-600 font-bold">$<?= number_format($stay['price_per_night'], 2) ?></p>
          </div>
        <?php elseif ($user_reservation): ?>
          <div class="mb-4">
            <p class="text-lg">
              Your reservation request is:
              <span class="font-bold capitalize"><?= htmlspecialchars($user_reservation['status']) ?></span>
            </p>
            <?php if ($user_reservation['status'] === 'pending'): ?>
              <form method="POST" onsubmit="return confirm('Are you sure you want to cancel this pending reservation?');">
                <input type="hidden" name="cancel_reservation" value="<?= $user_reservation['id'] ?>">
                <button type="submit" class="mt-4 bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition w-full">
                  Cancel Reservation
                </button>
              </form>
            <?php elseif ($user_reservation['status'] === 'declined'): ?>
              <form method="POST">
                <input type="hidden" name="cancel_reservation" value="<?= $user_reservation['id'] ?>">
                <button type="submit" class="mt-4 bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition w-full">
                  Cancel Reservation
                </button>
              </form>
            <?php elseif ($user_reservation['status'] === 'confirmed'): ?>
              <div class="mt-4 text-green-600 font-semibold">Enjoy your stay!</div>
            <?php endif; ?>
          </div>
        <?php elseif (!$stay['available']): ?>
          <div class="text-center text-red-500 font-bold text-xl py-8">
            This stay is unavailable.
          </div>
        <?php else: ?>
          <h2 class="text-2xl font-bold mb-4">Check Availability</h2>
          <form id="reservationForm" method="POST">
            <!-- Check-in Date -->
            <div class="mb-4">
              <label for="checkIn" class="block text-gray-700">Check-in Date</label>
              <input type="date" id="checkIn" name="checkIn" class="w-full p-2 border border-gray-300 rounded-lg" required>
            </div>
            <!-- Check-out Date -->
            <div class="mb-4">
              <label for="checkOut" class="block text-gray-700">Check-out Date</label>
              <input type="date" id="checkOut" name="checkOut" class="w-full p-2 border border-gray-300 rounded-lg" required>
            </div>
            <!-- Price Calculation -->
            <div class="mb-4">
              <p class="text-gray-700">Price: <span id="pricePerNight">$<?= number_format($stay['price_per_night'], 2) ?></span> per night</p>
              <p class="text-gray-700">Total: <span id="totalPrice">$0</span></p>
            </div>
            <!-- Book Button -->
            <button type="submit" id="bookButton"
              class="bg-orange-500 text-white px-6 py-3 rounded-lg hover:bg-orange-600 transition w-full disabled:opacity-50"
              disabled>
              Book Now
            </button>
          </form>
        <?php endif; ?>

        <?php if ($is_stay_host || $is_admin): ?>
          <div class="flex flex-col gap-3 mt-6">
            <?php if ($is_stay_host): ?>
              <a href="edit-stay.php?id=<?= $stay['id'] ?>"
                class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition font-semibold text-center">
                Edit Stay
              </a>
            <?php endif; ?>

            <?php if ($has_confirmed_reservation): ?>
              <button class="w-full bg-gray-400 text-white py-2 rounded-lg font-semibold cursor-not-allowed" disabled>
                Delete Stay
              </button>
              <div class="text-red-600 text-center text-sm mt-2">Cannot delete: confirmed reservations exist.</div>
            <?php else: ?>
              <form method="POST" onsubmit="return confirm('Are you sure you want to delete this stay? This cannot be undone.');">
                <input type="hidden" name="delete_stay" value="<?= $stay['id'] ?>">
                <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition font-semibold">
                  Delete Stay
                </button>
              </form>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Include Footer -->
  <div id="footer"></div>

  <?php include "headers/footer.php"; ?>
  <!-- JavaScript for Modal and Price Calculation -->
  <script>
    $(document).ready(function() {
      //Share Button
      $('#shareBtn').on('click', function() {
        const url = window.location.href;
        if (navigator.clipboard) {
          navigator.clipboard.writeText(url).then(function() {
            alert('Link copied :) !');
          }, function() {
            alert('Failed to copy link.');
          });
        } else {
          // Fallback for older browsers
          const tempInput = $('<input>');
          $('body').append(tempInput);
          tempInput.val(url).select();
          document.execCommand('copy');
          tempInput.remove();
          alert('Link copied :) !');
        }
      });
      // Show Modal
      $('#showAllPhotos').on('click', function() {
        $('#photoModal').removeClass('hidden');
      });
      // Close Modal
      $('#closeModal').on('click', function() {
        $('#photoModal').addClass('hidden');
      });
      const pricePerNight = <?= json_encode((float)$stay['price_per_night']) ?>;

      function calculateTotal() {
        const checkIn = new Date($('#checkIn').val());
        const checkOut = new Date($('#checkOut').val());
        if (checkIn && checkOut && checkOut > checkIn) {
          const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
          const total = nights * pricePerNight;
          $('#totalPrice').text(`$${total.toFixed(2)}`);
          $('#bookButton').prop('disabled', false);
        } else {
          $('#totalPrice').text('$0');
          $('#bookButton').prop('disabled', true);
        }
      }
      $('#checkIn, #checkOut').on('change', calculateTotal);
      $('#reservationForm').on('submit', function(e) {
        //e.preventDefault();
        <?php if (!$user_id): ?>
          alert('You must be logged in to book a stay.');
          return false;
        <?php endif ?>
      });

      $('#wishlistBtn').on('click', function() {
        <?php if (!$user_id): ?>
          alert('You must be logged in to use the wishlist.');
          return false;
        <?php else: ?>
          $.post('add-to-wishlist.php', {
            stay_id: <?= json_encode($stay_id) ?>
          }, function(response) {
            try {
              const data = JSON.parse(response);
              if (data.success) {
                $('#wl').attr('class', 'fas fa-check mr-2');
                $('#wishlistText').text('In Wishlist');
                alert('Added to your wishlist!');
              } else if (data.message === 'Already in wishlist.') {
                // If already in wishlist, remove it
                $.ajax({
                  url: 'remove-from-wishlist.php',
                  method: 'POST',
                  data: {
                    stay_id: <?= json_encode($stay_id) ?>
                  },
                  dataType: 'json',
                  success: function(removeData) {
                    if (removeData.success) {
                      $('#wl').attr('class', 'fas fa-heart mr-2');
                      $('#wishlistText').text('Add to Wishlist');
                      alert('Removed from your wishlist!');
                    } else {
                      alert(removeData.message || 'Error updating wishlist.');
                    }
                  },
                  error: function() {
                    alert('Unexpected error.');
                  }
                });
              } else {
                alert(data.message || 'Error updating wishlist.');
              }
            } catch (e) {
              alert('Unexpected error.');
            }
          });
          return false;
        <?php endif; ?>
      });
    });
  </script>
</body>

</html>