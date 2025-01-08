<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restauracja";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}
