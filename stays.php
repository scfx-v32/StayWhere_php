<?php
session_start();
require_once 'config.php';

// Only admin can access
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Handle delete action
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    // Check for confirmed reservation
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE stay_id = ? AND status = 'confirmed'");
    $stmt->execute([$delete_id]);
    $has_confirmed = $stmt->fetchColumn() > 0;

    if ($has_confirmed) {
        header("Location: stays.php?error=confirmed");
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM stays WHERE id = ?");
    $stmt->execute([$delete_id]);
    header("Location: stays.php");
    exit;
}

// Fetch stays with host info
$stmt = $pdo->query("
    SELECT s.id, u.name AS host_name, s.title, s.location, s.map_url, s.price_per_night, s.max_guests, s.created_at AS added, s.available,
        EXISTS (
            SELECT 1 FROM reservations r WHERE r.stay_id = s.id AND r.status = 'confirmed'
        ) AS has_confirmed_reservation
    FROM stays s
    JOIN users u ON s.user_id = u.id
    ORDER BY s.created_at DESC
");
$stays = $stmt->fetchAll(PDO::FETCH_ASSOC);


include_once 'headers/header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <title>Stays | StayWhere</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-10">
        <h1 class="text-3xl font-bold mb-8">Stays</h1>
        <?php if (isset($_GET['error']) && $_GET['error'] === 'confirmed'): ?>
            <div class="mb-4 text-red-600 font-semibold">You cannot delete a stay with confirmed reservations.</div>
        <?php endif; ?> <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-xl shadow-lg">
                <thead>
                    <tr>
                        <th class="py-3 px-4 border-b">ID</th>
                        <th class="py-3 px-4 border-b">Hosted By</th>
                        <th class="py-3 px-4 border-b">Title</th>
                        <th class="py-3 px-4 border-b">Location</th>
                        <th class="py-3 px-4 border-b">Map URL</th>
                        <th class="py-3 px-4 border-b">Price/Night</th>
                        <th class="py-3 px-4 border-b">Max Guests</th>
                        <th class="py-3 px-4 border-b">Added</th>
                        <th class="py-3 px-4 border-b">Available</th>
                        <th class="py-3 px-4 border-b">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stays as $stay): ?>
                        <tr class="hover:bg-gray-100">
                            <td class="py-2 px-4 border-b"><?= $stay['id'] ?></td>
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($stay['host_name']) ?></td>
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($stay['title']) ?></td>
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($stay['location']) ?></td>
                            <td class="py-2 px-4 border-b">
                                <a href="<?= htmlspecialchars($stay['map_url']) ?>" target="_blank" class="text-blue-600 hover:underline">View Map</a>
                            </td>
                            <td class="py-2 px-4 border-b">$<?= number_format($stay['price_per_night'], 2) ?></td>
                            <td class="py-2 px-4 border-b"><?= $stay['max_guests'] ?></td>
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($stay['added']) ?></td>
                            <td class="py-2 px-4 border-b">
                                <?php if ($stay['available']): ?>
                                    <span class="text-green-600 font-semibold">Yes</span>
                                <?php else: ?>
                                    <span class="text-red-600 font-semibold">No</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-2 px-4 border-b">
                                <a href="staydetails.php?id=<?= $stay['id'] ?>" class="text-blue-600 hover:underline mr-3">View</a>
                                <?php if ($stay['has_confirmed_reservation']): ?>
                                    <span class="text-gray-400 cursor-not-allowed" title="Cannot delete: confirmed reservations exist.">Delete</span>
                                <?php else: ?>
                                    <a href="stays.php?delete=<?= $stay['id'] ?>" class="text-red-600 hover:underline"
                                        onclick="return confirm('Are you sure you want to delete this stay?');">Delete</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($stays)): ?>
                        <tr>
                            <td colspan="10" class="text-center py-6 text-gray-500">No stays found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>