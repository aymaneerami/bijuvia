<?php
// Charger les variables d'environnement
require_once '../vendor/autoload.php';  // Assurez-vous que le chemin vers l'autoloader est correct
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');  // Charger le .env depuis la racine du projet
$dotenv->load();

// Connexion à la base de données avec PDO
$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];

try {
    // Créer une instance PDO pour la connexion à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Activation du mode d'erreur
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}
?>
