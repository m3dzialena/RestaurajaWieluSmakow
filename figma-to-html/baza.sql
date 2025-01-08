-- Utworzenie bazy danych
CREATE DATABASE restauracja;

-- Użycie bazy danych
USE restauracja;

-- Tabela: Użytkownicy
CREATE TABLE uzytkownicy (
   id_uzytkownika INT AUTO_INCREMENT PRIMARY KEY,
    imie_nazwisko VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    haslo_hash VARCHAR(255) NOT NULL, -- Hasło przechowywane w formie zahashowanej
    numer_telefonu VARCHAR(15),
    data_utworzenia TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela: Stoliki
CREATE TABLE stoliki (
    id_stolika INT AUTO_INCREMENT PRIMARY KEY,
    numer_stolika INT NOT NULL UNIQUE,
    liczba_miejsc INT NOT NULL, -- Maksymalna liczba miejsc przy stoliku
    lokalizacja VARCHAR(50) -- Opcjonalnie: np. "Sala główna", "Ogród"
);

-- Tabela: Rezerwacje
CREATE TABLE  rezerwacje (
    id_rezerwacji INT AUTO_INCREMENT PRIMARY KEY,
    id_uzytkownika INT NOT NULL,
    id_stolika INT NOT NULL,
    data_rezerwacji DATE NOT NULL,
    godzina_rezerwacji TIME NOT NULL,
    liczba_gosci INT NOT NULL,
    preferencje TEXT,
    data_utworzenia TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_uzytkownika) REFERENCES uzytkownicy(id_uzytkownika) ON DELETE CASCADE,
    FOREIGN KEY (id_stolika) REFERENCES stoliki(id_stolika) ON DELETE CASCADE
);

-- Tabela: Menu
CREATE TABLE menu (
    id_pozycji INT AUTO_INCREMENT PRIMARY KEY,
    nazwa_dania VARCHAR(100) NOT NULL,
    cena DECIMAL(10, 2) NOT NULL,
    kategoria VARCHAR(50), -- np. Przystawki, Dania główne, Desery
    opis TEXT
);

-- Tabela: Pracownicy
CREATE TABLE pracownicy (
    id_pracownika INT AUTO_INCREMENT PRIMARY KEY,
    imie_nazwisko VARCHAR(100) NOT NULL,
    stanowisko VARCHAR(50), -- np. Kelner, Kucharz, Manager
    numer_telefonu VARCHAR(15),
    email VARCHAR(100),
    data_zatrudnienia DATE
);

-- Przykładowi użytkownicy
INSERT INTO uzytkownicy (imie_nazwisko, email, haslo_hash, numer_telefonu) VALUES
('Jan Kowalski', 'jan.kowalski@example.com', SHA2('password123', 256), '123456789'),
('Anna Nowak', 'anna.nowak@example.com', SHA2('securepass', 256), '987654321');

-- Przykładowe stoliki
INSERT INTO stoliki (numer_stolika, liczba_miejsc, lokalizacja) VALUES
(1, 4, 'Sala główna'),
(2, 2, 'Sala główna'),
(3, 6, 'Ogród'),
(4, 4, 'Ogród');

-- Przykładowe rezerwacje
INSERT INTO rezerwacje (id_uzytkownika, id_stolika, data_rezerwacji, godzina_rezerwacji, liczba_gosci, preferencje) VALUES
(1, 1, '2024-12-01', '18:00:00', 4, 'Blisko okna'),
(2, 2, '2024-12-05', '20:00:00', 2, 'Alergia na orzechy');

-- Przykładowe pozycje menu
INSERT INTO menu (nazwa_dania, cena, kategoria, opis) VALUES
('Sałatka Caprese', 35.00, 'Przystawki', 'Sałatka z mozzarelli, pomidorów i bazylii.'),
('Soczysty Stek', 54.00, 'Dania główne', 'Stek z najlepszej wołowiny z dodatkiem ziemniaków i warzyw.'),
('Tiramisu', 25.00, 'Desery', 'Tradycyjny włoski deser z mascarpone i kawą.');

-- admin
ALTER TABLE uzytkownicy ADD COLUMN typ ENUM('admin', 'user') DEFAULT 'user';

-- Przykładowi pracownicy
INSERT INTO pracownicy (imie_nazwisko, stanowisko, numer_telefonu, email, data_zatrudnienia) VALUES
('Maria Zawadzka', 'Kelner', '123123123', 'maria.zawadzka@example.com', '2023-01-15'),
('Tomasz Nowak', 'Kucharz', '987987987', 'tomasz.nowak@example.com', '2022-03-01');

-- admin 
INSERT INTO uzytkownicy (imie_nazwisko, email, haslo_hash, numer_telefonu, typ) 
VALUES ('Test Test', 'test@gmail.com', 'haslo123', '123456789', 'admin');
INSERT INTO uzytkownicy (imie_nazwisko, email, haslo_hash, numer_telefonu, typ) 
VALUES ('admin', 'admin@gmail.com', 'admin123', '123456789', 'admin');


