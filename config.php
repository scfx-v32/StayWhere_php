<?php
$host = "localhost";
$dbname = "staywhere_db";
$user = "root";
$pass = "0000"; // Change this if needed

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB connection ERROR : " . $e->getMessage());
}
