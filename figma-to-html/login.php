<?php
session_start(); // Rozpoczęcie sesji

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restauracja";

// Połączenie z bazą danych
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = $_POST['username']; // Zmienna z formularza
    $pass = $_POST['password']; // Zmienna z formularza

    // Przygotowanie zapytania
    $stmt = $conn->prepare("SELECT * FROM uzytkownicy WHERE imie_nazwisko = ? OR email = ?");
    $stmt->bind_param("ss", $user, $user); // Zabezpieczenie przed SQL Injection

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Weryfikacja hasła
        if (password_verify($pass, $row['haslo_hash'])) {
            // Zapisanie danych do sesji
            $_SESSION['username'] = $row['imie_nazwisko'];
            $_SESSION['userId'] = $row['id_uzytkownika'];

            // Sprawdzamy, czy użytkownik jest administratorem
            if ($row['typ'] === 'admin') {
                $_SESSION['is_admin'] = true; // Dodajemy flagę dla administratora
                header("Location: ./admin/admin_dashboard.php");
                exit();
            } else {
                header("Location: index.php"); // Przekierowanie na stronę główną dla zwykłego użytkownika
                exit();
            }
        } else {
            echo "Błędna nazwa użytkownika lub hasło.";
        }
    } else {
        echo "Użytkownik o podanej nazwie nie istnieje.";
    }

    // Zamknięcie zapytania
    $stmt->close();
}

// Zamknięcie połączenia z bazą danych
$conn->close();
?>
