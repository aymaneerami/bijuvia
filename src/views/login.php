<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bijuvia - Montres & Bijoux</title>

    <!-- Lien vers la police Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <!-- Lien vers le fichier Tailwind CSS -->
    <link href="../public/assets/css/tailwind.css" rel="stylesheet">
    <link href="../public/assets/css/custom.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Lien vers les icônes FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <style>
        /* Appliquer la police Poppins à tout le corps */
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">
    <?php
        include __DIR__ . '/../includes/header.php';
    ?>

    <main class="mt-9">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white p-8 rounded-md shadow-lg w-full max-w-md">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Bienvenue sur Bijuvia</h2>
                <!-- Afficher le message d'erreur s'il existe -->
                <?php if (!empty($errorMessage)): ?>
                    <div style="color: red;"><?= htmlspecialchars($errorMessage) ?></div>
                <?php endif; ?>
                <form action="?route=login" method="POST">
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Adresse e-mail</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email"
                            class="w-full border border-gray-300 rounded-md p-2 mt-1
                                focus:outline-none focus:ring-2 focus:ring-yellow-500 px-4"
                            placeholder="votreemail@example.com"
                            required
                        >
                    </div>
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            class="w-full border border-gray-300 rounded-md p-2 mt-1
                                focus:outline-none focus:ring-2 focus:ring-yellow-500 px-4"
                            placeholder="••••••••"
                            required
                        >
                    </div>
                    <button 
                        type="submit" 
                        class="w-full px-4 py-2 bg-yellow-500 text-white font-medium text-lg rounded-md shadow-md hover:bg-yellow-600 transition duration-300 ease-in-out"
                    >
                        Connexion
                    </button>
                    
                    
                </form>
                <p class="text-center text-gray-600 text-sm mt-4">
                    Pas encore de compte ? 
                    <a href="?route=register" class="text-yellow-500 font-medium hover:underline">Inscrivez-vous</a>
                </p>
            </div>
        </div>
    </main>


    <?php
        include __DIR__ . '/../includes/footer.php';
    ?>
 
</body>
</html>