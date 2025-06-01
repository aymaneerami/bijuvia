<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bijuvia - Détails du produit</title>

    <!-- Lien vers Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Lien vers la police Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- Lien vers FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Lien CSS pour SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">

    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main class="mt-12">
        <div class="container mx-auto p-6 lg:p-16">
            <div class="bg-white shadow-xl rounded-lg overflow-hidden flex flex-col lg:flex-row">
                <!-- Image du produit -->
                <div class="lg:w-1/2 bg-gray-100 p-4">
                    <img src="<?= $product['image_url']; ?>" alt="<?= $product['name']; ?>" class="w-full h-full object-cover rounded-lg shadow-md">
                </div>
                
                <!-- Détails du produit -->
                <div class="lg:w-1/2 p-6 flex flex-col justify-between">
                    <h1 class="text-3xl font-bold text-gray-800 mb-4"><?= $product['name']; ?></h1>
                    <p class="text-gray-600 text-lg mb-6"><?= $product['description']; ?></p>

                    <div class="flex items-center space-x-4 mb-6">
                        <p class="text-2xl font-semibold text-yellow-500"><?= $product['price']; ?> MAD</p>
                    </div>

                    <div class="flex items-center justify-between space-x-4 mt-auto">
                                        <button 
                                            class="add-to-cart-btn px-4 py-2 bg-yellow-500 text-white text-sm font-medium rounded-md shadow-md hover:bg-yellow-600 transition duration-300" 
                                            data-product-id="<?= $product['id']; ?>" 
                                            data-quantity="1">
                                            Ajouter au panier
                                         </button>

                        <a href="?route=cart" class="text-yellow-500 text-lg font-semibold hover:text-yellow-600">Voir le panier</a>
                    </div>
                </div>
            </div>

            <div class="mt-12 bg-white shadow-md rounded-lg p-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Produits similaires</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    <!-- Affichage des produits similaires -->
                    <?php if (!empty($similarProducts)): ?>
                        <?php foreach ($similarProducts as $similarProduct): ?>
                        <div class="bg-white shadow-md rounded-lg overflow-hidden">
                            <img src="<?= $similarProduct['image_url']; ?>" alt="<?= $similarProduct['name']; ?>" class="w-full h-48 object-cover">
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-800 mb-2"><?= $similarProduct['name']; ?></h3>
                                <p class="text-gray-600 mb-2"><?= $similarProduct['description']; ?></p>
                                <p class="text-yellow-500 font-bold text-xl mb-4"><?= $similarProduct['price']; ?> MAD</p>
                                <a href="?route=product&id=<?= $similarProduct['id']; ?>" class="text-yellow-500 hover:text-yellow-600 text-sm">Voir détails</a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-gray-600">Aucun produit similaire trouvé.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
<script>
    document.addEventListener("DOMContentLoaded", () => {
    // Gestion de l'ajout au panier avec AJAX
    const addToCartButtons = document.querySelectorAll(".add-to-cart-btn");

    addToCartButtons.forEach(button => {
        button.addEventListener("click", () => {
            const productId = button.getAttribute("data-product-id");
            const quantity = button.getAttribute("data-quantity");

            fetch("?route=add-to-cart", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Erreur HTTP : " + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // SweetAlert2 personnalisé pour succès
                    Swal.fire({
                        icon: 'success',
                        title: 'Produit ajouté !',
                        text: 'Votre produit a été ajouté avec succès au panier.',
                        confirmButtonText: 'Voir le panier',
                        cancelButtonText: 'Continuer',
                        showCancelButton: true,
                        confirmButtonColor: '#fbbf24', // Jaune foncé
                        cancelButtonColor: '#4a5568', // Gris foncé
                        background: '#fefce8', // Fond jaune pâle
                        color: '#4a5568', // Texte gris foncé
                        iconColor: '#38a169' // Vert pour l'icône de succès
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Rediriger vers le panier
                            window.location.href = '?route=cart';
                        }
                    });
                } else {
                    // SweetAlert2 personnalisé pour erreur
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: data.message || 'Impossible d\'ajouter le produit au panier.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#e53e3e', // Rouge
                        background: '#fff5f5', // Fond rouge pâle
                        color: '#4a5568', // Texte gris foncé
                        iconColor: '#e53e3e' // Rouge pour l'icône d'erreur
                    });
                }
            })
            .catch(error => {
                console.error("Erreur AJAX :", error);
                // SweetAlert2 pour exception
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Une erreur s\'est produite. Veuillez réessayer.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#e53e3e', // Rouge
                    background: '#fff5f5', // Fond rouge pâle
                    color: '#4a5568', // Texte gris foncé
                    iconColor: '#e53e3e' // Rouge pour l'icône d'erreur
                });
            });
        });
    });
});
    </script>
