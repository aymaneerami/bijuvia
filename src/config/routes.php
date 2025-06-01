<?php

// Charger les fichiers nécessaires
require_once '../src/controllers/HomeController.php';
require_once '../src/controllers/ProductController.php';
require_once '../src/controllers/CartController.php';
require_once '../src/controllers/AuthController.php';
require_once '../src/controllers/OrderController.php'; // Nouveau contrôleur
require_once '../src/controllers/ProfileController.php'; // Contrôleur de profil
require_once '../src/controllers/AdminController.php'; // Contrôleur admin

// Fonction pour définir les routes
function defineRoutes()
{
    $route = $_GET['route'] ?? 'home'; // Route par défaut
    $method = $_SERVER['REQUEST_METHOD']; // Méthode HTTP (GET ou POST)

    // Décoder les données JSON si elles sont envoyées avec Content-Type: application/json
    if ($method === 'POST' && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $rawData = file_get_contents('php://input');
        $decodedData = json_decode($rawData, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $_POST = $decodedData;
        }
    }

    // Table de correspondance des routes
    $routes = [
        'home' => 'showHome',                          // Page d'accueil
        'products' => 'showProducts',                 // Liste des produits
        'product' => 'showProduct',                   // Détail d'un produit (nécessite ID)
        'cart' => 'showCart',                         // Afficher le panier
        'add-to-cart' => 'addToCart',                 // Ajouter au panier (POST)
        'remove-from-cart' => 'removeFromCart',       // Supprimer un produit du panier (POST)
        'clear-cart' => 'clearCart',                  // Vider tout le panier (POST)
        'checkout' => 'checkout',                     // Valider la commande (POST)
        'order-confirmation' => 'showOrderConfirmation', // Confirmation de commande
        'login' => 'login',                           // Connexion utilisateur
        'register' => 'register',                     // Inscription utilisateur
        'logout' => 'logout',                         // Déconnexion utilisateur
        'profile' => 'showProfile',                   // Afficher le profil utilisateur
        'update-profile' => 'updateProfile',          // Mettre à jour le profil (POST)
        'user-generate-invoice' => 'generateUserInvoice', // Générer facture utilisateur
        'user-order-details' => 'showUserOrderDetails', // Détails commande utilisateur
        'admin' => 'showAdminDashboard',              // Panel d'administration
        'admin-order-details' => 'showOrderDetails', // Détails d'une commande (admin)
        'admin-generate-invoice' => 'generateInvoice', // Générer une facture (admin)
        'admin-update-order' => 'updateOrderStatus', // Mettre à jour statut commande (POST)
        'admin-delete-user' => 'deleteUser',         // Supprimer un utilisateur (POST)
        'admin-update-role' => 'updateUserRole',     // Mettre à jour rôle utilisateur (POST)
        'productAjax' => 'handleProductAjaxRequest'   // Requêtes AJAX pour les produits
    ];

    if (array_key_exists($route, $routes)) {
        $controllerFunction = $routes[$route];

        switch ($route) {
            case 'product':
                $productId = $_GET['id'] ?? null;
                if ($productId) {
                    $controllerFunction($productId);
                } else {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Produit introuvable.']);
                }
                break;

            case 'add-to-cart':
                $productId = $_POST['product_id'] ?? null;
                $quantity = $_POST['quantity'] ?? 1;
                if ($productId && $quantity) {
                    $controllerFunction($productId, $quantity);
                } else {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Données manquantes pour ajouter au panier.']);
                }
                break;

            default:
                $controllerFunction();
                break;
        }
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Page introuvable.']);
    }
}
