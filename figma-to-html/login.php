<?php
session_start(); // Start sesji

$servername = "localhost"; 
$username = "root";       
$password = "";           
$dbname = "restauracja";   

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $user = $conn->real_escape_string($user);

    $sql = "SELECT * FROM uzytkownicy WHERE imie_nazwisko = '$user'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($pass, $row['haslo_hash'])) {
            $_SESSION['zalogowany'] = true; // Flaga zalogowania
            $_SESSION['imie'] = $row['imie']; // Pobierz imię z bazy
            $_SESSION['nazwisko'] = $row['nazwisko']; // Pobierz nazwisko z bazy
            $_SESSION['username'] = $user; // Nazwa użytkownika
            header("Location: index.php"); // Przekierowanie na stronę główną
            exit();
        } else {
            echo "Błędna nazwa użytkownika lub hasło.";
        }
    } else {
        echo "Błędna nazwa użytkownika lub hasło.";
    }
}

$conn->close();
?>
