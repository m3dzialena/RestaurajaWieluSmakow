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

    if ($password !== $confirmPassword) {
        die("Hasła nie są zgodne. Spróbuj ponownie.");
    }

    $username = $conn->real_escape_string($username);
    $email = $conn->real_escape_string($email);

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $checkUserQuery = "SELECT * FROM uzytkownicy WHERE imie_nazwisko = '$username' OR email = '$email'";
    $result = $conn->query($checkUserQuery);

    if ($result->num_rows > 0) {
        die("Użytkownik z podaną nazwą lub adresem e-mail już istnieje.");
    }

    $insertQuery = "INSERT INTO uzytkownicy (imie_nazwisko, email, haslo_hash) VALUES ('$username', '$email', '$hashedPassword')";
    if ($conn->query($insertQuery) === TRUE) {
        header("Location: index.html");
        exit();
    } else {
        echo "Błąd podczas rejestracji: " . $conn->error;
    }
}

$conn->close();
