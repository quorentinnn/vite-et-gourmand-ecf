<?php
// config/database.php
// Connexion à la base de données

// Paramètres de connexion
define('DB_HOST', 'localhost');
define('DB_NAME', 'vite_gourmand_db');
define('DB_USER', 'root');
define('DB_PASS', ''); // Vide par défaut sur XAMPP

// Fonction pour obtenir la connexion PDO
function getConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        
        // Configuration PDO
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}
?>