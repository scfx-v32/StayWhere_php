<?php
require_once 'config.php';

// Admin credentials
$name     = "Admin";
$email    = "admin2@staywhere.com";
$phone    = "0000000000";
$password = "admin"; // Change if needed
$role     = "admin";

// Check if admin already exists
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->fetch()) {
    echo "⚠️ Admin already exists.";
    exit;
}

// Hash and insert
$hashed = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO users (name, email, telephone, password, role, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
$success = $stmt->execute([$name, $email, $phone, $hashed, $role]);

if ($success) {
    echo "✅ Admin created successfully.<br>Email: $email<br>Password: $password";
} else {
    echo "❌ Failed to create admin.";
}
?>
