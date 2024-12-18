<?php
session_start();

// Połączenie z bazą danych
$polaczenie = mysqli_connect('localhost', 'root', '', 'restauracja');

if (!$polaczenie) {
    die("Nie udało się połączyć z bazą danych: " . mysqli_connect_error());
}

$userId = null;
if (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
} elseif (isset($_COOKIE['userId'])) {
    $userId = $_COOKIE['userId'];
}

// Jeśli użytkownik nie jest zalogowany, zakończ działanie
if (!$userId) {
    echo "<p style='color: red;'>Musisz się zalogować, aby dokonać rezerwacji.</p>";
    exit();
}

// Pobranie i walidacja danych z formularza
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$date = trim($_POST['date']);
$time = trim($_POST['time']);
$guests = intval($_POST['guests']);
$preferences = trim($_POST['preferences']);

$errors = [];

// Walidacja pola "Imię i nazwisko"
if (empty($name) || strlen($name) < 3) {
    $errors[] = "Proszę podaj prawidłowe imię i nazwisko.";
}

// Walidacja pola "Email"
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Proszę podaj prawidłowy adres email.";
}

// Walidacja pola "Telefon"
if (!preg_match('/^\+?[0-9]{9,15}$/', $phone)) {
    $errors[] = "Proszę podaj prawidłowy numer telefonu.";
}

// Walidacja daty i godziny
if (empty($date) || empty($time)) {
    $errors[] = "Proszę podaj datę i godzinę rezerwacji.";
} else {
    $datetime = strtotime("$date $time");
    if ($datetime < time()) {
        $errors[] = "Data i godzina rezerwacji nie może być w przeszłości.";
    }
}

// Walidacja liczby gości
if ($guests < 1 || $guests > 20) {
    $errors[] = "Liczba gości musi mieć wartość od 1 do 20.";
}

// Sprawdzenie, czy występują błędy
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p style='color: red;'>$error</p>";
    }
    exit();
}

// Sprawdzanie dostępności stolika
$id_stolika = 1; // Zakładając, że stolik jest wybierany dynamicznie, tutaj jest 1 dla przykładu
$query = "
    SELECT id_rezerwacji
    FROM rezerwacje
    WHERE id_stolika = ? AND data_rezerwacji = ? AND godzina_rezerwacji = ?
";

$stmt = $polaczenie->prepare($query);
$stmt->bind_param("iss", $id_stolika, $date, $time);
$stmt->execute();
$result = $stmt->get_result();

// Jeśli stolik jest już zarezerwowany na ten termin i godzinę
if ($result->num_rows > 0) {
    echo "<p style='color: red;'>Stolik nr $id_stolika jest już zarezerwowany w tym terminie. Proszę wybrać inny termin.</p>";
} else {
    // Jeśli stolik jest dostępny, dodajemy rezerwację
    $stmt = $polaczenie->prepare(
        "INSERT INTO rezerwacje (id_uzytkownika, id_stolika, data_rezerwacji, godzina_rezerwacji, liczba_gosci, preferencje) VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("iissis", $userId, $id_stolika, $date, $time, $guests, $preferences);

    if ($stmt->execute()) {
        echo "<p>Rezerwacja została pomyślnie dokonana na $date o $time dla $guests gości!</p>";
        // Opcjonalnie można przekierować użytkownika na stronę główną lub na stronę z potwierdzeniem rezerwacji
        // header("Location: index.php");
        // exit();
    } else {
        echo "<p style='color: red;'>Wystąpił błąd podczas dokonywania rezerwacji: " . $stmt->error . "</p>";
    }
}

// Zamknięcie zapytania i połączenia
$stmt->close();
$polaczenie->close();
