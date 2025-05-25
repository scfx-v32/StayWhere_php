<?php

require 'config.php';
include 'headers/header.php';

// Get search parameters
$location = $_GET['location'] ?? '';
$check_in = $_GET['check_in'] ?? '';
$check_out = $_GET['check_out'] ?? '';
$guests = $_GET['guests'] ?? '';

// Build query
$sql = "
    SELECT s.*, 
           (SELECT url FROM images WHERE stay_id = s.id LIMIT 1) as image_url
    FROM stays s
    WHERE s.available = 1
";
$params = [];

if ($location) {
    $sql .= " AND (s.location LIKE ? OR s.title LIKE ?)";
    $params[] = "%$location%";
    $params[] = "%$location%";
}
if ($guests) {
    $sql .= " AND s.max_guests >= ?";
    $params[] = $guests;
}

// Optionally, you can add date availability logic here

$sql .= " ORDER BY s.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$stays = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Search Results | StayWhere</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
<!-- search inputs -->
<div class="container mx-auto px-6 py-12">
    <form method="get" action="search.php" class="bg-white p-6 rounded-xl shadow-lg flex flex-col md:flex-row gap-4">
        <input type="text" name="location" placeholder="Destination" value="<?= htmlspecialchars($location) ?>"
            class="flex-1 p-3 border border-gray-300 rounded-xl">
        <input type="date" name="check_in" value="<?= htmlspecialchars($check_in) ?>"
            class="p-3 border border-gray-300 rounded-xl">
        <input type="date" name="check_out" value="<?= htmlspecialchars($check_out) ?>"
            class="p-3 border border-gray-300 rounded-xl">
        <input type="number" min="1" name="guests" placeholder="Guests" value="<?= htmlspecialchars($guests) ?>"
            class="p-3 border border-gray-300 rounded-xl" max="<?= max(array_column($stays, 'max_guests')) ?>">
        <button type="submit"
            class="bg-orange-500 text-white px-6 py-3 rounded-xl hover:bg-orange-600 transition">Search</button>
    </form>
</div>

    <div class="container mx-auto px-6 py-12">
        <h2 class="text-3xl font-bold text-center mb-8">Search Results</h2>
        <?php if (empty($stays)): ?>
            <div class="text-center text-gray-600">No stays found for your search.</div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($stays as $stay): ?>
                    <a href="staydetails.php?id=<?= $stay['id'] ?>" class="bg-white rounded-xl shadow-lg overflow-hidden block hover:shadow-xl transition">
                        <img src="<?= $stay['image_url'] ? $stay['image_url'] : 'https://placehold.co/400x300' . $stay['id'] ?>"
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
        <?php endif; ?>
    </div>

    <?php include 'headers/footer.php'; ?>
</body>

</html>