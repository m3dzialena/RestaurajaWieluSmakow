<?php
session_start(); // Rozpoczęcie sesji

// Sprawdzamy, czy użytkownik jest zalogowany i ma rolę admina
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    // Jeśli użytkownik nie jest administratorem, przekierowujemy go do strony logowania
    header("Location: ../logowanie.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restauracja";

// Połączenie z bazą danych
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

// Pobranie listy użytkowników
$userQuery = "SELECT id_uzytkownika, imie_nazwisko, email, typ FROM uzytkownicy";
$usersResult = $conn->query($userQuery);

// Pobranie listy stolików
$tableQuery = "SELECT * FROM stoliki";
$tablesResult = $conn->query($tableQuery);

// Pobranie listy rezerwacji
$reservationQuery = "SELECT r.id_rezerwacji, r.data_rezerwacji, r.godzina_rezerwacji, u.imie_nazwisko AS user_name, s.numer_stolika FROM rezerwacje r JOIN uzytkownicy u ON r.id_uzytkownika = u.id_uzytkownika JOIN stoliki s ON r.id_stolika = s.id_stolika";
$reservationsResult = $conn->query($reservationQuery);

// Pobranie listy pozycji menu
$menuQuery = "SELECT * FROM menu";
$menuResult = $conn->query($menuQuery);
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administracyjny - Restauracja</title>
    <link rel="stylesheet" href="../style.css"> <!-- Ścieżka do Twojego pliku CSS -->
</head>

<body>
    <header>
        <div class="logo">
            <h1>Panel Administracyjny - Restauracja Wielu Smaków</h1>
        </div>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="../logout.php">Wyloguj się</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Użytkownicy</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imię i Nazwisko</th>
                        <th>Email</th>
                        <th>Typ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $usersResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id_uzytkownika']; ?></td>
                            <td><?php echo $row['imie_nazwisko']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['typ']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <section>
            <h2>Stoliki</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID Stolika</th>
                        <th>Numer Stolika</th>
                        <th>Liczba Miejsc</th>
                        <th>Lokalizacja</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $tablesResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id_stolika']; ?></td>
                            <td><?php echo $row['numer_stolika']; ?></td>
                            <td><?php echo $row['liczba_miejsc']; ?></td>
                            <td><?php echo $row['lokalizacja']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <section>
            <h2>Rezerwacje</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID Rezerwacji</th>
                        <th>Data Rezerwacji</th>
                        <th>Godzina</th>
                        <th>Użytkownik</th>
                        <th>Numer Stolika</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $reservationsResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id_rezerwacji']; ?></td>
                            <td><?php echo $row['data_rezerwacji']; ?></td>
                            <td><?php echo $row['godzina_rezerwacji']; ?></td>
                            <td><?php echo $row['user_name']; ?></td>
                            <td><?php echo $row['numer_stolika']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <section>
            <h2>Menu</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nazwa Dania</th>
                        <th>Cena</th>
                        <th>Kategoria</th>
                        <th>Opis</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $menuResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id_pozycji']; ?></td>
                            <td><?php echo $row['nazwa_dania']; ?></td>
                            <td><?php echo $row['cena']; ?></td>
                            <td><?php echo $row['kategoria']; ?></td>
                            <td><?php echo $row['opis']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Restauracja Wielu Smaków</p>
    </footer>
</body>

</html>

<?php
// Zamknięcie połączenia z bazą danych
$conn->close();
?>