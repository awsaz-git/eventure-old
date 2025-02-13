<?php
$host = 'localhost';
$dbName = 'eventure';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);
} catch (PDOException $e) {
    $e->getMessage();
}
