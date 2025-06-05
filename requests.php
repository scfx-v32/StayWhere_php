<?php
session_start();
require_once 'config.php';

// Only hosts can access this page
if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'host') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];

// Handle approve/decline actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id'], $_POST['action'])) {
    $reservation_id = intval($_POST['reservation_id']);
    $action = $_POST['action'] === 'approve' ? 'confirmed' : 'declined';

    // Update reservation status
    $stmt = $pdo->prepare("
        UPDATE reservations r
        JOIN stays s ON r.stay_id = s.id
        SET r.status = ?
        WHERE r.id = ? AND s.user_id = ?
    ");
    $stmt->execute([$action, $reservation_id, $user_id]);

    // If approved, mark stay as unavailable for the reserved dates
    if ($action === 'confirmed') {
        // Get stay_id, check_in, check_out
        $stmt = $pdo->prepare("SELECT stay_id, check_in, check_out FROM reservations WHERE id = ?");
        $stmt->execute([$reservation_id]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            // Mark stay as unavailable
            $pdo->prepare("UPDATE stays SET available = 0 WHERE id = ?")->execute([$res['stay_id']]);

            // Decline all other pending reservations for this stay that overlap
            $pdo->prepare("
                UPDATE reservations
                SET status = 'declined'
                WHERE stay_id = ?
                  AND id != ?
                  AND status = 'pending'
                  AND NOT (check_out <= ? OR check_in >= ?)
            ")->execute([
                $res['stay_id'],
                $reservation_id,
                $res['check_in'],
                $res['check_out']
            ]);
        }
    }
}

// Fetch reservation requests for stays owned by this host
$stmt = $pdo->prepare("
    SELECT r.*, u.name AS guest_name, s.title AS stay_title
    FROM reservations r
    JOIN stays s ON r.stay_id = s.id
    JOIN users u ON r.user_id = u.id
    WHERE s.user_id = ?
    ORDER BY r.created_at DESC
");
$stmt->execute([$user_id]);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once 'headers/header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <title>Reservation Requests | StayWhere</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-10">
        <h1 class="text-3xl font-bold mb-8">Reservation Requests</h1>
        <?php if (empty($reservations)): ?>
            <div class="text-center text-gray-600">No reservation requests yet.</div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-xl shadow-lg">
                    <thead>
                        <tr>
                            <th class="py-3 px-4 border-b">Stay</th>
                            <th class="py-3 px-4 border-b">Guest</th>
                            <th class="py-3 px-4 border-b">Check-In</th>
                            <th class="py-3 px-4 border-b">Check-Out</th>
                            <th class="py-3 px-4 border-b">Total Price</th>
                            <th class="py-3 px-4 border-b">Status</th>
                            <th class="py-3 px-4 border-b">Requested At</th>
                            <th class="py-3 px-4 border-b">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservations as $res): ?>
                            <tr class="hover:bg-gray-100">
                                <td class="py-2 px-4 border-b">
                                    <a href="staydetails.php?id=<?= urlencode($res['stay_id']) ?>" class="text-blue-600 hover:underline">
                                        <?= htmlspecialchars($res['stay_title']) ?>
                                    </a>
                                </td>
                                <td class="py-2 px-4 border-b">
                                    <a href="about-user.php?id=<?= urlencode($res['user_id']) ?>" class="text-blue-600 hover:underline">
                                        <?= htmlspecialchars($res['guest_name']) ?>
                                    </a>
                                </td>
                                <td class="py-2 px-4 border-b"><?= htmlspecialchars($res['check_in']) ?></td>
                                <td class="py-2 px-4 border-b"><?= htmlspecialchars($res['check_out']) ?></td>
                                <td class="py-2 px-4 border-b">$<?= number_format($res['total_price'], 2) ?></td>
                                <td class="py-2 px-4 border-b">
                                    <?php if ($res['status'] === 'pending'): ?>
                                        <span class="text-yellow-600 font-semibold">Pending</span>
                                    <?php elseif ($res['status'] === 'confirmed'): ?>
                                        <span class="text-green-600 font-semibold">Approved</span>
                                    <?php elseif ($res['status'] === 'cancelled'): ?>
                                        <span class="text-gray-600 font-semibold">Cancelled</span>
                                    <?php else: ?>
                                        <span class="text-red-600 font-semibold">Declined</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-2 px-4 border-b"><?= htmlspecialchars($res['created_at']) ?></td>
                                <td class="py-2 px-4 border-b">
                                    <?php if ($res['status'] === 'pending'): ?>
                                        <form method="POST" class="flex gap-2">
                                            <input type="hidden" name="reservation_id" value="<?= $res['id'] ?>">
                                            <button type="submit" name="action" value="approve" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 transition">Approve</button>
                                            <button type="submit" name="action" value="decline" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">Decline</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-gray-400">No action</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>