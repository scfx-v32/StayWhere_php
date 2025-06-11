<?php
ob_start();
session_start();
require_once 'config.php';

// Only hosts can access
if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'host') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];

// Fetch stays for this host
$stmt = $pdo->prepare("
    SELECT s.*, 
        (SELECT url FROM images WHERE stay_id = s.id ORDER BY id ASC LIMIT 1) AS main_image,
        EXISTS (
            SELECT 1 FROM reservations r 
            WHERE r.stay_id = s.id AND r.status = 'confirmed'
        ) AS has_confirmed_reservation
    FROM stays s
    WHERE s.user_id = ?
    ORDER BY s.created_at DESC
");
$stmt->execute([$user_id]);
$stays = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle delete action
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    // Check for confirmed reservation
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE stay_id = ? AND status = 'confirmed'");
    $stmt->execute([$delete_id]);
    $has_confirmed = $stmt->fetchColumn() > 0;

    if ($has_confirmed) {
        ob_end_flush();
        header("Location: my-stays.php?error=confirmed");
        exit;
    }
    $stmt = $pdo->prepare("DELETE FROM stays WHERE id = ? AND user_id = ?");
    $stmt->execute([$delete_id, $user_id]);
    ob_end_flush();
    header("Location: my-stays.php");
    exit;
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <title>My Stays | StayWhere</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen">

    <?php include_once 'headers/header.php'; ?>


    <div class="container mx-auto px-4 py-10">
        <h1 class="text-3xl font-bold mb-8">My Stays</h1>
        <?php if (isset($_GET['error']) && $_GET['error'] === 'confirmed'): ?>
            <div class="mb-4 text-red-600 font-semibold">You cannot delete a stay with confirmed reservations.</div>
        <?php endif; ?>
        <?php if (empty($stays)): ?>
            <div class="text-center text-gray-600">You have not added any stays yet.</div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($stays as $stay): ?>
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col">
                        <a href="staydetails.php?id=<?= $stay['id'] ?>" class="block hover:opacity-90 transition">
                            <img src="<?= htmlspecialchars($stay['main_image'] ?? 'https://placehold.co/400x300?text=No+Image') ?>"
                                alt="<?= htmlspecialchars($stay['title']) ?>"
                                class="w-full h-48 object-cover">
                        </a>
                        <div class="p-4 flex-1 flex flex-col">
                            <a href="staydetails.php?id=<?= $stay['id'] ?>" class="text-xl font-semibold hover:underline mb-1"><?= htmlspecialchars($stay['title']) ?></a>
                            <div class="text-gray-600 mb-2"><?= htmlspecialchars($stay['location']) ?></div>
                            <div class="text-gray-700 font-bold mb-2">$<?= number_format($stay['price_per_night'], 2) ?>/night</div>
                            <div class="mt-auto flex gap-2">
                                <a href="edit-stay.php?id=<?= $stay['id'] ?>"
                                    class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg text-center hover:bg-blue-700 transition font-semibold">
                                    Edit
                                </a>
                                <?php if ($stay['has_confirmed_reservation']): ?>
                                    <button class="flex-1 bg-gray-400 text-white px-4 py-2 rounded-lg text-center font-semibold cursor-not-allowed" disabled>
                                        Delete
                                    </button>
                                <?php else: ?>
                                    <a href="my-stays.php?delete=<?= $stay['id'] ?>"
                                        class="flex-1 bg-red-600 text-white px-4 py-2 rounded-lg text-center hover:bg-red-700 transition font-semibold"
                                        onclick="return confirm('Are you sure you want to delete this stay?');">
                                        Delete
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>