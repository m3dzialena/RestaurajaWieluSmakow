<?php
session_start();
session_unset(); // Usunięcie zmiennych sesji
session_destroy(); // Zniszczenie sesji
header("Location: index.php"); // Przekierowanie na stronę główną
exit();
?>
