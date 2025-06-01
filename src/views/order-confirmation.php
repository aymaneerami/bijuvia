<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de commande - Bijuvia</title>

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">

    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main class="mt-15 px-6 lg:px-16 min-h-screen">
        <br><br>
        <div class="container mx-auto max-w-4xl bg-white shadow-md rounded-lg p-8 mt-10 text-center">
            <h1 class="text-4xl font-bold text-green-600 mb-4">Merci pour votre commande !</h1>
            <p class="text-lg text-gray-700 mb-6">Votre commande a été passée avec succès. Vous recevrez bientôt un email de confirmation.</p>
            <a href="?route=products" class="px-6 py-3 bg-yellow-500 text-white font-medium rounded-md shadow-md hover:bg-yellow-600 transition duration-300">
                Continuer vos achats
            </a>
        </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
