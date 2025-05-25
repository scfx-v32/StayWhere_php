<?php
session_start();
require_once 'config.php';

$user_id = $_SESSION['user']['id'] ?? null;
$stay_id = $_POST['stay_id'] ?? null;

if (!$user_id || !$stay_id) {
    echo json_encode(['success' => false, 'message' => 'Not logged in or missing stay ID.']);
    exit;
}

// Get user's wishlist (every user has exactly one)
$stmt = $pdo->prepare("SELECT id FROM wishlists WHERE user_id = ?");
$stmt->execute([$user_id]);
$wishlist_id = $stmt->fetchColumn();

if (!$wishlist_id) {
    echo json_encode(['success' => false, 'message' => 'Wishlist not found for user.']);
    exit;
}

// Check if already in wishlist
$stmt = $pdo->prepare("SELECT id FROM wishlist_stay WHERE wishlist_id = ? AND stay_id = ?");
$stmt->execute([$wishlist_id, $stay_id]);
if ($stmt->fetchColumn()) {
    echo json_encode(['success' => false, 'message' => 'Already in wishlist.']);
    exit;
}

// Add to wishlist
$stmt = $pdo->prepare("INSERT INTO wishlist_stay (wishlist_id, stay_id, created_at, updated_at) VALUES (?, ?, NOW(), NOW())");
$stmt->execute([$wishlist_id, $stay_id]);
echo json_encode(['success' => true]);