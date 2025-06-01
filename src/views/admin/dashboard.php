<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel d'Administration - Bijuvia</title>

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
                        },
                        'red': {
                            50: '#fef2f2',
                            100: '#fee2e2',
                            500: '#ef4444',
                            600: '#dc2626',
                            700: '#b91c1c',
                        },
                        'blue': {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        },
                        'green': {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.3s ease-out',
                        'pulse-soft': 'pulseSoft 2s ease-in-out infinite',
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
        
        @keyframes pulseSoft {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        
        .card-admin {
            border: 1px solid rgba(229, 229, 229, 0.3);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            transition: all 0.2s ease-in-out;
        }
        
        .card-admin:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .btn-admin {
            transition: all 0.2s ease-in-out;
        }
        
        .btn-admin:hover {
            transform: translateY(-1px);
        }
        
        .stat-card {
            position: relative;
            overflow: hidden;
        }
        
        .stat-icon {
            position: absolute;
            top: -10px;
            right: -10px;
            font-size: 4rem;
            opacity: 0.1;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease-in-out;
        }
        
        .tab-button {
            transition: all 0.2s ease-in-out;
        }
        
        .tab-button.active {
            background-color: #eab308;
            color: white;
        }
        
        .status-select {
            border-radius: 6px;
            padding: 4px 8px;
            font-size: 0.75rem;
        }
        
        .role-select {
            border-radius: 6px;
            padding: 2px 6px;
            font-size: 0.75rem;
        }
    </style>
</head>
<body class="bg-neutral-50 font-inter">
    <?php include __DIR__ . '/../../includes/header.php'; ?>

    <main class="min-h-screen pt-24 pb-16">
        <div class="container mx-auto px-4 max-w-7xl">
            <!-- Header Section -->
            <div class="mb-8 animate-fade-in">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-2">
                            <i class="fas fa-cog text-red-500 mr-3"></i>
                            Panel d'Administration
                        </h1>
                        <p class="text-neutral-600">Gérez votre boutique en ligne Bijuvia</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <div class="flex items-center space-x-3 text-sm text-neutral-500">
                            <span class="flex items-center">
                                <i class="fas fa-user-shield mr-2 text-red-500"></i>
                                Administrateur: <?php echo htmlspecialchars($_SESSION['name']); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <!-- Total Users -->
                <div class="bg-white rounded-2xl card-admin p-6 stat-card animate-slide-up">
                    <i class="fas fa-users stat-icon text-blue-500"></i>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-neutral-600 text-sm font-medium">Utilisateurs</p>
                            <p class="text-2xl font-bold text-neutral-900"><?php echo $stats['total_users']; ?></p>
                        </div>
                        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-users text-blue-500 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Products -->
                <div class="bg-white rounded-2xl card-admin p-6 stat-card animate-slide-up">
                    <i class="fas fa-box stat-icon text-green-500"></i>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-neutral-600 text-sm font-medium">Produits</p>
                            <p class="text-2xl font-bold text-neutral-900"><?php echo $stats['total_products']; ?></p>
                        </div>
                        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-box text-green-500 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Pending Orders -->
                <div class="bg-white rounded-2xl card-admin p-6 stat-card animate-slide-up">
                    <i class="fas fa-clock stat-icon text-yellow-500"></i>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-neutral-600 text-sm font-medium">En attente</p>
                            <p class="text-2xl font-bold text-neutral-900"><?php echo $stats['pending_orders']; ?></p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-500 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Completed Orders -->
                <div class="bg-white rounded-2xl card-admin p-6 stat-card animate-slide-up">
                    <i class="fas fa-check-circle stat-icon text-green-500"></i>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-neutral-600 text-sm font-medium">Complétées</p>
                            <p class="text-2xl font-bold text-neutral-900"><?php echo $stats['completed_orders']; ?></p>
                        </div>
                        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-500 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Revenue -->
                <div class="bg-white rounded-2xl card-admin p-6 stat-card animate-slide-up">
                    <i class="fas fa-coins stat-icon text-primary-500"></i>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-neutral-600 text-sm font-medium">Revenus</p>
                            <p class="text-xl font-bold text-primary-600"><?php echo number_format($stats['total_revenue'], 2); ?> MAD</p>
                        </div>
                        <div class="w-12 h-12 bg-primary-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-coins text-primary-500 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Message Container -->
            <div id="message-container" class="hidden mb-6"></div>

            <!-- Tabs Navigation -->
            <div class="bg-white rounded-2xl card-admin mb-6 animate-slide-up">
                <div class="flex flex-wrap border-b border-neutral-200">
                    <button class="tab-button active px-6 py-4 font-medium text-sm rounded-t-2xl" data-tab="dashboard">
                        <i class="fas fa-chart-line mr-2"></i>Dashboard
                    </button>
                    <button class="tab-button px-6 py-4 font-medium text-sm text-neutral-600" data-tab="orders">
                        <i class="fas fa-shopping-cart mr-2"></i>Commandes
                        <?php if ($stats['pending_orders'] > 0): ?>
                            <span class="ml-2 px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full"><?php echo $stats['pending_orders']; ?></span>
                        <?php endif; ?>
                    </button>
                    <button class="tab-button px-6 py-4 font-medium text-sm text-neutral-600" data-tab="users">
                        <i class="fas fa-users mr-2"></i>Utilisateurs
                    </button>
                    <button class="tab-button px-6 py-4 font-medium text-sm text-neutral-600" data-tab="products">
                        <i class="fas fa-box mr-2"></i>Produits
                    </button>
                </div>

                <!-- Dashboard Tab -->
                <div id="dashboard" class="tab-content active p-6">
                    <h3 class="text-xl font-semibold text-neutral-900 mb-6">Aperçu rapide</h3>
                    
                    <?php if (!empty($recentOrders)): ?>
                    <div class="mb-8">
                        <h4 class="text-lg font-medium text-neutral-900 mb-4">Dernières commandes</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-neutral-200">
                                        <th class="text-left py-3 px-4 font-medium text-neutral-700">Commande</th>
                                        <th class="text-left py-3 px-4 font-medium text-neutral-700">Client</th>
                                        <th class="text-left py-3 px-4 font-medium text-neutral-700">Montant</th>
                                        <th class="text-left py-3 px-4 font-medium text-neutral-700">Statut</th>
                                        <th class="text-left py-3 px-4 font-medium text-neutral-700">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($recentOrders, 0, 5) as $order): ?>
                                    <tr class="border-b border-neutral-100">
                                        <td class="py-3 px-4 font-medium">#<?php echo $order['id']; ?></td>
                                        <td class="py-3 px-4"><?php echo htmlspecialchars($order['user_name']); ?></td>
                                        <td class="py-3 px-4 font-semibold"><?php echo number_format($order['total_amount'], 2); ?>€</td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                                <?php echo $order['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                    ($order['status'] === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                                <?php echo ucfirst($order['status']); ?>
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-neutral-600">
                                            <?php echo date('d/m/Y', strtotime($order['created_at'])); ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Orders Tab -->
                <div id="orders" class="tab-content p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-neutral-900">Gestion des commandes</h3>
                        <span class="text-sm text-neutral-500"><?php echo count($recentOrders); ?> commande(s)</span>
                    </div>
                    
                    <?php if (!empty($recentOrders)): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-neutral-200">
                                    <th class="text-left py-3 px-4 font-medium text-neutral-700">ID</th>
                                    <th class="text-left py-3 px-4 font-medium text-neutral-700">Client</th>
                                    <th class="text-left py-3 px-4 font-medium text-neutral-700">Email</th>
                                    <th class="text-left py-3 px-4 font-medium text-neutral-700">Montant</th>
                                    <th class="text-left py-3 px-4 font-medium text-neutral-700">Statut</th>
                                    <th class="text-left py-3 px-4 font-medium text-neutral-700">Date</th>
                                    <th class="text-left py-3 px-4 font-medium text-neutral-700">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentOrders as $order): ?>
                                <tr class="border-b border-neutral-100" data-order-id="<?php echo $order['id']; ?>">
                                    <td class="py-3 px-4 font-medium">#<?php echo $order['id']; ?></td>
                                    <td class="py-3 px-4"><?php echo htmlspecialchars($order['user_name']); ?></td>
                                    <td class="py-3 px-4 text-neutral-600"><?php echo htmlspecialchars($order['user_email']); ?></td>
                                    <td class="py-3 px-4 font-semibold"><?php echo number_format($order['total_amount'], 2); ?>€</td>
                                    <td class="py-3 px-4">
                                        <select class="status-select border border-neutral-300 text-xs
                                            <?php echo $order['status'] === 'pending' ? 'bg-yellow-50 text-yellow-800' : 
                                                ($order['status'] === 'completed' ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800'); ?>"
                                            onchange="updateOrderStatus(<?php echo $order['id']; ?>, this.value)">
                                            <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>En attente</option>
                                            <option value="completed" <?php echo $order['status'] === 'completed' ? 'selected' : ''; ?>>Complétée</option>
                                            <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Annulée</option>
                                        </select>
                                    </td>
                                    <td class="py-3 px-4 text-neutral-600">
                                        <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex space-x-2">
                                            <button class="text-blue-600 hover:text-blue-800 transition-colors duration-200" title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-12">
                        <i class="fas fa-shopping-cart text-5xl text-neutral-300 mb-4"></i>
                        <h3 class="text-lg font-semibold text-neutral-900 mb-2">Aucune commande</h3>
                        <p class="text-neutral-600">Les commandes apparaîtront ici.</p>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Users Tab -->
                <div id="users" class="tab-content p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-neutral-900">Gestion des utilisateurs</h3>
                        <span class="text-sm text-neutral-500"><?php echo count($users); ?> utilisateur(s)</span>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-neutral-200">
                                    <th class="text-left py-3 px-4 font-medium text-neutral-700">ID</th>
                                    <th class="text-left py-3 px-4 font-medium text-neutral-700">Nom</th>
                                    <th class="text-left py-3 px-4 font-medium text-neutral-700">Email</th>
                                    <th class="text-left py-3 px-4 font-medium text-neutral-700">Rôle</th>
                                    <th class="text-left py-3 px-4 font-medium text-neutral-700">Inscription</th>
                                    <th class="text-left py-3 px-4 font-medium text-neutral-700">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                <tr class="border-b border-neutral-100" data-user-id="<?php echo $user['id']; ?>">
                                    <td class="py-3 px-4 font-medium"><?php echo $user['id']; ?></td>
                                    <td class="py-3 px-4"><?php echo htmlspecialchars($user['name']); ?></td>
                                    <td class="py-3 px-4 text-neutral-600"><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td class="py-3 px-4">
                                        <select class="role-select border border-neutral-300 text-xs
                                            <?php echo $user['role'] === 'admin' ? 'bg-red-50 text-red-800' : 'bg-blue-50 text-blue-800'; ?>"
                                            onchange="updateUserRole(<?php echo $user['id']; ?>, this.value)"
                                            <?php echo $user['id'] == $_SESSION['user_id'] ? 'disabled' : ''; ?>>
                                            <option value="customer" <?php echo $user['role'] === 'customer' ? 'selected' : ''; ?>>Client</option>
                                            <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                        </select>
                                    </td>
                                    <td class="py-3 px-4 text-neutral-600">
                                        <?php echo date('d/m/Y', strtotime($user['created_at'])); ?>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex space-x-2">
                                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                            <button class="text-red-600 hover:text-red-800 transition-colors duration-200" 
                                                    onclick="deleteUser(<?php echo $user['id']; ?>)" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <?php else: ?>
                                            <span class="text-neutral-400" title="Vous ne pouvez pas supprimer votre propre compte">
                                                <i class="fas fa-user-shield"></i>
                                            </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Products Tab -->
                <div id="products" class="tab-content p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-neutral-900">Gestion des produits</h3>
                        <span class="text-sm text-neutral-500"><?php echo count($products); ?> produit(s)</span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($products as $product): ?>
                        <div class="bg-white rounded-xl border border-neutral-200 p-4">
                            <div class="aspect-square bg-neutral-100 rounded-lg mb-4 flex items-center justify-center">
                                <?php if (!empty($product['image_url'])): ?>
                                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                         alt="<?php echo htmlspecialchars($product['name']); ?>"
                                         class="w-full h-full object-cover rounded-lg">
                                <?php else: ?>
                                    <i class="fas fa-image text-4xl text-neutral-400"></i>
                                <?php endif; ?>
                            </div>
                            <h4 class="font-semibold text-neutral-900 mb-2"><?php echo htmlspecialchars($product['name']); ?></h4>
                            <p class="text-neutral-600 text-sm mb-3 line-clamp-2"><?php echo htmlspecialchars($product['description']); ?></p>
                            <div class="flex items-center justify-between mb-3">
                                <span class="font-bold text-lg text-neutral-900"><?php echo number_format($product['price'], 2); ?>€</span>
                                <span class="text-sm text-neutral-600">Stock: <?php echo $product['stock_quantity']; ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs px-2 py-1 bg-neutral-100 text-neutral-700 rounded">
                                    <?php echo htmlspecialchars($product['category_name'] ?? 'Sans catégorie'); ?>
                                </span>
                                <div class="flex space-x-2">
                                    <button class="text-blue-600 hover:text-blue-800 transition-colors duration-200" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-600 hover:text-red-800 transition-colors duration-200" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>

    <script>
        // Tab functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const tabName = button.getAttribute('data-tab');
                    
                    // Remove active class from all buttons and contents
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabContents.forEach(content => content.classList.remove('active'));
                    
                    // Add active class to clicked button and corresponding content
                    button.classList.add('active');
                    document.getElementById(tabName).classList.add('active');
                });
            });
        });

        // Update order status
        function updateOrderStatus(orderId, status) {
            const formData = new FormData();
            formData.append('order_id', orderId);
            formData.append('status', status);

            fetch('?route=admin-update-order', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                showMessage(data.message, data.success ? 'success' : 'error');
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                showMessage('Erreur lors de la mise à jour du statut.', 'error');
            });
        }

        // Delete user
        function deleteUser(userId) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
                const formData = new FormData();
                formData.append('user_id', userId);

                fetch('?route=admin-delete-user', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    showMessage(data.message, data.success ? 'success' : 'error');
                    if (data.success) {
                        document.querySelector(`tr[data-user-id="${userId}"]`).remove();
                    }
                })
                .catch(error => {
                    showMessage('Erreur lors de la suppression de l\'utilisateur.', 'error');
                });
            }
        }

        // Update user role
        function updateUserRole(userId, role) {
            const formData = new FormData();
            formData.append('user_id', userId);
            formData.append('role', role);

            fetch('?route=admin-update-role', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                showMessage(data.message, data.success ? 'success' : 'error');
            })
            .catch(error => {
                showMessage('Erreur lors de la mise à jour du rôle.', 'error');
            });
        }

        // Show message function
        function showMessage(message, type) {
            const messageContainer = document.getElementById('message-container');
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
    </script>
</body>
</html> 