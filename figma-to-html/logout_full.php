<?php
session_start();
session_destroy(); // Usunięcie danych sesji

// Usuwanie ciasteczka userId
setcookie("userId", "", time() - 3600, "/");

header("Location: index.php");
exit();
?>
