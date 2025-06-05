<?php
session_start();
require_once 'config.php';
include_once 'headers/header.php';

// Only hosts can access
if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'host') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];
$errors = [];
$success = "";

// Fetch all available amenities and facilities for selection (with icons)
$amenities = $pdo->query("SELECT id, name, icon_url FROM amenities ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$facilities = $pdo->query("SELECT id, name, icon_url FROM facilities ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $price_per_night = floatval($_POST['price_per_night'] ?? 0);
    $max_guests = intval($_POST['max_guests'] ?? 1);
    $description = trim($_POST['description'] ?? '');
    $map_url = trim($_POST['map_url'] ?? '');
    $iframe_embed = trim($_POST['iframe_embed'] ?? '');

    // Sanitize and enforce iframe width and height
    if ($iframe_embed) {
        // Replace width and height attributes (works for most Google Maps iframes)
        $iframe_embed = preg_replace('/width="\d+"/i', 'width="100%"', $iframe_embed);
        $iframe_embed = preg_replace('/height="\d+"/i', 'height="400"', $iframe_embed);

        // If width/height not present, add them (optional, for extra safety)
        if (!preg_match('/width="/i', $iframe_embed)) {
            $iframe_embed = preg_replace('/<iframe/i', '<iframe width="100%"', $iframe_embed);
        }
        if (!preg_match('/height="/i', $iframe_embed)) {
            $iframe_embed = preg_replace('/<iframe/i', '<iframe height="400"', $iframe_embed);
        }
    }
    $available = isset($_POST['available']) ? 1 : 0;

    // Validate required fields
    if (!$title || !$location || !$price_per_night || !$max_guests || !$description || !$map_url || !$iframe_embed) {
        $errors[] = "All fields are required.";
    }

    // Insert stay
    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO stays (user_id, title, location, price_per_night, max_guests, description, map_url, iframe_embed, available, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$user_id, $title, $location, $price_per_night, $max_guests, $description, $map_url, $iframe_embed, $available]);
        $stay_id = $pdo->lastInsertId();

        // Handle amenities
        if (!empty($_POST['amenities'])) {
            foreach ($_POST['amenities'] as $amenity_id) {
                $stmt = $pdo->prepare("INSERT INTO stay_amenity (stay_id, amenity_id) VALUES (?, ?)");
                $stmt->execute([$stay_id, $amenity_id]);
            }
        }

        // Handle facilities with count
        if (!empty($_POST['facilities']) && !empty($_POST['facility_count'])) {
            foreach ($_POST['facilities'] as $facility_id) {
                $count = intval($_POST['facility_count'][$facility_id] ?? 1);
                $stmt = $pdo->prepare("INSERT INTO stay_facility (stay_id, facility_id, count) VALUES (?, ?, ?)");
                $stmt->execute([$stay_id, $facility_id, $count]);
            }
        }

        // Handle images
        if (!empty($_FILES['images']['name'][0])) {
            $upload_dir = "uploads/stay_images/";
            if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);

            foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                $file_name = basename($_FILES['images']['name'][$key]);
                $target_file = $upload_dir . uniqid('stay_' . $stay_id . '_') . "_" . $file_name;
                if (move_uploaded_file($tmp_name, $target_file)) {
                    $stmt = $pdo->prepare("INSERT INTO images (stay_id, url) VALUES (?, ?)");
                    $stmt->execute([$stay_id, $target_file]);
                }
            }
        }

        $success = "Stay added successfully!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
  <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <title>Add Stay | StayWhere</title>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-10 max-w-6xl"> <!-- Changed max-w-3xl to max-w-6xl for wider layout -->
        <h1 class="text-3xl font-bold mb-6">Add a New Stay</h1>
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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8"> <!-- Two-column grid -->
                <!-- Left Column -->
                <div class="space-y-5">
                    <!-- Image Upload Section -->
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-orange-400 transition-colors relative">
                        <!-- Main Upload Area -->
                        <label class="cursor-pointer block">
                            <div class="flex flex-col items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="mt-2 font-medium">Drag & drop images or click to browse</p>
                                <p class="text-sm text-gray-500">PNG, JPG, JPEG (Max 5MB each)</p>
                            </div>
                            <input type="file" name="images[]" accept="image/*" multiple class="hidden" id="image-upload">
                        </label>

                        <!-- Persistent + Button -->
                        <button type="button" class="absolute bottom-2 right-2 bg-orange-500 text-white rounded-full w-10 h-10 flex items-center justify-center hover:bg-orange-600 transition" id="add-more-images">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </button>

                        <!-- Image Previews -->
                        <div id="image-preview" class="mt-4 grid grid-cols-3 gap-2 hidden"></div>
                    </div>

                    <div>
                        <label class="block font-semibold mb-1">Title</label>
                        <input type="text" name="title" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-orange-200 focus:border-orange-400 transition" required>
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">Location</label>
                        <input type="text" name="location" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-orange-200 focus:border-orange-400 transition" required>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="block font-semibold mb-1">Price per Night ($)</label>
                            <input type="number" name="price_per_night" min="1" step="0.01" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-orange-200 focus:border-orange-400 transition" required>
                        </div>
                        <div class="flex-1">
                            <label class="block font-semibold mb-1">Max Guests</label>
                            <input type="number" name="max_guests" min="1" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-orange-200 focus:border-orange-400 transition" required>
                        </div>
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">Description</label>
                        <textarea name="description" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-orange-200 focus:border-orange-400 transition" rows="4" required></textarea>
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">Google Maps Link</label>
                        <input type="text" name="map_url" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-orange-200 focus:border-orange-400 transition" required>
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">Map Embed (iframe)</label>
                        <input type="text" name="iframe_embed" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-orange-200 focus:border-orange-400 transition" required>
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
                                    <input type="checkbox" name="amenities[]" value="<?= $amenity['id'] ?>" class="rounded text-orange-500 focus:ring-orange-400">
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
                                    <input type="checkbox" name="facilities[]" value="<?= $facility['id'] ?>" id="facility-<?= $facility['id'] ?>" class="rounded text-orange-500 focus:ring-orange-400">
                                    <label for="facility-<?= $facility['id'] ?>" class="flex-1"><?= htmlspecialchars($facility['name']) ?></label>
                                    <input type="number" name="facility_count[<?= $facility['id'] ?>]" min="1" value="1" class="w-16 border rounded-lg p-2 focus:ring-2 focus:ring-orange-200 focus:border-orange-400 transition">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="pt-4">
                        <label class="inline-flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                            <input type="checkbox" name="available" value="1" checked class="rounded text-orange-500 focus:ring-orange-400">
                            <span class="font-medium">Available for booking</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Button - Full width below columns -->
            <div class="mt-8">
                <button type="submit" class="w-full bg-orange-500 text-white py-3 rounded-lg font-semibold hover:bg-orange-600 transition focus:outline-none focus:ring-2 focus:ring-orange-400 focus:ring-offset-2">
                    Add Stay
                </button>
            </div>
        </form>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const imageUpload = $('#image-upload');
            const addMoreBtn = $('#add-more-images');
            const imagePreview = $('#image-preview');

            // Function to handle file selection
            function handleFiles(files) {
                $.each(files, function(i, file) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const preview = $(
                            `<div class="relative group">
            <img src="${e.target.result}" class="h-24 w-full object-cover rounded-lg border">
            <button type="button" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity">×</button>
          </div>`
                        );

                        preview.find('button').on('click', function() {
                            preview.remove();
                            if (imagePreview.children().length === 0) {
                                imagePreview.addClass('hidden');
                            }
                        });

                        imagePreview.append(preview).removeClass('hidden');
                    };

                    reader.readAsDataURL(file);
                });
            }

            // Main upload handler
            imageUpload.on('change', function() {
                handleFiles(this.files);
            });

            // Add more images button
            addMoreBtn.on('click', function() {
                imageUpload.click();
            });

            // Drag and drop functionality
            $('label[for="image-upload"]').on('dragover', function(e) {
                e.preventDefault();
                $(this).closest('div').addClass('border-orange-500 bg-orange-50');
            }).on('dragleave', function(e) {
                e.preventDefault();
                $(this).closest('div').removeClass('border-orange-500 bg-orange-50');
            }).on('drop', function(e) {
                e.preventDefault();
                $(this).closest('div').removeClass('border-orange-500 bg-orange-50');
                handleFiles(e.originalEvent.dataTransfer.files);
            });
        });
    </script>
</body>

</html>