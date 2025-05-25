<?php
require_once 'config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user']['id'])) {
    header("Location: login.php");
    exit();
}

// Include header after session check
include_once 'headers/header.php';

try {
    // Get user's wishlist stays
    $stmt = $pdo->prepare("
        SELECT s.* 
        FROM stays s
        JOIN wishlist_stay ws ON s.id = ws.stay_id
        JOIN wishlists w ON ws.wishlist_id = w.id
        WHERE w.user_id = ?
    ");
    $stmt->execute([$_SESSION['user']['id']]);
    $wishlistStays = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get first image for each stay
    foreach ($wishlistStays as &$stay) {
        $imgStmt = $pdo->prepare("SELECT url FROM images WHERE stay_id = ? LIMIT 1");
        $imgStmt->execute([$stay['id']]);
        $stay['image'] = $imgStmt->fetchColumn();
    }
    unset($stay); // Break the reference

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Wishlist | StayWhere</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Your Wishlist</h1>

        <?php if (empty($wishlistStays)): ?>
            <div class="text-center py-12">
                <i class="fas fa-heart text-4xl text-gray-300 mb-4"></i>
                <p class="text-xl text-gray-600">Your wishlist is empty</p>
                <a href="index.php" class="mt-4 inline-block bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
                    Browse Stays
                </a>
            </div>
        <?php else: ?>
            <div id="wishlist-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($wishlistStays as $stay): ?>
                    <div class="wishlist-card bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                        <a href="staydetails.php?id=<?= $stay['id'] ?>">
                            <div class="relative h-48 overflow-hidden">
                                <img src="<?= $stay['image'] ?? 'https://picsum.photos/400/300?random=' . $stay['id'] ?>"
                                    alt="<?= htmlspecialchars($stay['title']) ?>"
                                    class="w-full h-full object-cover">
                                <!-- Change the remove button in your stay card to this -->
                                <button class="remove-wishlist-btn absolute top-2 right-2 bg-white p-2 rounded-full shadow-md text-red-500 hover:text-red-600"
                                    data-stay-id="<?= $stay['id'] ?>">
                                    <i class="fas fa-heart text-orange-500"></i>
                                </button>
                            </div>
                            <div class="p-4">
                                <h3 class="text-lg font-semibold mb-1"><?= htmlspecialchars($stay['title']) ?></h3>
                                <p class="text-gray-600 mb-2"><?= htmlspecialchars($stay['location']) ?></p>
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-lg">$<?= number_format($stay['price_per_night'], 2) ?>/night</span>
                                    <span class="text-sm bg-gray-100 px-2 py-1 rounded">
                                        <i class="fas fa-users mr-1"></i> <?= $stay['max_guests'] ?> guests
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- In wishlist.php, replace the existing script with this jQuery version -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.remove-wishlist-btn').on('click', function(e) {
                e.preventDefault();
                const stayId = $(this).data('stay-id');
                const $card = $(this).closest('.wishlist-card');

                if (confirm('Remove this stay from your wishlist?')) {
                    $.ajax({
                        url: 'remove-from-wishlist.php',
                        method: 'POST',
                        data: {
                            stay_id: stayId
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                $card.fadeOut(300, function() {
                                    $(this).remove();
                                    // Show empty state if no items left
                                    if ($('.wishlist-card').length === 0) {
                                        $('#wishlist-container').replaceWith(`
                                    <div class="text-center py-12">
                                        <i class="fas fa-heart text-4xl text-gray-300 mb-4"></i>
                                        <p class="text-xl text-gray-600">Your wishlist is empty</p>
                                        <a href="index.php" class="mt-4 inline-block bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
                                            Browse Stays
                                        </a>
                                    </div>
                                `);
                                    }
                                });
                            } else {
                                alert(response.message || 'Error removing from wishlist');
                            }
                        },
                        error: function(xhr, status, error) {
                            alert('An error occurred: ' + error);
                        }
                    });
                }
            });
        });
    </script>

    <?php include_once 'headers/footer.php'; ?>
</body>

</html>