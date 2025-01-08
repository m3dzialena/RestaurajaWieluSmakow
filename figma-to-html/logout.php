<?php
session_start(); // Rozpoczęcie sesji

// Sprawdzanie, czy wynik z bazy danych istnieje
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Weryfikacja hasła
    if (password_verify($pass, $row['haslo_hash'])) {
        // Zapisanie danych do sesji
        $_SESSION['username'] = $row['imie_nazwisko'];
        $_SESSION['userId'] = $row['id_uzytkownika'];

        // Ustawienie ciasteczka na 7 dni
        setcookie("userId", $row['id_uzytkownika'], time() + (7 * 24 * 60 * 60), "/");

        header("Location: index.php"); // Przekierowanie na stronę główną
        exit();
    } else {
        // Jeśli hasło jest błędne
        echo "Błędna nazwa użytkownika lub hasło.";
        
        // Dodanie wylogowania, gdy logowanie nie powiedzie się
        session_unset(); // Usunięcie zmiennych sesji
        session_destroy(); // Zniszczenie sesji
        setcookie("userId", "", time() - 3600, "/"); // Usuwanie ciasteczka
        header("Location: index.php"); // Przekierowanie na stronę główną
        exit();
    }
} else {
    // Jeśli użytkownik nie istnieje w bazie danych
    echo "Brak użytkownika w bazie.";
    
    // Dodanie wylogowania, gdy użytkownik nie istnieje
    session_unset(); // Usunięcie zmiennych sesji
    session_destroy(); // Zniszczenie sesji
    setcookie("userId", "", time() - 3600, "/"); // Usuwanie ciasteczka
    header("Location: index.php"); // Przekierowanie na stronę główną
    exit();
}
?>
