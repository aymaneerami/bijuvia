<?php
// Script temporaire pour créer un utilisateur admin
// À supprimer après usage pour des raisons de sécurité

session_start();
require_once '../src/config/database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'make_admin') {
        $userId = (int)$_POST['user_id'];
        
        try {
            $stmt = $pdo->prepare('UPDATE users SET role = "admin" WHERE id = ?');
            $stmt->execute([$userId]);
            $message = "✅ Utilisateur ID $userId est maintenant administrateur.";
        } catch (PDOException $e) {
            $message = "❌ Erreur: " . $e->getMessage();
        }
    }
    
    if ($action === 'create_admin') {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        
        if ($name && $email && $password) {
            try {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare('INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES (?, ?, ?, "admin", NOW(), NOW())');
                $stmt->execute([$name, $email, $hashedPassword]);
                $message = "✅ Nouvel administrateur créé avec succès.";
            } catch (PDOException $e) {
                $message = "❌ Erreur: " . $e->getMessage();
            }
        } else {
            $message = "❌ Tous les champs sont requis.";
        }
    }
}

// Récupérer tous les utilisateurs
try {
    $stmt = $pdo->query('SELECT id, name, email, role FROM users ORDER BY id');
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    $users = [];
    $message = "❌ Erreur lors de la récupération des utilisateurs: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer Admin - Bijuvia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <strong>⚠️ Attention:</strong> Cette page est temporaire et doit être supprimée après usage pour des raisons de sécurité.
        </div>

        <h1 class="text-3xl font-bold text-gray-900 mb-8">Gestion des Administrateurs - Bijuvia</h1>
        
        <?php if ($message): ?>
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6">
            <?php echo $message; ?>
        </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Utilisateurs existants -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-users mr-2"></i>Utilisateurs existants
                </h2>
                
                <?php if (!empty($users)): ?>
                <div class="space-y-3">
                    <?php foreach ($users as $user): ?>
                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded">
                        <div>
                            <div class="font-medium"><?php echo htmlspecialchars($user['name']); ?></div>
                            <div class="text-sm text-gray-600"><?php echo htmlspecialchars($user['email']); ?></div>
                            <div class="text-xs">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    <?php echo $user['role'] === 'admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'; ?>">
                                    <?php echo $user['role'] === 'admin' ? 'Admin' : 'Client'; ?>
                                </span>
                            </div>
                        </div>
                        <?php if ($user['role'] !== 'admin'): ?>
                        <form method="POST" class="inline">
                            <input type="hidden" name="action" value="make_admin">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <button type="submit" 
                                    class="px-3 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600 transition duration-200"
                                    onclick="return confirm('Transformer cet utilisateur en admin ?')">
                                <i class="fas fa-user-shield mr-1"></i>Faire Admin
                            </button>
                        </form>
                        <?php else: ?>
                        <span class="text-green-600 text-sm font-medium">
                            <i class="fas fa-check-circle mr-1"></i>Déjà Admin
                        </span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <p class="text-gray-600">Aucun utilisateur trouvé.</p>
                <?php endif; ?>
            </div>

            <!-- Créer nouvel admin -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-user-plus mr-2"></i>Créer un nouvel administrateur
                </h2>
                
                <form method="POST" class="space-y-4">
                    <input type="hidden" name="action" value="create_admin">
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                        <input type="text" id="name" name="name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" name="email" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                        <input type="password" id="password" name="password" required minlength="6"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                        <p class="text-xs text-gray-500 mt-1">Minimum 6 caractères</p>
                    </div>
                    
                    <button type="submit" 
                            class="w-full px-4 py-2 bg-red-500 text-white font-medium rounded-md hover:bg-red-600 transition duration-200">
                        <i class="fas fa-user-shield mr-2"></i>Créer l'administrateur
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-8 text-center">
            <a href="/" class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white font-medium rounded-md hover:bg-yellow-600 transition duration-200">
                <i class="fas fa-home mr-2"></i>Retour à l'accueil
            </a>
        </div>

        <div class="mt-8 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
            <p><strong>Instructions:</strong></p>
            <ol class="list-decimal list-inside mt-2 space-y-1">
                <li>Utilisez cette page pour créer votre premier administrateur</li>
                <li>Une fois terminé, <strong>supprimez ce fichier</strong> (public/create_admin.php)</li>
                <li>Connectez-vous avec votre compte admin sur <a href="?route=login" class="underline">la page de connexion</a></li>
                <li>Accédez au dashboard admin depuis votre profil</li>
            </ol>
        </div>
    </div>
</body>
</html> 