<?php
session_start();
include('db.php');

// Sprawdzenie, czy użytkownik jest zalogowany, sprawdzamy zarówno sesję, jak i ciasteczka
$userId = null;
if (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
} elseif (isset($_COOKIE['userId'])) {
    $userId = $_COOKIE['userId'];
}

// Jeśli brak identyfikatora użytkownika, wyświetl komunikat
if (!$userId) {
    echo "Nie znaleziono danych użytkownika. Zaloguj się, aby zobaczyć swoje rezerwacje.";
    exit();
}

// Pobieranie rezerwacji użytkownika
$reservationsQuery = "SELECT rezerwacje.id_rezerwacji, stoliki.numer_stolika, rezerwacje.data_rezerwacji, 
                             rezerwacje.godzina_rezerwacji, rezerwacje.liczba_gosci, rezerwacje.preferencje
                      FROM rezerwacje
                      JOIN stoliki ON rezerwacje.id_stolika = stoliki.id_stolika
                      WHERE rezerwacje.id_uzytkownika = ?";

$stmt = $conn->prepare($reservationsQuery);
$stmt->bind_param("i", $userId);  // Zapobieganie SQL Injection przez bind_param
$stmt->execute();
$reservationsResult = $stmt->get_result();

// Sprawdzanie, czy są rezerwacje
if ($reservationsResult->num_rows > 0) {
    echo "Twoje rezerwacje:<br>";
    while ($row = $reservationsResult->fetch_assoc()) {
        echo "Rezerwacja ID: " . htmlspecialchars($row['id_rezerwacji']) . "<br>";
        echo "Numer stolika: " . htmlspecialchars($row['numer_stolika']) . "<br>";
        echo "Data: " . htmlspecialchars($row['data_rezerwacji']) . "<br>";
        echo "Godzina: " . htmlspecialchars($row['godzina_rezerwacji']) . "<br>";
        echo "Liczba gości: " . htmlspecialchars($row['liczba_gosci']) . "<br>";
        echo "Preferencje: " . htmlspecialchars($row['preferencje']) . "<br><br>";
    }
} else {
    echo "Brak rezerwacji dla tego użytkownika.";
}

$stmt->close();
$conn->close();
?>

<!-- HTML do wyświetlenia rezerwacji -->
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moje Rezerwacje</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Moje Rezerwacje</h1>
    </header>
    <main>
        <?php if ($reservationsResult && $reservationsResult->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Numer stolika</th>
                        <th>Data</th>
                        <th>Godzina</th>
                        <th>Liczba gości</th>
                        <th>Preferencje</th>
                        <th>Opcje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $reservationsResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['numer_stolika']); ?></td>
                            <td><?php echo htmlspecialchars($row['data_rezerwacji']); ?></td>
                            <td><?php echo htmlspecialchars($row['godzina_rezerwacji']); ?></td>
                            <td><?php echo htmlspecialchars($row['liczba_gosci']); ?></td>
                            <td><?php echo htmlspecialchars($row['preferencje']); ?></td>
                            <td>
                                <a href="edytuj_rezerwacje.php?id=<?php echo $row['id_rezerwacji']; ?>">Edytuj</a> |
                                <a href="anuluj_rezerwacje.php?id=<?php echo $row['id_rezerwacji']; ?>" onclick="return confirm('Czy na pewno chcesz anulować tę rezerwację?')">Anuluj</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nie masz żadnych rezerwacji.</p>
        <?php endif; ?>
    </main>
</body>
</html>
