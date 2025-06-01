<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bijuvia - Panier</title>

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

    <main class="mt-15 px-6 lg:px-16 min-h-screen">
        <br>
        <br>
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white shadow-xl rounded-lg p-6 lg:p-12">
                <h1 class="text-4xl font-bold text-gray-800 mb-6">Votre Panier</h1>
                
                <?php if (!empty($cartItems)): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto border-collapse text-sm">
                            <thead>
                                <tr class="border-b">
                                    <th class="py-3 px-6 text-left">Produit</th>
                                    <th class="py-3 px-6 text-left">Quantité</th>
                                    <th class="py-3 px-6 text-left">Prix</th>
                                    <th class="py-3 px-6 text-left">Total</th>
                                    <th class="py-3 px-6 text-left">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cartItems as $item): ?>
                                    <tr id="product-<?= $item['product_id']; ?>" class="border-b hover:bg-gray-100">
                                        <td class="py-4 px-6"><?= htmlspecialchars($item['name']); ?></td>
                                        <td class="py-4 px-6"><?= htmlspecialchars($item['quantity']); ?></td>
                                        <td class="py-4 px-6"><?= number_format($item['price'], 2); ?> MAD</td>
                                        <td class="py-4 px-6"><?= number_format($item['price'] * $item['quantity'], 2); ?> MAD</td>
                                        <td class="py-4 px-6 flex space-x-4">
                                        
                                            <a href="?route=product&id=<?= $item['product_id']; ?>" class="text-blue-500 hover:underline">Voir les détails</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex justify-between items-center">
                        <p class="text-xl font-semibold">Total: <?= number_format($totalAmount, 2); ?> MAD</p>

                        <div class="flex items-center space-x-4">
                            <button id="clear-cart" class="px-6 py-3 bg-red-500 text-white font-medium rounded-md shadow-md hover:bg-red-700 transition duration-300">
                                Supprimer tous les produits
                            </button>
                           

                            <form id="checkout-form" action="?route=checkout" method="POST">
                                <button type="submit" id="checkout-btn" class="px-6 py-3 bg-yellow-500 text-white font-medium rounded-md shadow-md hover:bg-yellow-600 transition duration-300">
                                    Valider la commande
                                </button>
                            </form>

                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center">
                        <p class="text-2xl font-semibold text-gray-600">Votre panier est vide.</p>
                        <a href="?route=products" class="mt-4 inline-block text-lg text-blue-500">Retourner à la boutique</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>     
    <script>
        // Supprimer un produit du panier via AJAX
        function deleteProduct(productId) {
            fetch('?route=remove-from-cart', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ product_id: productId })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur HTTP : ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // SweetAlert2 pour succès
                        Swal.fire({
                            icon: 'success',
                            title: 'Produit supprimé',
                            text: 'Le produit a été retiré de votre panier avec succès.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#fbbf24', // Jaune foncé
                            background: '#fefce8', // Fond jaune pâle
                            color: '#4a5568', // Texte gris foncé
                            iconColor: '#38a169' // Vert pour l'icône de succès
                        }).then(() => {
                            document.getElementById(`product-${productId}`).remove();
                        });
                    } else {
                        // SweetAlert2 pour erreur
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: data.message || 'Une erreur s\'est produite lors de la suppression.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#e53e3e', // Rouge
                            background: '#fff5f5', // Fond rouge pâle
                            color: '#4a5568', // Texte gris foncé
                            iconColor: '#e53e3e' // Rouge pour l'icône d'erreur
                        });
                    }
                })
                .catch(error => {
                    console.error('Erreur AJAX :', error);
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
        }


        // Supprimer tous les produits du panier
        document.getElementById('clear-cart').addEventListener('click', () => {
            Swal.fire({
                icon: 'warning',
                title: 'Vider le panier ?',
                text: 'Êtes-vous sûr de vouloir vider votre panier ? Cette action est irréversible.',
                showCancelButton: true,
                confirmButtonText: 'Oui, vider',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#e53e3e', // Rouge pour le bouton confirmer
                cancelButtonColor: '#4a5568', // Gris foncé pour annuler
                background: '#fff5f5', // Fond rouge pâle
                color: '#4a5568' // Texte gris foncé
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('?route=clear-cart', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' }
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Erreur HTTP : ' + response.status);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // SweetAlert2 pour succès
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Panier vidé',
                                    text: 'Votre panier a été vidé avec succès.',
                                    confirmButtonText: 'OK',
                                    confirmButtonColor: '#fbbf24', // Jaune foncé
                                    background: '#fefce8', // Fond jaune pâle
                                    color: '#4a5568', // Texte gris foncé
                                    iconColor: '#38a169' // Vert pour l'icône de succès
                                }).then(() => {
                                    location.reload(); // Recharger la page
                                });
                            } else {
                                // SweetAlert2 pour erreur
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erreur',
                                    text: data.message || 'Une erreur s\'est produite lors de la suppression.',
                                    confirmButtonText: 'OK',
                                    confirmButtonColor: '#e53e3e', // Rouge
                                    background: '#fff5f5', // Fond rouge pâle
                                    color: '#4a5568', // Texte gris foncé
                                    iconColor: '#e53e3e' // Rouge pour l'icône d'erreur
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Erreur AJAX :', error);
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
                }
            });
        });


        // Gestion des clics sur les boutons de suppression des produits
        document.addEventListener('click', (event) => {
            if (event.target.classList.contains('delete-product')) {
                const productId = event.target.getAttribute('data-product-id');
                deleteProduct(productId);
            }
        });



    </script>

</body>
</html>
