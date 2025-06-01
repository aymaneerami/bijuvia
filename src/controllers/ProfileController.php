<?php

function showProfile() 
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
        
        // Récupérer les commandes de l'utilisateur
        $orderStmt = $pdo->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC');
        $orderStmt->execute([$_SESSION['user_id']]);
        $orders = $orderStmt->fetchAll();
        
        if (!$user) {
            header('Location: ?route=login');
            exit;
        }
        
        // Vérifier si l'utilisateur est admin pour afficher les liens appropriés
        $isAdmin = ($user['role'] === 'admin');
        
    } catch (PDOException $e) {
        $error = "Erreur lors de la récupération des données: " . $e->getMessage();
    }
    
    include __DIR__ . '/../views/profile.php';
}

function updateProfile() 
{
    
    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Non autorisé']);
        exit;
    }
    
    require '../src/config/database.php';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validation
        if (empty($name) || empty($email)) {
            echo json_encode(['success' => false, 'message' => 'Le nom et l\'email sont obligatoires']);
            exit;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Format d\'email invalide']);
            exit;
        }
        
        try {
            // Vérifier si l'email existe déjà pour un autre utilisateur
            $emailCheckStmt = $pdo->prepare('SELECT id FROM users WHERE email = ? AND id != ?');
            $emailCheckStmt->execute([$email, $_SESSION['user_id']]);
            if ($emailCheckStmt->fetch()) {
                echo json_encode(['success' => false, 'message' => 'Cet email est déjà utilisé']);
                exit;
            }
            
            // Récupérer l'utilisateur actuel
            $userStmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
            $userStmt->execute([$_SESSION['user_id']]);
            $user = $userStmt->fetch();
            
            // Si un nouveau mot de passe est fourni
            if (!empty($newPassword)) {
                if (empty($currentPassword)) {
                    echo json_encode(['success' => false, 'message' => 'Mot de passe actuel requis']);
                    exit;
                }
                
                if (!password_verify($currentPassword, $user['password'])) {
                    echo json_encode(['success' => false, 'message' => 'Mot de passe actuel incorrect']);
                    exit;
                }
                
                if (strlen($newPassword) < 6) {
                    echo json_encode(['success' => false, 'message' => 'Le nouveau mot de passe doit contenir au moins 6 caractères']);
                    exit;
                }
                
                if ($newPassword !== $confirmPassword) {
                    echo json_encode(['success' => false, 'message' => 'Les mots de passe ne correspondent pas']);
                    exit;
                }
                
                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                
                // Mettre à jour avec le nouveau mot de passe
                $updateStmt = $pdo->prepare('UPDATE users SET name = ?, email = ?, password = ?, updated_at = NOW() WHERE id = ?');
                $updateStmt->execute([$name, $email, $hashedPassword, $_SESSION['user_id']]);
            } else {
                // Mettre à jour sans changer le mot de passe
                $updateStmt = $pdo->prepare('UPDATE users SET name = ?, email = ?, updated_at = NOW() WHERE id = ?');
                $updateStmt->execute([$name, $email, $_SESSION['user_id']]);
            }
            
            // Mettre à jour la session
            $_SESSION['name'] = $name;
            
            echo json_encode(['success' => true, 'message' => 'Profil mis à jour avec succès']);
            
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()]);
        }
    }
}

function showUserOrderDetails() 
{
    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        header('Location: ?route=login');
        exit;
    }
    
    require '../src/config/database.php';
    
    try {
        $orderId = $_GET['id'] ?? null;
        if (!$orderId) {
            header('Location: ?route=profile');
            exit;
        }
        
        // Récupérer les détails de la commande (vérifier que c'est bien la commande de l'utilisateur)
        $orderStmt = $pdo->prepare('
            SELECT o.*, u.name as user_name, u.email as user_email 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            WHERE o.id = ? AND o.user_id = ?
        ');
        $orderStmt->execute([$orderId, $_SESSION['user_id']]);
        $order = $orderStmt->fetch();
        
        if (!$order) {
            header('Location: ?route=profile');
            exit;
        }
        
        // Vérifier le rôle de l'utilisateur pour les fonctionnalités admin
        $userStmt = $pdo->prepare('SELECT role FROM users WHERE id = ?');
        $userStmt->execute([$_SESSION['user_id']]);
        $userInfo = $userStmt->fetch();
        $isAdmin = ($userInfo && $userInfo['role'] === 'admin');
        
    } catch (PDOException $e) {
        $error = "Erreur lors de la récupération des données: " . $e->getMessage();
        include __DIR__ . '/../views/error.php';
        exit;
    }
    
    include __DIR__ . '/../views/user/order-details.php';
}

function generateUserInvoice()
{
    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Non autorisé']);
        exit;
    }
    
    require '../src/config/database.php';
    
    try {
        $orderId = $_GET['id'] ?? null;
        if (!$orderId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID commande manquant']);
            exit;
        }
        
        // Récupérer les détails de la commande (vérifier que c'est bien la commande de l'utilisateur)
        $orderStmt = $pdo->prepare('
            SELECT o.*, u.name as user_name, u.email as user_email 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            WHERE o.id = ? AND o.user_id = ?
        ');
        $orderStmt->execute([$orderId, $_SESSION['user_id']]);
        $order = $orderStmt->fetch();
        
        if (!$order) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Commande introuvable']);
            exit;
        }
        
        // Vérifier que la commande est complétée
        if ($order['status'] !== 'completed') {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'La facture n\'est disponible que pour les commandes complétées']);
            exit;
        }
        
        // Générer le PDF de facture (réutiliser la fonction admin)
        generateUserInvoicePDF($order);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
    }
}

function generateUserInvoicePDF($order)
{
    // Headers pour le téléchargement PDF
    header('Content-Type: text/html; charset=utf-8');
    
    $invoiceNumber = 'FACT-' . str_pad($order['id'], 6, '0', STR_PAD_LEFT);
    $invoiceDate = date('d/m/Y', strtotime($order['created_at']));
    $tvaRate = 20; // TVA à 20%
    $subtotal = $order['total_amount'];
    $tvaAmount = $subtotal * ($tvaRate / 100);
    $totalTTC = $subtotal + $tvaAmount;
    
    echo '
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Facture ' . $invoiceNumber . '</title>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { 
                font-family: "Inter", -apple-system, BlinkMacSystemFont, sans-serif; 
                line-height: 1.6; 
                color: #1f2937;
                background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 2rem;
            }
            .invoice-container {
                background: white;
                border-radius: 24px;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                overflow: hidden;
                max-width: 800px;
                width: 100%;
                position: relative;
            }
            .invoice-header {
                background: #eab308;
                color: white;
                padding: 3rem 2rem;
                position: relative;
                overflow: hidden;
            }
            .invoice-header::before {
                content: "";
                position: absolute;
                top: -50%;
                right: -50%;
                width: 200%;
                height: 200%;
                background: url("data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.1\"%3E%3Ccircle cx=\"30\" cy=\"30\" r=\"4\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
                animation: float 20s infinite linear;
            }
            @keyframes float {
                0% { transform: translateX(0) translateY(0); }
                100% { transform: translateX(-60px) translateY(-60px); }
            }
            .invoice-title {
                font-size: 3rem;
                font-weight: 800;
                letter-spacing: -0.025em;
                margin-bottom: 0.5rem;
                position: relative;
                z-index: 2;
            }
            .invoice-subtitle {
                font-size: 1.25rem;
                opacity: 0.9;
                position: relative;
                z-index: 2;
            }
            .invoice-content {
                padding: 3rem 2rem;
            }
            .invoice-info {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 3rem;
                margin-bottom: 3rem;
            }
            .info-section h3 {
                font-size: 1.25rem;
                font-weight: 700;
                color: #374151;
                margin-bottom: 1rem;
                border-bottom: 2px solid #eab308;
                padding-bottom: 0.5rem;
            }
            .info-item {
                display: flex;
                justify-content: space-between;
                padding: 0.75rem 0;
                border-bottom: 1px solid #f3f4f6;
            }
            .info-label {
                color: #6b7280;
                font-weight: 500;
            }
            .info-value {
                font-weight: 600;
                color: #111827;
            }
            .invoice-table {
                width: 100%;
                border-collapse: collapse;
                margin: 2rem 0;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }
            .invoice-table th {
                background: #f9fafb;
                padding: 1.5rem 1rem;
                text-align: left;
                font-weight: 700;
                color: #374151;
                border-bottom: 2px solid #eab308;
            }
            .invoice-table td {
                padding: 1.5rem 1rem;
                border-bottom: 1px solid #f3f4f6;
            }
            .totals-section {
                background: #f9fafb;
                border-radius: 16px;
                padding: 2rem;
                margin-top: 2rem;
            }
            .total-row {
                display: flex;
                justify-content: space-between;
                padding: 0.75rem 0;
                border-bottom: 1px solid #e5e7eb;
            }
            .total-row:last-child {
                border-bottom: none;
                background: #eab308;
                color: white;
                margin: 1rem -2rem -2rem;
                padding: 1.5rem 2rem;
                border-radius: 0 0 16px 16px;
                font-size: 1.25rem;
                font-weight: 800;
            }
            .currency {
                color: #eab308;
                font-weight: 700;
            }
            .footer {
                text-align: center;
                padding: 2rem;
                border-top: 1px solid #e5e7eb;
                background: #f9fafb;
                color: #6b7280;
            }
            .print-btn {
                position: fixed;
                top: 2rem;
                right: 2rem;
                background: #eab308;
                color: white;
                border: none;
                padding: 1rem 2rem;
                border-radius: 12px;
                font-weight: 600;
                cursor: pointer;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                transition: transform 0.2s;
            }
            .print-btn:hover {
                transform: translateY(-2px);
            }
            @media print {
                body { background: white; padding: 0; }
                .print-btn { display: none; }
                .invoice-container { box-shadow: none; }
            }
            @media (max-width: 768px) {
                .invoice-info { grid-template-columns: 1fr; gap: 2rem; }
                .invoice-table { font-size: 0.875rem; }
                .invoice-table th, .invoice-table td { padding: 1rem 0.5rem; }
            }
        </style>
    </head>
    <body>
        <button class="print-btn" onclick="window.print()">
            🖨️ Imprimer
        </button>
        
        <div class="invoice-container">
            <div class="invoice-header">
                <h1 class="invoice-title">BIJUVIA</h1>
                <p class="invoice-subtitle">Bijouterie de Luxe • Casablanca</p>
            </div>
            
            <div class="invoice-content">
                <div class="invoice-info">
                    <div class="info-section">
                        <h3>📋 Informations Facture</h3>
                        <div class="info-item">
                            <span class="info-label">Numéro:</span>
                            <span class="info-value">' . $invoiceNumber . '</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Date d\'émission:</span>
                            <span class="info-value">' . $invoiceDate . '</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Statut:</span>
                            <span class="info-value" style="color: #059669;">✅ ' . ucfirst($order['status']) . '</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Devise:</span>
                            <span class="info-value">Dirham Marocain (MAD)</span>
                        </div>
                    </div>
                    
                    <div class="info-section">
                        <h3>👤 Client</h3>
                        <div class="info-item">
                            <span class="info-label">Nom:</span>
                            <span class="info-value">' . htmlspecialchars($order['user_name']) . '</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email:</span>
                            <span class="info-value">' . htmlspecialchars($order['user_email']) . '</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Commande:</span>
                            <span class="info-value">#' . $order['id'] . '</span>
                        </div>
                    </div>
                </div>
                
                <table class="invoice-table">
                    <thead>
                        <tr>
                            <th>📦 Description</th>
                            <th>🔢 Quantité</th>
                            <th>💰 Prix unitaire HT</th>
                            <th>💵 Total HT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <strong>Commande Bijuvia #' . $order['id'] . '</strong><br>
                                <small style="color: #6b7280;">Produits de bijouterie sélectionnés</small>
                            </td>
                            <td>1</td>
                            <td class="currency">' . number_format($subtotal, 2) . ' MAD</td>
                            <td class="currency">' . number_format($subtotal, 2) . ' MAD</td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="totals-section">
                    <div class="total-row">
                        <span><strong>💰 Sous-total HT:</strong></span>
                        <span class="currency">' . number_format($subtotal, 2) . ' MAD</span>
                    </div>
                    <div class="total-row">
                        <span><strong>📊 TVA (' . $tvaRate . '%):</strong></span>
                        <span class="currency">' . number_format($tvaAmount, 2) . ' MAD</span>
                    </div>
                    <div class="total-row">
                        <span><strong>🎯 TOTAL TTC:</strong></span>
                        <span><strong>' . number_format($totalTTC, 2) . ' MAD</strong></span>
                    </div>
                </div>
            </div>
            
            <div class="footer">
                <p><strong>🙏 Merci pour votre confiance !</strong></p>
                <p style="margin-top: 0.5rem;">
                    📍 BIJUVIA SARL • 123 Avenue Mohammed V, Casablanca<br>
                    📞 +212 522 123 456 • 📧 contact@bijuvia.com
                </p>
                <p style="margin-top: 1rem; font-size: 0.875rem;">
                    Cette facture a été générée automatiquement le ' . date('d/m/Y à H:i') . '
                </p>
            </div>
        </div>
    </body>
    </html>';
}

?> 