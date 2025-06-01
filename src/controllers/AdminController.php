<?php

function showAdminDashboard() 
{
    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        header('Location: ?route=login');
        exit;
    }
    
    require '../src/config/database.php';
    
    try {
        // Récupérer les informations de l'utilisateur
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        
        // Vérifier si l'utilisateur est admin
        if (!$user || $user['role'] !== 'admin') {
            http_response_code(403);
            $error = "Accès refusé. Vous n'avez pas les droits d'administration.";
            include __DIR__ . '/../views/error.php';
            exit;
        }
        
        // Récupérer les statistiques
        $statsQueries = [
            'total_users' => 'SELECT COUNT(*) as count FROM users',
            'total_products' => 'SELECT COUNT(*) as count FROM products',
            'total_orders' => 'SELECT COUNT(*) as count FROM orders',
            'total_categories' => 'SELECT COUNT(*) as count FROM categories',
            'pending_orders' => 'SELECT COUNT(*) as count FROM orders WHERE status = "pending"',
            'completed_orders' => 'SELECT COUNT(*) as count FROM orders WHERE status = "completed"',
            'rejected_orders' => 'SELECT COUNT(*) as count FROM orders WHERE status = "rejected"',
            'total_revenue' => 'SELECT SUM(total_amount) as total FROM orders WHERE status = "completed"'
        ];
        
        $stats = [];
        foreach ($statsQueries as $key => $query) {
            $stmt = $pdo->query($query);
            $result = $stmt->fetch();
            $stats[$key] = $result['count'] ?? $result['total'] ?? 0;
        }
        
        // Récupérer les dernières commandes
        $recentOrdersStmt = $pdo->query('
            SELECT o.*, u.name as user_name, u.email as user_email 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            ORDER BY o.created_at DESC 
            LIMIT 10
        ');
        $recentOrders = $recentOrdersStmt->fetchAll();
        
        // Récupérer tous les utilisateurs
        $usersStmt = $pdo->query('SELECT * FROM users ORDER BY created_at DESC');
        $users = $usersStmt->fetchAll();
        
        // Récupérer tous les produits avec catégories
        $productsStmt = $pdo->query('
            SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            ORDER BY p.created_at DESC
        ');
        $products = $productsStmt->fetchAll();
        
        // Récupérer toutes les catégories
        $categoriesStmt = $pdo->query('SELECT * FROM categories ORDER BY name');
        $categories = $categoriesStmt->fetchAll();
        
    } catch (PDOException $e) {
        $error = "Erreur lors de la récupération des données: " . $e->getMessage();
        include __DIR__ . '/../views/error.php';
        exit;
    }
    
    include __DIR__ . '/../views/admin/dashboard.php';
}

function showOrderDetails() 
{
    // Vérifier si l'utilisateur est connecté et admin
    if (!isset($_SESSION['user_id'])) {
        header('Location: ?route=login');
        exit;
    }
    
    require '../src/config/database.php';
    
    try {
        // Vérifier si l'utilisateur est admin
        $stmt = $pdo->prepare('SELECT role FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        
        if (!$user || $user['role'] !== 'admin') {
            http_response_code(403);
            $error = "Accès refusé. Vous n'avez pas les droits d'administration.";
            include __DIR__ . '/../views/error.php';
            exit;
        }
        
        $orderId = $_GET['id'] ?? null;
        if (!$orderId) {
            header('Location: ?route=admin');
            exit;
        }
        
        // Récupérer les détails de la commande
        $orderStmt = $pdo->prepare('
            SELECT o.*, u.name as user_name, u.email as user_email, u.id as user_id
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            WHERE o.id = ?
        ');
        $orderStmt->execute([$orderId]);
        $order = $orderStmt->fetch();
        
        if (!$order) {
            header('Location: ?route=admin');
            exit;
        }
        
        // Récupérer les détails des produits de la commande (simulé car order_details est vide)
        // Pour l'instant, on crée une facture basique
        $orderItems = [
            [
                'product_name' => 'Commande #' . $order['id'],
                'quantity' => 1,
                'unit_price' => $order['total_amount'],
                'total_price' => $order['total_amount']
            ]
        ];
        
    } catch (PDOException $e) {
        $error = "Erreur lors de la récupération des données: " . $e->getMessage();
        include __DIR__ . '/../views/error.php';
        exit;
    }
    
    include __DIR__ . '/../views/admin/order-details.php';
}

function generateInvoice()
{
    // Vérifier si l'utilisateur est connecté et admin
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Non autorisé']);
        exit;
    }
    
    require '../src/config/database.php';
    
    try {
        // Vérifier si l'utilisateur est admin
        $stmt = $pdo->prepare('SELECT role FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        
        if (!$user || $user['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Accès refusé']);
            exit;
        }
        
        $orderId = $_GET['id'] ?? null;
        if (!$orderId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID commande manquant']);
            exit;
        }
        
        // Récupérer les détails de la commande
        $orderStmt = $pdo->prepare('
            SELECT o.*, u.name as user_name, u.email as user_email 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            WHERE o.id = ?
        ');
        $orderStmt->execute([$orderId]);
        $order = $orderStmt->fetch();
        
        if (!$order) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Commande introuvable']);
            exit;
        }
        
        // Générer le PDF de facture
        generateInvoicePDF($order);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
    }
}

function generateInvoicePDF($order)
{
    // Headers pour le téléchargement PDF
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="facture_' . $order['id'] . '.pdf"');
    
    // Créer une facture HTML simple qui peut être convertie en PDF
    $invoiceHtml = generateInvoiceHTML($order);
    
    // Pour une vraie application, vous devriez utiliser une librairie comme TCPDF ou DOMPDF
    // Ici, on va retourner du HTML pour la démo
    header('Content-Type: text/html; charset=utf-8');
    echo $invoiceHtml;
}

function generateInvoiceHTML($order)
{
    $invoiceNumber = 'FACT-' . str_pad($order['id'], 6, '0', STR_PAD_LEFT);
    $invoiceDate = date('d/m/Y', strtotime($order['created_at']));
    $tvaRate = 20; // TVA à 20%
    $subtotal = $order['total_amount'];
    $tvaAmount = $subtotal * ($tvaRate / 100);
    $totalTTC = $subtotal + $tvaAmount;
    
    return '
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Facture ' . $invoiceNumber . '</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            .header { border-bottom: 2px solid #eab308; padding-bottom: 20px; margin-bottom: 30px; }
            .company-info { text-align: right; }
            .invoice-info { margin: 20px 0; }
            .client-info { background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 20px 0; }
            table { width: 100%; border-collapse: collapse; margin: 20px 0; }
            th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
            th { background: #f8f9fa; font-weight: bold; }
            .total-row { font-weight: bold; background: #eab308; color: white; }
            .footer { margin-top: 40px; text-align: center; color: #666; }
            .currency { font-weight: bold; color: #eab308; }
        </style>
    </head>
    <body>
        <div class="header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1 style="color: #eab308; margin: 0;">BIJUVIA</h1>
                    <p style="margin: 5px 0; color: #666;">Bijouterie de Luxe</p>
                </div>
                <div class="company-info">
                    <p><strong>BIJUVIA SARL</strong></p>
                    <p>123 Avenue Mohammed V</p>
                    <p>Casablanca, Maroc</p>
                    <p>Tél: +212 522 123 456</p>
                    <p>Email: contact@bijuvia.com</p>
                </div>
            </div>
        </div>

        <div class="invoice-info">
            <h2>FACTURE N° ' . $invoiceNumber . '</h2>
            <p><strong>Date:</strong> ' . $invoiceDate . '</p>
            <p><strong>Statut:</strong> ' . ucfirst($order['status']) . '</p>
        </div>

        <div class="client-info">
            <h3>Facturé à:</h3>
            <p><strong>' . htmlspecialchars($order['user_name']) . '</strong></p>
            <p>Email: ' . htmlspecialchars($order['user_email']) . '</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Total HT</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Commande Bijuvia #' . $order['id'] . '</td>
                    <td>1</td>
                    <td class="currency">' . number_format($subtotal, 2) . ' MAD</td>
                    <td class="currency">' . number_format($subtotal, 2) . ' MAD</td>
                </tr>
            </tbody>
        </table>

        <div style="width: 300px; margin-left: auto;">
            <table style="margin: 0;">
                <tr>
                    <td><strong>Sous-total HT:</strong></td>
                    <td class="currency">' . number_format($subtotal, 2) . ' MAD</td>
                </tr>
                <tr>
                    <td><strong>TVA (' . $tvaRate . '%):</strong></td>
                    <td class="currency">' . number_format($tvaAmount, 2) . ' MAD</td>
                </tr>
                <tr class="total-row">
                    <td><strong>TOTAL TTC:</strong></td>
                    <td><strong>' . number_format($totalTTC, 2) . ' MAD</strong></td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Merci pour votre confiance !</p>
            <p style="font-size: 12px;">Cette facture a été générée automatiquement le ' . date('d/m/Y à H:i') . '</p>
        </div>
    </body>
    </html>';
}

function updateOrderStatus() 
{
    // Vérifier si l'utilisateur est connecté et admin
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Non autorisé']);
        exit;
    }
    
    require '../src/config/database.php';
    
    try {
        // Vérifier si l'utilisateur est admin
        $stmt = $pdo->prepare('SELECT role FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        
        if (!$user || $user['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Accès refusé']);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = $_POST['order_id'] ?? null;
            $status = $_POST['status'] ?? null;
            
            if (!$orderId || !$status) {
                echo json_encode(['success' => false, 'message' => 'Données manquantes']);
                exit;
            }
            
            // Valider le statut (ajout de "rejected")
            $validStatuses = ['pending', 'completed', 'rejected'];
            if (!in_array($status, $validStatuses)) {
                echo json_encode(['success' => false, 'message' => 'Statut invalide']);
                exit;
            }
            
            // Mettre à jour le statut de la commande
            $updateStmt = $pdo->prepare('UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?');
            $updateStmt->execute([$status, $orderId]);
            
            echo json_encode(['success' => true, 'message' => 'Statut mis à jour avec succès']);
        }
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
    }
}

function deleteUser() 
{
    // Vérifier si l'utilisateur est connecté et admin
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Non autorisé']);
        exit;
    }
    
    require '../src/config/database.php';
    
    try {
        // Vérifier si l'utilisateur est admin
        $stmt = $pdo->prepare('SELECT role FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        
        if (!$user || $user['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Accès refusé']);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'] ?? null;
            
            if (!$userId) {
                echo json_encode(['success' => false, 'message' => 'ID utilisateur manquant']);
                exit;
            }
            
            // Ne pas permettre de supprimer son propre compte
            if ($userId == $_SESSION['user_id']) {
                echo json_encode(['success' => false, 'message' => 'Vous ne pouvez pas supprimer votre propre compte']);
                exit;
            }
            
            // Supprimer l'utilisateur
            $deleteStmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
            $deleteStmt->execute([$userId]);
            
            echo json_encode(['success' => true, 'message' => 'Utilisateur supprimé avec succès']);
        }
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
    }
}

function updateUserRole() 
{
    // Vérifier si l'utilisateur est connecté et admin
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Non autorisé']);
        exit;
    }
    
    require '../src/config/database.php';
    
    try {
        // Vérifier si l'utilisateur est admin
        $stmt = $pdo->prepare('SELECT role FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        
        if (!$user || $user['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Accès refusé']);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'] ?? null;
            $role = $_POST['role'] ?? null;
            
            if (!$userId || !$role) {
                echo json_encode(['success' => false, 'message' => 'Données manquantes']);
                exit;
            }
            
            // Valider le rôle
            $validRoles = ['customer', 'admin'];
            if (!in_array($role, $validRoles)) {
                echo json_encode(['success' => false, 'message' => 'Rôle invalide']);
                exit;
            }
            
            // Mettre à jour le rôle
            $updateStmt = $pdo->prepare('UPDATE users SET role = ?, updated_at = NOW() WHERE id = ?');
            $updateStmt->execute([$role, $userId]);
            
            echo json_encode(['success' => true, 'message' => 'Rôle mis à jour avec succès']);
        }
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
    }
}

function addProduct()
{
    // Vérifier si l'utilisateur est connecté et admin
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Non autorisé']);
        exit;
    }
    
    require '../src/config/database.php';
    
    try {
        // Vérifier si l'utilisateur est admin
        $stmt = $pdo->prepare('SELECT role FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        
        if (!$user || $user['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Accès refusé']);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0;
            $stock = $_POST['stock'] ?? 0;
            $category_id = $_POST['category_id'] ?? null;
            
            // Validation simple
            if (empty($name) || empty($description) || $price <= 0) {
                echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs obligatoires']);
                exit;
            }
            
            // Traitement de l'image si présente
            $image_url = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = '../public/assets/images/';
                
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
                    $image_url = '../public/assets/images/' . $file_name;
                }
            }
            
            // Insérer le produit dans la base de données
            $sql = "INSERT INTO products (name, description, price, stock_quantity, category_id, image_url, created_at, updated_at) 
                   VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $description, $price, $stock, $category_id, $image_url]);
            
            $productId = $pdo->lastInsertId();
            
            echo json_encode([
                'success' => true, 
                'message' => 'Produit ajouté avec succès', 
                'product_id' => $productId
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
    }
}

function editProduct()
{
    // Vérifier si l'utilisateur est connecté et admin
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Non autorisé']);
        exit;
    }
    
    require '../src/config/database.php';
    
    try {
        // Vérifier si l'utilisateur est admin
        $stmt = $pdo->prepare('SELECT role FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        
        if (!$user || $user['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Accès refusé']);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $product_id = $_POST['product_id'] ?? null;
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0;
            $stock = $_POST['stock'] ?? 0;
            $category_id = $_POST['category_id'] ?? null;
            
            if (!$product_id) {
                echo json_encode(['success' => false, 'message' => 'ID produit manquant']);
                exit;
            }
            
            // Validation simple
            if (empty($name) || empty($description) || $price <= 0) {
                echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs obligatoires']);
                exit;
            }
            
            // Vérifier si le produit existe
            $checkStmt = $pdo->prepare('SELECT id FROM products WHERE id = ?');
            $checkStmt->execute([$product_id]);
            if (!$checkStmt->fetch()) {
                echo json_encode(['success' => false, 'message' => 'Produit introuvable']);
                exit;
            }
            
            // Traitement de l'image si présente
            $imageUpdateSql = '';
            $imageParams = [];
            
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = '../public/assets/images/';
                
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
                    $image_url = '../public/assets/images/' . $file_name;
                    $imageUpdateSql = ', image_url = ?';
                    $imageParams[] = $image_url;
                }
            }
            
            // Mettre à jour le produit
            $sql = "UPDATE products SET name = ?, description = ?, price = ?, 
                   stock_quantity = ?, category_id = ?, updated_at = NOW()" . $imageUpdateSql . " WHERE id = ?";
            
            $params = [$name, $description, $price, $stock, $category_id];
            if (!empty($imageParams)) {
                $params = array_merge($params, $imageParams);
            }
            $params[] = $product_id;
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            echo json_encode(['success' => true, 'message' => 'Produit mis à jour avec succès']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
    }
}

function deleteProduct()
{
    // Vérifier si l'utilisateur est connecté et admin
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Non autorisé']);
        exit;
    }
    
    require '../src/config/database.php';
    
    try {
        // Vérifier si l'utilisateur est admin
        $stmt = $pdo->prepare('SELECT role FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        
        if (!$user || $user['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Accès refusé']);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product_id = $_POST['product_id'] ?? null;
            
            if (!$product_id) {
                echo json_encode(['success' => false, 'message' => 'ID produit manquant']);
                exit;
            }
            
            // Supprimer le produit
            $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
            $stmt->execute([$product_id]);
            
            echo json_encode(['success' => true, 'message' => 'Produit supprimé avec succès']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
    }
}

function addCategory()
{
    // Vérifier si l'utilisateur est connecté et admin
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Non autorisé']);
        exit;
    }
    
    require '../src/config/database.php';
    
    try {
        // Vérifier si l'utilisateur est admin
        $stmt = $pdo->prepare('SELECT role FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        
        if (!$user || $user['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Accès refusé']);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            
            if (empty($name)) {
                echo json_encode(['success' => false, 'message' => 'Le nom de la catégorie est obligatoire']);
                exit;
            }
            
            // Vérifier si la catégorie existe déjà
            $checkStmt = $pdo->prepare('SELECT id FROM categories WHERE name = ?');
            $checkStmt->execute([$name]);
            if ($checkStmt->fetch()) {
                echo json_encode(['success' => false, 'message' => 'Cette catégorie existe déjà']);
                exit;
            }
            
            // Ajouter la catégorie
            $stmt = $pdo->prepare('INSERT INTO categories (name, description, created_at, updated_at) VALUES (?, ?, NOW(), NOW())');
            $stmt->execute([$name, $description]);
            
            $categoryId = $pdo->lastInsertId();
            
            echo json_encode([
                'success' => true, 
                'message' => 'Catégorie ajoutée avec succès', 
                'category_id' => $categoryId
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
    }
}

?> 