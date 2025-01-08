<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restauracja";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];
    $type = $_POST['type'];

    if ($password !== $confirmPassword) {
        die("Hasła nie są zgodne. Spróbuj ponownie.");
    }

    $username = $conn->real_escape_string($username);
    $email = $conn->real_escape_string($email);

    // Hashowanie hasła
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Sprawdzenie, czy użytkownik lub email już istnieje
    $stmt = $conn->prepare("SELECT * FROM uzytkownicy WHERE imie_nazwisko = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        die("Użytkownik z podaną nazwą lub adresem e-mail już istnieje.");
    }

    // Dodanie nowego użytkownika
    $stmt = $conn->prepare("INSERT INTO uzytkownicy (imie_nazwisko, email, haslo_hash, typ) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $hashedPassword, $type);

    if ($stmt->execute()) {
        header("Location: logowanie.html");
        exit();
    } else {
        echo "Błąd podczas rejestracji: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
