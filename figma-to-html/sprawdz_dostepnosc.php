<?php
session_start();

// Połączenie z bazą danych
$polaczenie = mysqli_connect('localhost', 'root', '', 'restauracja');

if (!$polaczenie) {
    die("Nie udało się połączyć z bazą danych: " . mysqli_connect_error());
}

// Pobranie danych z formularza
$date = trim($_POST['date']);
$time = trim($_POST['time']);
$guests = intval($_POST['guests']);

// Walidacja danych (np. data nie może być w przeszłości)
$datetime = strtotime("$date $time");
if ($datetime < time()) {
    echo "<p style='color: red;'>Data i godzina rezerwacji nie może być w przeszłości.</p>";
    exit();
}

// Zapytanie o dostępne stoliki
$query = "
    SELECT id_stolika, numer_stolika, lokalizacja
    FROM stoliki
    WHERE id_stolika NOT IN (
        SELECT id_stolika
        FROM rezerwacje
        WHERE data_rezerwacji = ? AND godzina_rezerwacji = ?
    )
    AND liczba_miejsc >= ?
";

// Przygotowanie zapytania
$stmt = $polaczenie->prepare($query);
$stmt->bind_param("ssi", $date, $time, $guests);

// Wykonanie zapytania
$stmt->execute();
$result = $stmt->get_result();

// Sprawdzanie, czy są dostępne stoliki
if ($result->num_rows > 0) {
    echo "<h2>Dostępne stoliki:</h2>";
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>Stolik nr " . htmlspecialchars($row['numer_stolika']) . " " .  htmlspecialchars($row['lokalizacja']) . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color: red;'>Brak dostępnych stolików w wybranym terminie.</p>";
}

// Zamknięcie zapytania i połączenia
$stmt->close();
$polaczenie->close();
