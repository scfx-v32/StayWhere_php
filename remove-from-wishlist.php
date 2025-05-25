<?php
session_start();
require_once 'config.php';

// Set header for JSON response
header('Content-Type: application/json');

$user_id = $_SESSION['user']['id'] ?? null;
$stay_id = $_POST['stay_id'] ?? null;

if (!$user_id || !$stay_id) {
    echo json_encode(['success' => false, 'message' => 'Not logged in or missing stay ID.']);
    exit;
}

try {
    // Get user's wishlist (every user has exactly one)
    $stmt = $pdo->prepare("SELECT id FROM wishlists WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $wishlist_id = $stmt->fetchColumn();

    if (!$wishlist_id) {
        echo json_encode(['success' => false, 'message' => 'Wishlist not found for user.']);
        exit;
    }

    // Remove from wishlist
    $stmt = $pdo->prepare("DELETE FROM wishlist_stay WHERE wishlist_id = ? AND stay_id = ?");
    $stmt->execute([$wishlist_id, $stay_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Stay not found in wishlist.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}