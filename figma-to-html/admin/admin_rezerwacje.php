<?php
session_start();

// Sprawdzanie, czy użytkownik jest administratorem
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}

$polaczenie = mysqli_connect('localhost', 'root', '', 'restauracja');

if (!$polaczenie) {
    die("Nie udało się połączyć z bazą danych: " . mysqli_connect_error());
}

$query = "SELECT r.id_rezerwacji, u.imie_nazwisko, s.numer_stolika, r.data_rezerwacji, r.godzina_rezerwacji, r.liczba_gosci 
          FROM rezerwacje r
          JOIN uzytkownicy u ON r.id_uzytkownika = u.id_uzytkownika
          JOIN stoliki s ON r.id_stolika = s.id_stolika";

$result = mysqli_query($polaczenie, $query);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezerwacje - Panel Administratora</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <header>
        <h1>Panel Administratora - Rezerwacje</h1>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="admin_stoliki.php">Stoliki</a></li>
                <li><a href="admin_uzytkownicy.php">Użytkownicy</a></li>
                <li><a href="logout.php">Wyloguj</a></li>
            </ul>
        </nav>
    </header>
    
    <main>
        <h2>Lista Rezerwacji</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imię i Nazwisko</th>
                    <th>Numer Stolika</th>
                    <th>Data</th>
                    <th>Godzina</th>
                    <th>Liczba Gości</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['id_rezerwacji']; ?></td>
                    <td><?php echo $row['imie_nazwisko']; ?></td>
                    <td><?php echo $row['numer_stolika']; ?></td>
                    <td><?php echo $row['data_rezerwacji']; ?></td>
                    <td><?php echo $row['godzina_rezerwacji']; ?></td>
                    <td><?php echo $row['liczba_gosci']; ?></td>
                    <td>
                        <a href="usun_rezerwacje.php?id=<?php echo $row['id_rezerwacji']; ?>">Usuń</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
    
    <footer>
        <p>&copy; 2024 Restauracja Wielu Smaków</p>
    </footer>
</body>
</html>
