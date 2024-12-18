<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: logowanie.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restauracja";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idRezerwacji = $_POST['id_rezerwacji'];
    $data = $_POST['data'];
    $godzina = $_POST['godzina'];
    $liczbaGosci = $_POST['liczba_gosci'];
    $preferencje = $_POST['preferencje'];

    $updateQuery = "UPDATE rezerwacje 
                    SET data_rezerwacji = ?, godzina_rezerwacji = ?, liczba_gosci = ?, preferencje = ? 
                    WHERE id_rezerwacji = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssisi", $data, $godzina, $liczbaGosci, $preferencje, $idRezerwacji);
    if ($stmt->execute()) {
        header("Location: moje_rezerwacje.php");
        exit();
    } else {
        echo "Błąd podczas edytowania rezerwacji: " . $conn->error;
    }
}

if (isset($_GET['id'])) {
    $idRezerwacji = $_GET['id'];
    $reservationQuery = "SELECT * FROM rezerwacje WHERE id_rezerwacji = ?";
    $stmt = $conn->prepare($reservationQuery);
    $stmt->bind_param("i", $idRezerwacji);
    $stmt->execute();
    $reservationResult = $stmt->get_result();
    if ($reservationResult->num_rows > 0) {
        $reservation = $reservationResult->fetch_assoc();
    } else {
        echo "Nie znaleziono rezerwacji.";
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edytuj Rezerwację</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Edytuj Rezerwację</h1>
    <form action="edytuj_rezerwacje.php" method="POST">
        <input type="hidden" name="id_rezerwacji" value="<?php echo $reservation['id_rezerwacji']; ?>">
        <label for="data">Data:</label>
        <input type="date" id="data" name="data" value="<?php echo $reservation['data_rezerwacji']; ?>" required>

        <label for="godzina">Godzina:</label>
        <input type="time" id="godzina" name="godzina" value="<?php echo $reservation['godzina_rezerwacji']; ?>" required>

        <label for="liczba_gosci">Liczba Gości:</label>
        <input type="number" id="liczba_gosci" name="liczba_gosci" value="<?php echo $reservation['liczba_gosci']; ?>" required>

        <label for="preferencje">Preferencje:</label>
        <textarea id="preferencje" name="preferencje"><?php echo $reservation['preferencje']; ?></textarea>

        <button type="submit">Zapisz zmiany</button>
    </form>
</body>
</html>
