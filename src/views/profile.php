<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - Bijuvia</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    },
                    colors: {
                        'primary': {
                            50: '#fefce8',
                            100: '#fef9c3',
                            500: '#eab308',
                            600: '#ca8a04',
                            700: '#a16207',
                        },
                        'neutral': {
                            50: '#fafafa',
                            100: '#f5f5f5',
                            200: '#e5e5e5',
                            300: '#d4d4d4',
                            400: '#a3a3a3',
                            500: '#737373',
                            600: '#525252',
                            700: '#404040',
                            800: '#262626',
                            900: '#171717',
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.3s ease-out',
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(20px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.8);
        }
        
        .card-modern {
            border: 1px solid rgba(229, 229, 229, 0.3);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
        
        .input-modern {
            transition: all 0.2s ease-in-out;
        }
        
        .input-modern:focus {
            outline: none;
            border-color: #eab308;
            box-shadow: 0 0 0 3px rgba(234, 179, 8, 0.1);
        }
        
        .btn-modern {
            transition: all 0.2s ease-in-out;
        }
        
        .btn-modern:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(234, 179, 8, 0.3);
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .status-completed {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-cancelled {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body class="bg-neutral-50 font-inter">
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main class="min-h-screen pt-24 pb-16">
        <div class="container mx-auto px-4 max-w-7xl">
            <!-- Header Section -->
            <div class="mb-8 animate-fade-in">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-2">Mon Profil</h1>
                        <p class="text-neutral-600">Gérez vos informations personnelles et suivez vos commandes</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <div class="flex items-center space-x-3 text-sm text-neutral-500">
                            <span class="flex items-center">
                                <i class="fas fa-calendar mr-2"></i>
                                Membre depuis <?php echo date('F Y', strtotime($user['created_at'])); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Profile Information Card -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl card-modern p-6 md:p-8 animate-slide-up">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-neutral-900">Informations personnelles</h2>
                            <div class="flex items-center space-x-2 text-sm text-neutral-500">
                                <i class="fas fa-shield-alt text-primary-500"></i>
                                <span>Sécurisé</span>
                            </div>
                        </div>

                        <!-- Success/Error Messages -->
                        <div id="message-container" class="hidden mb-6"></div>

                        <form id="profile-form" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-neutral-700 mb-2">
                                        Nom complet
                                    </label>
                                    <input 
                                        type="text" 
                                        id="name" 
                                        name="name" 
                                        value="<?php echo htmlspecialchars($user['name']); ?>"
                                        class="w-full px-4 py-3 border border-neutral-200 rounded-xl input-modern bg-neutral-50 focus:bg-white"
                                        required
                                    >
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-neutral-700 mb-2">
                                        Adresse email
                                    </label>
                                    <input 
                                        type="email" 
                                        id="email" 
                                        name="email" 
                                        value="<?php echo htmlspecialchars($user['email']); ?>"
                                        class="w-full px-4 py-3 border border-neutral-200 rounded-xl input-modern bg-neutral-50 focus:bg-white"
                                        required
                                    >
                                </div>
                            </div>

                            <!-- Password Change Section -->
                            <div class="border-t border-neutral-200 pt-6">
                                <h3 class="text-lg font-medium text-neutral-900 mb-4">Changer le mot de passe</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label for="current_password" class="block text-sm font-medium text-neutral-700 mb-2">
                                            Mot de passe actuel
                                        </label>
                                        <input 
                                            type="password" 
                                            id="current_password" 
                                            name="current_password"
                                            class="w-full px-4 py-3 border border-neutral-200 rounded-xl input-modern bg-neutral-50 focus:bg-white"
                                            placeholder="••••••••"
                                        >
                                    </div>
                                    <div>
                                        <label for="new_password" class="block text-sm font-medium text-neutral-700 mb-2">
                                            Nouveau mot de passe
                                        </label>
                                        <input 
                                            type="password" 
                                            id="new_password" 
                                            name="new_password"
                                            class="w-full px-4 py-3 border border-neutral-200 rounded-xl input-modern bg-neutral-50 focus:bg-white"
                                            placeholder="••••••••"
                                        >
                                    </div>
                                    <div>
                                        <label for="confirm_password" class="block text-sm font-medium text-neutral-700 mb-2">
                                            Confirmer le mot de passe
                                        </label>
                                        <input 
                                            type="password" 
                                            id="confirm_password" 
                                            name="confirm_password"
                                            class="w-full px-4 py-3 border border-neutral-200 rounded-xl input-modern bg-neutral-50 focus:bg-white"
                                            placeholder="••••••••"
                                        >
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end pt-4">
                                <button 
                                    type="submit" 
                                    class="px-8 py-3 bg-primary-500 text-white font-medium rounded-xl btn-modern"
                                >
                                    <i class="fas fa-save mr-2"></i>
                                    Sauvegarder les modifications
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- User Stats Card -->
                    <div class="bg-white rounded-2xl card-modern p-6 animate-slide-up">
                        <h3 class="text-lg font-semibold text-neutral-900 mb-4">Statistiques</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-neutral-600">Commandes totales</span>
                                <span class="font-semibold text-neutral-900"><?php echo count($orders); ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-neutral-600">Statut compte</span>
                                <span class="status-badge status-completed">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Actif
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-neutral-600">Type de compte</span>
                                <span class="font-semibold text-primary-600 capitalize"><?php echo $user['role']; ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions Card -->
                    <div class="bg-white rounded-2xl card-modern p-6 animate-slide-up">
                        <h3 class="text-lg font-semibold text-neutral-900 mb-4">Actions rapides</h3>
                        <div class="space-y-3">
                            <a href="?route=products" class="flex items-center p-3 rounded-xl border border-neutral-200 text-neutral-700 transition-colors duration-200 hover:bg-neutral-50">
                                <i class="fas fa-shopping-bag mr-3 text-primary-500"></i>
                                Parcourir les produits
                            </a>
                            <a href="?route=cart" class="flex items-center p-3 rounded-xl border border-neutral-200 text-neutral-700 transition-colors duration-200 hover:bg-neutral-50">
                                <i class="fas fa-shopping-cart mr-3 text-primary-500"></i>
                                Voir le panier
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders History Section -->
            <?php if (!empty($orders)): ?>
            <div class="mt-12">
                <div class="bg-white rounded-2xl card-modern p-6 md:p-8 animate-slide-up">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-neutral-900">Historique des commandes</h2>
                        <span class="text-sm text-neutral-500"><?php echo count($orders); ?> commande(s)</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-neutral-200">
                                    <th class="text-left py-3 px-4 font-medium text-neutral-700">Commande</th>
                                    <th class="text-left py-3 px-4 font-medium text-neutral-700">Date</th>
                                    <th class="text-left py-3 px-4 font-medium text-neutral-700">Montant</th>
                                    <th class="text-left py-3 px-4 font-medium text-neutral-700">Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                <tr class="border-b border-neutral-100 last:border-0">
                                    <td class="py-4 px-4">
                                        <span class="font-medium text-neutral-900">#<?php echo $order['id']; ?></span>
                                    </td>
                                    <td class="py-4 px-4 text-neutral-600">
                                        <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="font-semibold text-neutral-900"><?php echo number_format($order['total_amount'], 2); ?> €</span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <?php
                                        $statusClass = 'status-pending';
                                        $statusIcon = 'fas fa-clock';
                                        $statusText = 'En attente';
                                        
                                        switch ($order['status']) {
                                            case 'completed':
                                                $statusClass = 'status-completed';
                                                $statusIcon = 'fas fa-check-circle';
                                                $statusText = 'Complétée';
                                                break;
                                            case 'cancelled':
                                                $statusClass = 'status-cancelled';
                                                $statusIcon = 'fas fa-times-circle';
                                                $statusText = 'Annulée';
                                                break;
                                        }
                                        ?>
                                        <span class="status-badge <?php echo $statusClass; ?>">
                                            <i class="<?php echo $statusIcon; ?> mr-1"></i>
                                            <?php echo $statusText; ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="mt-12">
                <div class="bg-white rounded-2xl card-modern p-8 text-center animate-slide-up">
                    <i class="fas fa-shopping-bag text-5xl text-neutral-300 mb-4"></i>
                    <h3 class="text-lg font-semibold text-neutral-900 mb-2">Aucune commande</h3>
                    <p class="text-neutral-600 mb-6">Vous n'avez pas encore passé de commande.</p>
                    <a href="?route=products" class="inline-flex items-center px-6 py-3 bg-primary-500 text-white font-medium rounded-xl btn-modern">
                        <i class="fas fa-shopping-bag mr-2"></i>
                        Découvrir nos produits
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('profile-form');
            const messageContainer = document.getElementById('message-container');

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(form);
                const submitButton = form.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;
                
                // Disable button and show loading
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sauvegarde...';
                
                fetch('?route=update-profile', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    showMessage(data.message, data.success ? 'success' : 'error');
                    
                    if (data.success) {
                        // Clear password fields
                        document.getElementById('current_password').value = '';
                        document.getElementById('new_password').value = '';
                        document.getElementById('confirm_password').value = '';
                    }
                })
                .catch(error => {
                    showMessage('Une erreur est survenue lors de la mise à jour.', 'error');
                })
                .finally(() => {
                    // Re-enable button
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                });
            });

            function showMessage(message, type) {
                messageContainer.className = `mb-6 p-4 rounded-xl ${type === 'success' ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200'}`;
                messageContainer.innerHTML = `
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                    ${message}
                `;
                messageContainer.classList.remove('hidden');
                
                // Auto-hide success messages
                if (type === 'success') {
                    setTimeout(() => {
                        messageContainer.classList.add('hidden');
                    }, 5000);
                }
            }
        });
    </script>
</body>
</html> 