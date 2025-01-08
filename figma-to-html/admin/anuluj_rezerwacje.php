<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../logowanie.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restauracja";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $idRezerwacji = $_GET['id'];
    $deleteQuery = "DELETE FROM rezerwacje WHERE id_rezerwacji = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $idRezerwacji);
    if ($stmt->execute()) {
        header("Location: moje_rezerwacje.php");
        exit();
    } else {
        echo "Błąd podczas anulowania rezerwacji: " . $conn->error;
    }
}

$conn->close();
