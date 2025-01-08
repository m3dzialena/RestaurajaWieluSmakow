<?php
session_start();
include('db.php');

if (!isset($_SESSION['userId'])) {
    echo "Musisz być zalogowany, aby usunąć rezerwację.";
    exit();
}

if (isset($_GET['id'])) {
    $idRezerwacji = intval($_GET['id']);

    // Przygotowanie zapytania do usunięcia rezerwacji
    $deleteQuery = "DELETE FROM rezerwacje WHERE id_rezerwacji = ? AND id_uzytkownika = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("ii", $idRezerwacji, $_SESSION['userId']);

    if ($stmt->execute()) {
        echo "Rezerwacja została pomyślnie usunięta.";
    } else {
        echo "Wystąpił błąd podczas usuwania rezerwacji: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Nie podano ID rezerwacji.";
}

$conn->close();
