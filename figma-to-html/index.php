<?php
session_start(); // Rozpoczęcie sesji
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restauracja Wielu Smaków</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <div class="logo">
            <h1>Restauracja Wielu Smaków</h1>
        </div>
        <div class="witaj-log">
            <nav>
                <ul>
                    <li><a href="menu.html">Menu</a></li>
                    <li><a href="rezerwacja.html">Rezerwacja</a></li>
                    <li><a href="moje_rezerwacje.php">Moje Rezerwacje</a></li>
            <?php if (isset($_SESSION['username'])): ?>
                <li><a href="logout.php">Wyloguj (<?php echo $_SESSION['username']; ?>)</a></li>
            <?php else: ?>
                <li><a href="logowanie.html">Logowanie</a></li>
            <?php endif; ?>
                    <li><a href="logowanie.html">Logowanie</a></li>
                    <li><a href="kontakt.html">Kontakt</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section id="zdj">
    </section>

    <main>

        <section class="reservation">
            <img src="images/danie1.png" alt="danie">
            <div class="reservation-content">
                <h2>Zarezerwuj stolik już teraz</h2>
                <p>Doświadcz wyjątkowych kulinarnych doznań.</p>
                <button>Przejdź do rezerwacji</button>
            </div>
        </section>
        <hr>
        <section class="witaaj">
            <?php if (isset($_SESSION['username'])): ?>
                <p>Witaj, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!</p>
                <form action="logout.php" method="POST">
                    <button type="submit">Wyloguj</button>
                </form>
            <?php else: ?>
                <p>Jesteś niezalogowany. <a href="logowanie.html">Zaloguj się tutaj</a>.</p>
            <?php endif; ?>
        </section>
        <section class="popular-dishes">
            <h2>Nasze popularne dania</h2>
            <div class="dish-grid">
                <div class="dish">
                    <img src="images/danie1.png" alt="Sałatka Caprese">
                    <p>Sałatka Caprese<br><strong>Cena: 35 zł</strong></p>
                </div>
                <div class="dish">
                    <img src="images/danie2.png" alt="Zapiekanka Vege">
                    <p>Zapiekanka Vege<br><strong>Cena: 25 zł</strong></p>
                </div>
                <div class="dish">
                    <img src="images/danie1.png" alt="Soczysty Stek">
                    <p>Soczysty Stek<br><strong>Cena: 54 zł</strong></p>
                </div>
            </div>
        </section>
        <hr>

        <!-- Chefs Section -->
        <section class="chefs">
            <h2>Poznaj szefów naszej kuchni</h2>
            <div class="chef-grid">
                <div class="chef">
                    <img src="images/kucharz1.png" alt="Chef Zenon">
                    <p>Zenon G.<br>Specjalista od makaronów</p>
                </div>
                <div class="chef">
                    <img src="images/kucharz2.png" alt="Chef Andrzej">
                    <p>Andrzej M.<br>Mistrz wędzenia</p>
                </div>
                <div class="chef">
                    <img src="images/kucharz3.png" alt="Chef Piotr">
                    <p>Piotr P.<br>Fan burgerów</p>
                </div>
            </div>
        </section>
        <hr>

        <!-- Contact Section -->
        <section class="contact">
            <div class="map">
                <img src="images/mapa.png" alt="Map">
            </div>
            <div class="contact-info">
                <h2>Przyjedź do nas, Zapraszamy!</h2>
                <p><strong>Adres:</strong></p>
                <p>Koniec Świata 92-234, Ulica 4</p>
                <p><strong>Kontakt:</strong></p>
                <p>+48 123 456 789</p>
                <p>Do zobaczenia u nas!</p>
            </div>

        </section>
    </main>

    <footer>
    <nav>
    <ul>
        <li><a href="menu.html">Menu</a></li>
        <li><a href="rezerwacja.html">Rezerwacja</a></li>
        <li><a href="moje_rezerwacje.php">Moje Rezerwacje</a></li>
        <?php if (isset($_SESSION['username'])): ?>
            <li><a href="logout.php">Wyloguj (<?php echo $_SESSION['username']; ?>)</a></li>
        <?php else: ?>
            <li><a href="logowanie.html">Logowanie</a></li>
        <?php endif; ?>
        <li><a href="kontakt.html">Kontakt</a></li>
    </ul>
</nav>

    </footer>
</body>

</html>