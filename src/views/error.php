<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur - Bijuvia</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main class="min-h-screen pt-24 pb-16">
        <div class="container mx-auto px-4 max-w-2xl">
            <div class="text-center">
                <div class="mb-8">
                    <i class="fas fa-exclamation-triangle text-6xl text-red-500 mb-4"></i>
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">Accès refusé</h1>
                    <p class="text-xl text-gray-600 mb-8">
                        <?php echo htmlspecialchars($error ?? 'Une erreur est survenue.'); ?>
                    </p>
                </div>
                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Que pouvez-vous faire ?</h2>
                    <div class="space-y-4 text-left">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-home text-yellow-500 mt-1"></i>
                            <div>
                                <h3 class="font-medium text-gray-900">Retourner à l'accueil</h3>
                                <p class="text-gray-600 text-sm">Découvrez nos produits et services.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-user text-yellow-500 mt-1"></i>
                            <div>
                                <h3 class="font-medium text-gray-900">Vérifier vos permissions</h3>
                                <p class="text-gray-600 text-sm">Contactez un administrateur si nécessaire.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-sign-in-alt text-yellow-500 mt-1"></i>
                            <div>
                                <h3 class="font-medium text-gray-900">Se connecter</h3>
                                <p class="text-gray-600 text-sm">Connectez-vous avec un compte autorisé.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 space-x-4">
                    <a href="?route=home" class="inline-flex items-center px-6 py-3 bg-yellow-500 text-white font-medium rounded-xl hover:bg-yellow-600 transition duration-200">
                        <i class="fas fa-home mr-2"></i>
                        Retour à l'accueil
                    </a>
                    <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="?route=login" class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition duration-200">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Se connecter
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html> 