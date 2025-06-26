<?php
require 'config.php';      // Conexiunea la baza de date
//require 'functions.php';   // Dacă pui funcția acolo

function adaugaAdmin(PDO $pdo, string $first_name, string $last_name, string $username, string $parola_clara): bool {
    $check = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $check->execute([$username]);
    if ($check->fetch()) return false;

    $parola_hash = password_hash($parola_clara, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("
        INSERT INTO users (first_name, last_name, username, password, role, status, created_at)
        VALUES (?, ?, ?, ?, 'administrator', 'active', NOW())
    ");
    return $stmt->execute([$first_name, $last_name, $username, $parola_hash]);
}

// Apelează funcția
if (adaugaAdmin($pdo, 'Radu', 'Mot', 'radumot', 'radumot')) {
    echo "Admin creat cu succes.";
} else {
    echo "Eroare: Emailul există deja sau altă problemă.";
}