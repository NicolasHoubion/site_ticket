<?php
$host = 'localhost';
$dbname = 'u208447672_My_Ticket_233';
$username = 'u208447672_NicoHoubi';
$password = '31NvT]Vt';

// Tentative de connexion
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Échec MySQLi: " . $conn->connect_error);
} else {
    echo "Succès MySQLi!";
    $conn->close();
}

// Test PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    echo "Succès PDO!";
} catch (PDOException $e) {
    echo "Échec PDO: " . $e->getMessage();
}