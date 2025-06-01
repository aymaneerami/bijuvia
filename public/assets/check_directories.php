<?php
/**
 * Script utilitaire pour vérifier et créer les répertoires nécessaires aux uploads
 */

// Définir les répertoires nécessaires
$directories = [
    '../public/assets/images/',
    '../public/assets/images/products/',
    '../public/assets/images/categories/',
    '../public/assets/images/users/'
];

foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        // Créer le répertoire avec les permissions 0777 (lecture/écriture/exécution pour tous)
        if (mkdir($dir, 0777, true)) {
            echo "Répertoire créé avec succès: " . $dir . "<br>";
        } else {
            echo "Erreur lors de la création du répertoire: " . $dir . "<br>";
        }
    } else {
        // Vérifier les permissions
        if (!is_writable($dir)) {
            // Tenter de modifier les permissions
            if (chmod($dir, 0777)) {
                echo "Permissions mises à jour pour: " . $dir . "<br>";
            } else {
                echo "Impossible de modifier les permissions pour: " . $dir . "<br>";
            }
        } else {
            echo "Le répertoire existe et est accessible en écriture: " . $dir . "<br>";
        }
    }
}

echo "<p>Vérification terminée!</p>"; 