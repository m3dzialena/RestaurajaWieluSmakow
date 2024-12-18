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

$query = "SELECT * FROM uzytkownicy";
$result = mysqli_query($polaczenie, $query);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Użytkownicy - Panel Administratora</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <header>
        <h1>Panel Administratora - Użytkownicy</h1>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="admin_rezerwacje.php">Rezerwacje</a></li>
                <li><a href="admin_stoliki.php">Stoliki</a></li>
                <li><a href="logout.php">Wyloguj</a></li>
            </ul>
        </nav>
    </header>
    
    <main>
        <h2>Lista Użytkowników</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imię i Nazwisko</th>
                    <th>Adres Email</th>
                    <th>Typ</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['id_uzytkownika']; ?></td>
                    <td><?php echo $row['imie_nazwisko']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['typ']; ?></td>
                    <td>
                        <a href="usun_uzytkownika.php?id=<?php echo $row['id_uzytkownika']; ?>">Usuń</a>
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
