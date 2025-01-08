<?php
session_start();

// Sprawdzanie, czy użytkownik jest administratorem
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: ../login.php");
    exit();
}

$polaczenie = mysqli_connect('localhost', 'root', '', 'restauracja');

if (!$polaczenie) {
    die("Nie udało się połączyć z bazą danych: " . mysqli_connect_error());
}

if (isset($_GET['id'])) {
    $id_rezerwacji = $_GET['id'];
    
    $query = "DELETE FROM rezerwacje WHERE id_rezerwacji = ?";
    $stmt = $polaczenie->prepare($query);
    $stmt->bind_param('i', $id_rezerwacji);
    
    if ($stmt->execute()) {
        header("Location: admin_rezerwacje.php");
        exit();
    } else {
        echo "Wystąpił błąd podczas usuwania rezerwacji.";
    }
}
?>
