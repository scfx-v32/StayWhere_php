<?php
ob_start();
session_start();
require_once 'config.php';
include_once 'headers/header.php';


$user_id = $_SESSION['user']['id'] ?? null;
if (!$user_id) {
    header("Location: login.php");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_reservation'])) {
    $reservation_id = intval($_POST['cancel_reservation']);
    // Only allow cancel if user owns it and it's pending or not approved
    $stmt = $pdo->prepare("SELECT * FROM reservations WHERE id = ? AND user_id = ?");
    $stmt->execute([$reservation_id, $user_id]);
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($reservation && in_array($reservation['status'], ['pending', 'not approved'])) {
        $stmt = $pdo->prepare("UPDATE reservations SET status = 'cancelled' WHERE id = ?");
        $stmt->execute([$reservation_id]);
        echo "<script>alert('Reservation cancelled.');window.location='my-reservations.php';</script>";
        exit;
    }
}

// Delete cancelled reservation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_reservation'])) {
    $reservation_id = intval($_POST['delete_reservation']);
    // Only allow delete if user owns it and it's cancelled
    $stmt = $pdo->prepare("SELECT * FROM reservations WHERE id = ? AND user_id = ?");
    $stmt->execute([$reservation_id, $user_id]);
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($reservation && $reservation['status'] === 'cancelled') {
        $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
        $stmt->execute([$reservation_id]);
        echo "<script>alert('Reservation deleted.');window.location='my-reservations.php';</script>";
        exit;
    }
}

// Fetch reservations for this user, join with stays and get first image
$stmt = $pdo->prepare("
    SELECT r.*, s.title, s.location, s.price_per_night, s.id AS stay_id,
           (SELECT url FROM images WHERE stay_id = s.id LIMIT 1) AS image_url
    FROM reservations r
    JOIN stays s ON r.stay_id = s.id
    WHERE r.user_id = ?
    ORDER BY r.check_in DESC
");
$stmt->execute([$user_id]);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

ob_end_flush();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Reservations | StayWhere</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">My Reservations</h1>
        <?php if (empty($reservations)): ?>
            <div class="text-center text-gray-600">You have no reservations yet.</div>
            <br><br><br><br>
            <br><br><br><br>
            <br>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($reservations as $res): ?>
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <a href="staydetails.php?id=<?= $res['stay_id'] ?>">
                            <img src="<?= htmlspecialchars($res['image_url'] ?? 'https://picsum.photos/400/300') ?>" alt="Stay" class="w-full h-48 object-cover">
                        </a>
                        <div class="p-4">
                            <h2 class="text-xl font-semibold">
                                <a href="staydetails.php?id=<?= $res['stay_id'] ?>">
                                    <?= htmlspecialchars($res['title']) ?>
                                </a>
                            </h2>
                            <div class="mb-2">
                                <span class="font-semibold">Check-in:</span> <?= htmlspecialchars($res['check_in']) ?><br>
                                <span class="font-semibold">Check-out:</span> <?= htmlspecialchars($res['check_out']) ?>
                            </div>
                            <div class="text-gray-700">Total: $<?= number_format($res['total_price'], 2) ?></div>
                            <div class="text-gray-500 text-sm mt-2">Status: <?= htmlspecialchars($res['status']) ?></div>
                            <?php if (in_array($res['status'], ['pending', 'not approved'])): ?>
                                <form method="POST" class="mt-2"
                                    onsubmit="<?= $res['status'] === 'pending' ? "return confirm('Are you sure you want to cancel this pending reservation?');" : "" ?>">
                                    <input type="hidden" name="cancel_reservation" value="<?= $res['id'] ?>">
                                    <button type="submit"
                                        class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition w-full">
                                        Cancel Reservation
                                    </button>
                                </form>
                            <?php elseif ($res['status'] === 'confirmed'): ?>
                                <div class="mt-2 text-green-600 font-semibold">Enjoy your stay!</div>
                            <?php elseif ($res['status'] === 'cancelled'): ?>
                                <form method="POST" class="mt-2"
                                    onsubmit="return confirm('Delete this cancelled reservation permanently?');">
                                    <input type="hidden" name="delete_reservation" value="<?= $res['id'] ?>">
                                    <button type="submit"
                                        class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition w-full">
                                        Delete
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php include 'headers/footer.php'; ?>

</body>

</html>