<?php
// Initialiser les variables
$message = '';
$success = false;
$uploadedFilePath = '';

// Traiter le formulaire s'il est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier si un fichier a été téléchargé
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'assets/images/';
        
        // Créer le répertoire s'il n'existe pas
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // Préserver le nom original avec les accents
        $original_name = basename($_FILES['image']['name']);
        // Ajouter un préfixe timestamp pour éviter les conflits
        $file_name = time() . '_' . $original_name;
        $target_file = $upload_dir . $file_name;
        
        // Déplacer le fichier téléchargé
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $success = true;
            $message = 'Image téléchargée avec succès!';
            $uploadedFilePath = $target_file;
        } else {
            $message = 'Erreur lors du téléchargement de l\'image.';
        }
    } else {
        $message = 'Veuillez sélectionner une image à télécharger.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Upload - Bijuvia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen p-6">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-md">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Test de téléchargement d'images</h1>
        
        <?php if (!empty($message)): ?>
            <div class="mb-6 p-4 rounded-lg <?php echo $success ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200'; ?>">
                <i class="fas <?php echo $success ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> mr-2"></i>
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success && !empty($uploadedFilePath)): ?>
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-3">Image téléchargée:</h2>
                <div class="border border-gray-200 rounded-lg p-4">
                    <img src="<?php echo $uploadedFilePath; ?>" alt="Uploaded Image" class="max-h-80 max-w-full mx-auto">
                    <div class="mt-3 text-sm text-gray-600">
                        <p><strong>Chemin:</strong> <?php echo $uploadedFilePath; ?></p>
                        <p><strong>Nom du fichier:</strong> <?php echo basename($uploadedFilePath); ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Sélectionner une image</label>
                <input type="file" id="image" name="image" accept="image/*" class="block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0
                    file:text-sm file:font-semibold
                    file:bg-yellow-50 file:text-yellow-700
                    hover:file:bg-yellow-100
                    border rounded-md p-2">
                <p class="mt-1 text-sm text-gray-500">Formats acceptés: JPG, PNG, GIF. Taille max: 5MB.</p>
            </div>
            
            <div>
                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-md transition duration-200">
                    <i class="fas fa-upload mr-2"></i>Télécharger l'image
                </button>
            </div>
        </form>
        
        <div class="mt-8 pt-6 border-t border-gray-200">
            <h2 class="text-lg font-semibold text-gray-700 mb-3">Informations sur l'environnement</h2>
            <ul class="text-sm text-gray-600 space-y-2">
                <li><strong>Chemin du répertoire d'upload:</strong> <?php echo realpath('assets/images/'); ?></li>
                <li><strong>Permissions:</strong> <?php echo is_writable('assets/images/') ? 'Accessible en écriture' : 'Non accessible en écriture'; ?></li>
                <li><strong>upload_max_filesize:</strong> <?php echo ini_get('upload_max_filesize'); ?></li>
                <li><strong>post_max_size:</strong> <?php echo ini_get('post_max_size'); ?></li>
            </ul>
            
            <div class="mt-4">
                <a href="assets/check_directories.php" class="text-blue-600 hover:text-blue-800 font-medium">
                    <i class="fas fa-folder-open mr-1"></i>Vérifier les répertoires
                </a>
            </div>
        </div>
    </div>
</body>
</html> 