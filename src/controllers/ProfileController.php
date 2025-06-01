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

?> 