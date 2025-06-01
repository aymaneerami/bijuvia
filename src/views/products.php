<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bijuvia - Produits</title>

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

        .loader {
            border: 4px solid #f3f3f3; /* Couleur de fond */
            border-radius: 50%;
            border-top: 4px solid #fbbf24; /* Couleur du bord supérieur */
            width: 36px;
            height: 36px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main class="mt-9">
        <section class="py-10 px-4 min-h-screen">
            <!-- Barre de recherche et filtres -->
            <div class="flex flex-col items-center mb-8 space-y-4">
                <!-- Barre de recherche -->
                <div class="flex items-center space-x-2 w-full max-w-xl">
                    <i class="fas fa-search text-gray-600"></i>
                    <input type="text" id="searchInput" class="px-6 py-3 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 text-lg" placeholder="Rechercher des produits...">
                </div>

                <!-- Filtres -->
                <div class="flex flex-col space-y-4 w-full max-w-xl">
                    <!-- Filtrer par catégorie -->
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-tags text-gray-600"></i>
                        <select id="categoryFilter" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500">
                            <option value="">Filtrer par catégorie</option>
                            <option value="1">Montres</option>
                            <option value="2">Bijoux</option>
                            <option value="3">Bracelets</option>
                            <option value="4">Collier</option>
                        </select>
                    </div>

                    <!-- Filtrer par prix -->
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-dollar-sign text-gray-600"></i>
                        <select id="priceFilter" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500">
                            <option value="">Filtrer par prix</option>
                            <option value="low-to-high">Prix : Bas à Haut</option>
                            <option value="high-to-low">Prix : Haut à Bas</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section des produits -->
            <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            
                <!-- Les produits seront affichés ici via AJAX -->
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("searchInput");
    const categoryFilter = document.getElementById("categoryFilter");
    const priceFilter = document.getElementById("priceFilter");
    const productGrid = document.getElementById("productGrid");

    // Loader pour l'UX
    const loader = document.createElement("div");
    loader.innerHTML = `
        <div class="flex justify-center items-center h-16">
            <div class="loader border-t-4 border-yellow-500 w-8 h-8 rounded-full animate-spin"></div>
        </div>`;
    loader.style.display = "none";
    productGrid.parentNode.insertBefore(loader, productGrid);

    const showLoader = () => {
        loader.style.display = "block";
        productGrid.style.display = "none";
    };

    const hideLoader = () => {
        loader.style.display = "none";
        productGrid.style.display = "grid";
    };

    // Fonction pour récupérer les produits via AJAX
    const fetchProducts = () => {
        const query = searchInput.value.trim();
        const category = categoryFilter.value;
        const priceOrder = priceFilter.value;

        showLoader();

        fetch("?route=productAjax", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ query, category, priceOrder }),
        })
            .then((response) => response.json())
            .then((data) => {
                hideLoader();
                productGrid.innerHTML = "";
                if (data.products && data.products.length > 0) {
                    data.products.forEach((product) => {
                        const productHTML = `
                            <div class="bg-white shadow-md rounded-md overflow-hidden">
                                <img src="${product.image_url}" alt="${product.name}" class="w-full h-48 object-cover">
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-2">${product.name}</h3>
                                    <p class="text-gray-600 mb-4">${product.description}</p>
                                    <p class="text-yellow-500 font-bold text-xl mb-4">${product.price} MAD</p>
                                    <div class="flex justify-between items-center">
                                        <a href="?route=product&id=${product.id}" class="text-yellow-500 hover:text-yellow-600 font-medium text-sm">Voir détails</a>
                                        <button 
                                            class="add-to-cart-btn px-4 py-2 bg-yellow-500 text-white text-sm font-medium rounded-md shadow-md hover:bg-yellow-600 transition duration-300"
                                            data-product-id="${product.id}">
                                            Ajouter au panier
                                        </button>
                                    </div>
                                </div>
                            </div>`;
                        productGrid.insertAdjacentHTML("beforeend", productHTML);
                    });
                } else {
                    productGrid.innerHTML = `
                        <div class="flex items-center justify-center min-h-[50vh] flex-col">
                            <i class="fas fa-box-open text-6xl text-gray-400 mb-4"></i>
                            <p class="text-center text-gray-500 font-semibold text-lg">Aucun produit trouvé.</p>
                        </div>`;
                }
            })
            .catch((error) => {
                console.error("Erreur :", error);
                productGrid.innerHTML = `
                    <div class="text-center font-extrabold text-2xl mt-15 p-4 text-gray-600">
                        <i class="fas fa-times-circle text-4xl mb-2 text-red-500"></i>
                        <p>Erreur lors du chargement des produits.</p>
                    </div>`;
                hideLoader();
            });
    };

    // Ajouter au panier via AJAX
    const addToCart = (productId) => {
        fetch("?route=add-to-cart", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ product_id: productId, quantity: 1 }),
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Erreur HTTP : " + response.status);
                }
                return response.json();
            })
            .then((data) => {
                if (data.success) {
                    // SweetAlert2 pour succès
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
                    // SweetAlert2 pour erreur
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
            .catch((error) => {
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
    };

    // Gestion des événements
    searchInput.addEventListener("input", fetchProducts);
    categoryFilter.addEventListener("change", fetchProducts);
    priceFilter.addEventListener("change", fetchProducts);

    document.addEventListener("click", (event) => {
        if (event.target.classList.contains("add-to-cart-btn")) {
            const productId = event.target.getAttribute("data-product-id");
            addToCart(productId);
        }
    });

    // Chargement initial des produits
    fetchProducts();
});

    </script>
</body>
</html>
