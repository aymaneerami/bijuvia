<?php
require_once __DIR__ . '/../config/database.php';

function showProducts()
{
    include __DIR__ . '/../views/products.php';
}

// Fonction pour gérer les requêtes AJAX
function handleProductAjaxRequest()
{
    header('Content-Type: application/json');

    try {
        global $pdo; // Connexion PDO
        $data = json_decode(file_get_contents("php://input"), true);

        // Si les données sont envoyées via FormData (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['product_id'])) {
            // Récupérer un produit spécifique pour l'édition
            $productId = $_POST['product_id'];
            $stmt = $pdo->prepare("SELECT p.*, c.name as category_name 
                                  FROM products p 
                                  LEFT JOIN categories c ON p.category_id = c.id 
                                  WHERE p.id = ?");
            $stmt->execute([$productId]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($product) {
                echo json_encode(['success' => true, 'product' => $product]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Produit introuvable']);
            }
            return;
        }

        // Sinon, recherche et filtrage de produits
        $query = $data['query'] ?? '';
        $category = $data['category'] ?? '';
        $priceOrder = $data['priceOrder'] ?? '';

        $sql = "SELECT * FROM products WHERE 1=1";
        $params = [];

        if (!empty($query)) {
            $sql .= " AND name LIKE :query";
            $params[':query'] = '%' . $query . '%';
        }

        if (!empty($category)) {
            $sql .= " AND category_id = :category";
            $params[':category'] = $category;
        }

        if ($priceOrder === 'low-to-high') {
            $sql .= " ORDER BY price ASC";
        } elseif ($priceOrder === 'high-to-low') {
            $sql .= " ORDER BY price DESC";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'products' => $products]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}


function showProduct($productId)
{
    global $pdo;

    // Récupérer les détails du produit
    $sql = "SELECT * FROM products WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        // Récupérer les produits similaires
        $sqlSimilar = "
            SELECT p.id, p.name, p.description, p.price, p.image_url 
            FROM products p
            JOIN product_similarities ps ON p.id = ps.similar_product_id
            WHERE ps.product_id = :id
        ";
        $stmtSimilar = $pdo->prepare($sqlSimilar);
        $stmtSimilar->execute([':id' => $productId]);
        $similarProducts = $stmtSimilar->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . '/../views/productDetail.php'; // Vue pour afficher les détails du produit
    } else {
        echo "Produit introuvable.";
    }
}

