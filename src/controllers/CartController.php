<?php
require_once __DIR__ . '/../config/config.php'; // Charger la configuration de la base de données

// Fonction pour afficher le contenu du panier
function showCart() {
    global $pdo;

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $userId = $_SESSION['user_id'] ?? null;

    if (!$userId) {
        header('Location: ?route=login');
        exit();
    }

    // Récupérer les produits dans le panier
    $stmt = $pdo->prepare("
        SELECT p.name, p.price, p.image_url, c.quantity, p.price * c.quantity AS total, c.product_id
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = :user_id
    ");
    $stmt->execute([':user_id' => $userId]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $totalAmount = 0;
    foreach ($cartItems as $item) {
        $totalAmount += $item['total'];
    }

    include __DIR__ . '/../views/cart.php';
}

// Fonction pour ajouter un produit au panier
function addToCart($productId, $quantity = 1) {
    global $pdo;

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $userId = $_SESSION['user_id'] ?? null;

    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté.']);
        return;
    }

    // Vérifier si le produit existe déjà dans le panier
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = :user_id AND product_id = :product_id");
    $stmt->execute([':user_id' => $userId, ':product_id' => $productId]);
    $existingProduct = $stmt->fetch();

    if ($existingProduct) {
        // Si le produit existe, mettre à jour la quantité
        $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + :quantity WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute([
            ':quantity' => $quantity,
            ':user_id' => $userId,
            ':product_id' => $productId
        ]);
    } else {
        // Sinon, ajouter un nouveau produit au panier
        $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)");
        $stmt->execute([
            ':user_id' => $userId,
            ':product_id' => $productId,
            ':quantity' => $quantity
        ]);
    }

    // Test : Vérifiez les données dans la table "cart"
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = :user_id");
    $stmt->execute([':user_id' => $userId]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'cart' => $cartItems]);
}

// Fonction pour supprimer un produit du panier
function removeFromCart($productId)
{
    global $pdo;

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté.']);
        return;
    }

    $stmt = $pdo->prepare("
        DELETE FROM cart 
        WHERE user_id = :user_id AND product_id = :product_id
    ");
    $stmt->execute([':user_id' => $userId, ':product_id' => $productId]);

    echo json_encode(['success' => true]);
}

// Fonction pour vider le panier
function clearCart()
{
    global $pdo;

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté.']);
        return;
    }

    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = :user_id");
    $stmt->execute([':user_id' => $userId]);

    echo json_encode(['success' => true]);
}
function checkout()
{
    global $pdo;

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
        header('Location: ?route=login');
        exit();
    }

    try {
        $stmt = $pdo->prepare("
            SELECT c.product_id, c.quantity, p.name, p.price 
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.user_id = :user_id
        ");
        $stmt->execute([':user_id' => $userId]);
        $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($cartItems)) {
            header('Location: ?route=cart&error=empty');
            exit();
        }

        $totalAmount = 0;
        foreach ($cartItems as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        $stmt = $pdo->prepare("
            INSERT INTO orders (user_id, total_amount, status) 
            VALUES (:user_id, :total_amount, 'pending')
        ");
        $stmt->execute([':user_id' => $userId, ':total_amount' => $totalAmount]);
        $orderId = $pdo->lastInsertId();

        foreach ($cartItems as $item) {
            $stmt = $pdo->prepare("
                INSERT INTO order_details (order_id, product_id, quantity, price, total) 
                VALUES (:order_id, :product_id, :quantity, :price, :total)
            ");
            $stmt->execute([
                ':order_id' => $orderId,
                ':product_id' => $item['product_id'],
                ':quantity' => $item['quantity'],
                ':price' => $item['price'],
                ':total' => $item['price'] * $item['quantity'],
            ]);
        }

        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $userId]);

        // Récupérer l'email de l'utilisateur
        $stmt = $pdo->prepare("SELECT name, email, phone FROM users WHERE id = :user_id");
        $stmt->execute([':user_id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $email = $user['email'];
            $userName = $user['name'];
            $userPhone = $user['phone'];

            // Préparer le contenu de l'email
            $subject = "Confirmation de votre commande - Bijuvia";
            $body = "
                <div style='font-family: Poppins, sans-serif; color: #4a5568; background-color: #f9fafb; padding: 20px;'>
                    <h1 style='color: #fbbf24;'>Merci pour votre commande, $userName !</h1>
                    <p>Nous sommes ravis de vous informer que votre commande a été reçue. Voici les détails :</p>
                    <table style='width: 100%; border-collapse: collapse; margin-top: 20px;'>
                        <thead>
                            <tr style='background-color: #fefce8;'>
                                <th style='text-align: left; padding: 10px; border: 1px solid #e2e8f0;'>Produit</th>
                                <th style='text-align: center; padding: 10px; border: 1px solid #e2e8f0;'>Quantité</th>
                                <th style='text-align: center; padding: 10px; border: 1px solid #e2e8f0;'>Prix</th>
                                <th style='text-align: center; padding: 10px; border: 1px solid #e2e8f0;'>Total</th>
                            </tr>
                        </thead>
                        <tbody>";

            foreach ($cartItems as $item) {
                $productTotal = $item['price'] * $item['quantity'];
                $body .= "
                            <tr>
                                <td style='padding: 10px; border: 1px solid #e2e8f0;'>{$item['name']}</td>
                                <td style='text-align: center; padding: 10px; border: 1px solid #e2e8f0;'>{$item['quantity']}</td>
                                <td style='text-align: center; padding: 10px; border: 1px solid #e2e8f0;'>{$item['price']} MAD</td>
                                <td style='text-align: center; padding: 10px; border: 1px solid #e2e8f0;'>{$productTotal} MAD</td>
                            </tr>";
            }

            $body .= "
                        </tbody>
                    </table>
                    <p style='font-weight: bold; color: #fbbf24;'>Montant total : {$totalAmount} MAD</p>
                    <p>Pour toute question, contactez-nous au <a href='tel:$userPhone' style='color: #4a90e2;'>$userPhone</a> ou via notre <a href='https://wa.me/212654096337' style='color: #25d366;'>WhatsApp</a>.</p>
                    <p>Merci de faire vos achats chez Bijuvia !</p>
                </div>
            ";

            // Envoyer l'email
            sendEmail($email, $subject, $body);
        }

        header('Location: ?route=order-confirmation');
        exit();
    } catch (Exception $e) {
        header('Location: ?route=cart&error=checkout');
        exit();
    }
}
