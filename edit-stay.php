<?php

session_start();
require_once 'config.php';

// Only hosts can access
if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'host') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];
$errors = [];
$success = "";

// Get stay ID
$stay_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$stay_id) {
    die("Invalid stay ID.");
}

// Fetch stay data
$stmt = $pdo->prepare("SELECT * FROM stays WHERE id = ? AND user_id = ?");
$stmt->execute([$stay_id, $user_id]);
$stay = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$stay) {
    die("Stay not found or you do not have permission to edit this stay.");
}

// Check if stay is part of a confirmed reservation
$stmt = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE stay_id = ? AND status = 'confirmed'");
$stmt->execute([$stay_id]);
$has_confirmed = $stmt->fetchColumn();

if ($has_confirmed) {
    echo "<script>
        if (confirm('Cannot edit stay because it is reserved.\\nPress OK to go back or Cancel to stay on this page.')) {
            window.history.back();
        }
    </script>";
    exit;
}

// Fetch all available amenities and facilities for selection (with icons)
$amenities = $pdo->query("SELECT id, name, icon_url FROM amenities ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$facilities = $pdo->query("SELECT id, name, icon_url FROM facilities ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

// Fetch selected amenities and facilities for this stay
$selected_amenities = $pdo->query("SELECT amenity_id FROM stay_amenity WHERE stay_id = $stay_id")->fetchAll(PDO::FETCH_COLUMN);
$selected_facilities = $pdo->query("SELECT facility_id, count FROM stay_facility WHERE stay_id = $stay_id")->fetchAll(PDO::FETCH_KEY_PAIR);

// Fetch images for this stay
$images = $pdo->prepare("SELECT id, url FROM images WHERE stay_id = ?");
$images->execute([$stay_id]);
$images = $images->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $price_per_night = floatval($_POST['price_per_night'] ?? 0);
    $max_guests = intval($_POST['max_guests'] ?? 1);
    $description = trim($_POST['description'] ?? '');
    $map_url = trim($_POST['map_url'] ?? '');
    $iframe_embed = trim($_POST['iframe_embed'] ?? '');
    $available = isset($_POST['available']) ? 1 : 0;

    // Sanitize and enforce iframe width and height
    if ($iframe_embed) {
        $iframe_embed = preg_replace('/width="\d+"/i', 'width="100%"', $iframe_embed);
        $iframe_embed = preg_replace('/height="\d+"/i', 'height="400"', $iframe_embed);
        if (!preg_match('/width="/i', $iframe_embed)) {
            $iframe_embed = preg_replace('/<iframe/i', '<iframe width="100%"', $iframe_embed);
        }
        if (!preg_match('/height="/i', $iframe_embed)) {
            $iframe_embed = preg_replace('/<iframe/i', '<iframe height="400"', $iframe_embed);
        }
    }

    // Validate required fields
    if (!$title || !$location || !$price_per_night || !$max_guests || !$description || !$map_url || !$iframe_embed) {
        $errors[] = "All fields are required.";
    }

    if (empty($errors)) {
        // Update stay
        $stmt = $pdo->prepare("UPDATE stays SET title=?, location=?, price_per_night=?, max_guests=?, description=?, map_url=?, iframe_embed=?, available=? WHERE id=? AND user_id=?");
        $stmt->execute([$title, $location, $price_per_night, $max_guests, $description, $map_url, $iframe_embed, $available, $stay_id, $user_id]);

        // Update amenities
        $pdo->prepare("DELETE FROM stay_amenity WHERE stay_id=?")->execute([$stay_id]);
        if (!empty($_POST['amenities'])) {
            foreach ($_POST['amenities'] as $amenity_id) {
                $stmt = $pdo->prepare("INSERT INTO stay_amenity (stay_id, amenity_id) VALUES (?, ?)");
                $stmt->execute([$stay_id, $amenity_id]);
            }
        }

        // Update facilities with count
        $pdo->prepare("DELETE FROM stay_facility WHERE stay_id=?")->execute([$stay_id]);
        if (!empty($_POST['facilities']) && !empty($_POST['facility_count'])) {
            foreach ($_POST['facilities'] as $facility_id) {
                $count = intval($_POST['facility_count'][$facility_id] ?? 1);
                $stmt = $pdo->prepare("INSERT INTO stay_facility (stay_id, facility_id, count) VALUES (?, ?, ?)");
                $stmt->execute([$stay_id, $facility_id, $count]);
            }
        }

        // Handle new images
        if (!empty($_FILES['new_images']['name'][0])) {
            $upload_dir = "uploads/stay_images/";
            if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);

            foreach ($_FILES['new_images']['tmp_name'] as $key => $tmp_name) {
                $file_name = basename($_FILES['new_images']['name'][$key]);
                $target_file = $upload_dir . uniqid('stay_' . $stay_id . '_') . "_" . $file_name;
                if (move_uploaded_file($tmp_name, $target_file)) {
                    $stmt = $pdo->prepare("INSERT INTO images (stay_id, url) VALUES (?, ?)");
                    $stmt->execute([$stay_id, $target_file]);
                }
            }
        }

        $success = "Stay updated successfully!";
        // Refresh data after update
        $stmt = $pdo->prepare("SELECT * FROM stays WHERE id = ? AND user_id = ?");
        $stmt->execute([$stay_id, $user_id]);
        $stay = $stmt->fetch(PDO::FETCH_ASSOC);
        $selected_amenities = $pdo->query("SELECT amenity_id FROM stay_amenity WHERE stay_id = $stay_id")->fetchAll(PDO::FETCH_COLUMN);
        $selected_facilities = $pdo->query("SELECT facility_id, count FROM stay_facility WHERE stay_id = $stay_id")->fetchAll(PDO::FETCH_KEY_PAIR);
        $images = $pdo->prepare("SELECT id, url FROM images WHERE stay_id = ?");
        $images->execute([$stay_id]);
        $images = $images->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Handle image deletion (AJAX or GET)
if (isset($_GET['delete_image']) && is_numeric($_GET['delete_image'])) {
    $img_id = intval($_GET['delete_image']);
    $img = $pdo->prepare("SELECT url FROM images WHERE id = ? AND stay_id = ?");
    $img->execute([$img_id, $stay_id]);
    $img_url = $img->fetchColumn();
    if ($img_url) {
        @unlink($img_url);
        $pdo->prepare("DELETE FROM images WHERE id = ? AND stay_id = ?")->execute([$img_id, $stay_id]);
        header("Location: edit-stay.php?id=$stay_id");
        exit;
    }
}


include_once 'headers/header.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <title>Edit Stay | StayWhere</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-10 max-w-6xl">
        <h1 class="text-3xl font-bold mb-6">Edit Stay</h1>
        <?php if ($errors): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                <?= implode('<br>', $errors) ?>
            </div>
        <?php elseif ($success): ?>
            <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
                <?= $success ?>
            </div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-xl shadow-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-5">
                    <!-- Image Upload Section -->
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-orange-400 transition-colors relative">
                        <!-- Existing Images -->
                        <div id="existing-images" class="grid grid-cols-3 gap-2 mb-4">
                            <?php foreach ($images as $image): ?>
                                <div class="relative group">
                                    <img src="<?= htmlspecialchars($image['url']) ?>" class="h-24 w-full object-cover rounded-lg border">
                                    <a href="?id=<?= $stay_id ?>&delete_image=<?= $image['id'] ?>" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity">×</a>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Upload Area -->
                        <label class="cursor-pointer block">
                            <div class="flex flex-col items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="mt-2 font-medium">Drag & drop images or click to browse</p>
                                <p class="text-sm text-gray-500">PNG, JPG, JPEG (Max 5MB each)</p>
                            </div>
                            <input type="file" name="new_images[]" accept="image/*" multiple class="hidden" id="image-upload">
                        </label>

                        <!-- + Button -->
                        <button type="button" class="absolute bottom-2 right-2 bg-orange-500 text-white rounded-full w-10 h-10 flex items-center justify-center hover:bg-orange-600 transition" id="add-more-images">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </button>

                        <!-- New Image Previews -->
                        <div id="new-image-previews" class="mt-4 grid grid-cols-3 gap-2"></div>
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">Title</label>
                        <input type="text" name="title" value="<?= htmlspecialchars($stay['title']) ?>" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-orange-200 focus:border-orange-400 transition" required>
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">Location</label>
                        <input type="text" name="location" value="<?= htmlspecialchars($stay['location']) ?>" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-orange-200 focus:border-orange-400 transition" required>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="block font-semibold mb-1">Price per Night ($)</label>
                            <input type="number" name="price_per_night" min="1" step="0.01" value="<?= htmlspecialchars($stay['price_per_night']) ?>" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-orange-200 focus:border-orange-400 transition" required>
                        </div>
                        <div class="flex-1">
                            <label class="block font-semibold mb-1">Max Guests</label>
                            <input type="number" name="max_guests" min="1" value="<?= htmlspecialchars($stay['max_guests']) ?>" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-orange-200 focus:border-orange-400 transition" required>
                        </div>
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">Description</label>
                        <textarea name="description" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-orange-200 focus:border-orange-400 transition" rows="4" required><?= htmlspecialchars($stay['description']) ?></textarea>
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">Google Maps Link</label>
                        <input type="text" name="map_url" value="<?= htmlspecialchars($stay['map_url']) ?>" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-orange-200 focus:border-orange-400 transition" required>
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">Map Embed (iframe)</label>
                        <input type="text" name="iframe_embed" value="<?= htmlspecialchars($stay['iframe_embed']) ?>" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-orange-200 focus:border-orange-400 transition" required>
                        <div class="text-sm text-gray-500 mt-2">
                            <p>How to get the map iframe?</p>
                            <div class="mt-2">
                                <img src="images/tutorials/copy-iframe.gif" alt="Copy iframe tutorial" class="w-full border rounded-lg shadow">
                            </div>
                            <p class="mt-2">On Google Maps: Share → Embed a map → Copy HTML iframe code and paste here.</p>
                        </div>
                    </div>
                </div>
                <!-- Right Column -->
                <div class="space-y-5">
                    <div>
                        <label class="block font-semibold mb-1">Amenities</label>
                        <div class="grid grid-cols-2 gap-3">
                            <?php foreach ($amenities as $amenity): ?>
                                <label class="flex items-center gap-3 p-2 hover:bg-orange-50 rounded-lg transition">
                                    <i class="<?= htmlspecialchars($amenity['icon_url']) ?> text-orange-500 text-lg"></i>
                                    <input type="checkbox" name="amenities[]" value="<?= $amenity['id'] ?>" class="rounded text-orange-500 focus:ring-orange-400"
                                        <?= in_array($amenity['id'], $selected_amenities) ? 'checked' : '' ?>>
                                    <span><?= htmlspecialchars($amenity['name']) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">Facilities (with count)</label>
                        <div class="grid grid-cols-2 gap-3">
                            <?php foreach ($facilities as $facility): ?>
                                <div class="flex items-center gap-3 p-2 hover:bg-orange-50 rounded-lg transition">
                                    <i class="<?= htmlspecialchars($facility['icon_url']) ?> text-gray-500"></i>
                                    <input type="checkbox" name="facilities[]" value="<?= $facility['id'] ?>" id="facility-<?= $facility['id'] ?>" class="rounded text-orange-500 focus:ring-orange-400"
                                        <?= array_key_exists($facility['id'], $selected_facilities) ? 'checked' : '' ?>>
                                    <label for="facility-<?= $facility['id'] ?>" class="flex-1"><?= htmlspecialchars($facility['name']) ?></label>
                                    <input type="number" name="facility_count[<?= $facility['id'] ?>]" min="1" value="<?= htmlspecialchars($selected_facilities[$facility['id']] ?? 1) ?>" class="w-16 border rounded-lg p-2 focus:ring-2 focus:ring-orange-200 focus:border-orange-400 transition">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="pt-4">
                        <label class="inline-flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                            <input type="checkbox" name="available" value="1" <?= $stay['available'] ? 'checked' : '' ?> class="rounded text-orange-500 focus:ring-orange-400">
                            <span class="font-medium">Available for booking</span>
                        </label>
                    </div>
                </div>
            </div>
            <!-- Submit Button - Full width below columns -->
            <div class="mt-8">
                <button type="submit" class="w-full bg-orange-500 text-white py-3 rounded-lg font-semibold hover:bg-orange-600 transition focus:outline-none focus:ring-2 focus:ring-orange-400 focus:ring-offset-2">
                    Update Stay
                </button>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const imageUpload = $('#image-upload');
            const addMoreBtn = $('#add-more-images');
            const newImagePreviews = $('#new-image-previews');
            let allFiles = [];

            // Handle removal of new image previews
            newImagePreviews.on('click', '.remove-new-image', function() {
                const idx = $(this).parent().index();
                allFiles.splice(idx, 1);
                $(this).parent().remove();
            });

            // Function to handle file selection and preview
            function handleFiles(files) {
                for (let i = 0; i < files.length; i++) {
                    allFiles.push(files[i]);
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = $(`
                    <div class="relative group">
                        <img src="${e.target.result}" class="h-24 w-full object-cover rounded-lg border">
                        <button type="button" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity remove-new-image">×</button>
                    </div>
                `);
                        newImagePreviews.append(preview);
                    };
                    reader.readAsDataURL(files[i]);
                }
            }

            // Main upload handler
            imageUpload.on('change', function() {
                handleFiles(this.files);
                imageUpload.val(''); // Clear so same file can be re-added if needed
            });

            // Add more images button
            addMoreBtn.on('click', function() {
                imageUpload.click();
            });

            // On form submit, set allFiles to the file input
            $('form').on('submit', function(e) {
                if (allFiles.length > 0) {
                    const dt = new DataTransfer();
                    allFiles.forEach(file => dt.items.add(file));
                    imageUpload[0].files = dt.files;
                }
            });
        });
    </script>
</body>

</html>