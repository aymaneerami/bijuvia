<?php
// Démarrer la session si nécessaire
session_start();

// Inclure le fichier de connexion à la base de données
require_once '../src/config/database.php';

// Inclure le fichier des routes
require_once '../src/config/routes.php';

// Exécuter la fonction de gestion des routes
defineRoutes();
?>
