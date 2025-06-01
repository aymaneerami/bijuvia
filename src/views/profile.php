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
        
        .card-hover-effect {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .card-hover-effect::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(234, 179, 8, 0.1), transparent);
            transition: left 0.6s;
        }
        
        .card-hover-effect:hover::before {
            left: 100%;
        }
        
        .card-hover-effect:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-color: #eab308;
        }
        
        .invoice-ping {
            animation: ping 2s cubic-bezier(0, 0, 0.2, 1) infinite;
        }
        
        @keyframes ping {
            75%, 100% {
                transform: scale(2);
                opacity: 0;
            }
        }
        
        .shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }
        
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        
        .number-counter {
            animation: countUp 1s ease-out;
        }
        
        @keyframes countUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .glassmorphism {
            background: rgba(255, 255, 255, 0.25);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.18);
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
                            <?php if (isset($isAdmin) && $isAdmin): ?>
                            <a href="?route=admin" class="flex items-center p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 transition-all duration-200 hover:bg-red-100">
                                <i class="fas fa-cog mr-3 text-red-500"></i>
                                <div>
                                    <div class="font-medium">Panel d'Administration</div>
                                    <div class="text-xs text-red-600">Gérer la boutique</div>
                                </div>
                            </a>
                            <?php endif; ?>
                            
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
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-2xl font-bold text-neutral-900 flex items-center">
                            <i class="fas fa-history mr-3 text-primary-500"></i>
                            Historique des commandes
                        </h2>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-neutral-500"><?php echo count($orders); ?> commande(s)</span>
                            <div class="h-1 w-12 bg-primary-500 rounded-full"></div>
                        </div>
                    </div>

                    <div class="grid gap-6">
                        <?php foreach ($orders as $order): ?>
                        <div class="border border-neutral-200 rounded-2xl p-6 card-hover-effect">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                <!-- Order Info -->
                                <div class="flex-1">
                                    <div class="flex items-center space-x-4 mb-3">
                                        <div class="flex items-center space-x-2">
                                            <i class="fas fa-receipt text-primary-500"></i>
                                            <span class="font-bold text-lg text-neutral-900">#<?php echo $order['id']; ?></span>
                                        </div>
                                        
                                        <?php
                                        $statusClass = 'status-pending';
                                        $statusIcon = 'fas fa-clock';
                                        $statusText = '⏳ En attente';
                                        
                                        switch ($order['status']) {
                                            case 'completed':
                                                $statusClass = 'status-completed';
                                                $statusIcon = 'fas fa-check-circle';
                                                $statusText = '✅ Complétée';
                                                break;
                                            case 'rejected':
                                                $statusClass = 'status-cancelled';
                                                $statusIcon = 'fas fa-times-circle';
                                                $statusText = '❌ Rejetée';
                                                break;
                                            case 'cancelled':
                                                $statusClass = 'status-cancelled';
                                                $statusIcon = 'fas fa-times-circle';
                                                $statusText = '🚫 Annulée';
                                                break;
                                        }
                                        ?>
                                        <span class="status-badge <?php echo $statusClass; ?>">
                                            <i class="<?php echo $statusIcon; ?> mr-1"></i>
                                            <?php echo $statusText; ?>
                                        </span>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                        <div class="flex items-center space-x-2">
                                            <i class="fas fa-calendar text-neutral-400"></i>
                                            <span class="text-neutral-600">
                                                <?php echo date('d/m/Y à H:i', strtotime($order['created_at'])); ?>
                                            </span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <i class="fas fa-money-bill-wave text-neutral-400"></i>
                                            <span class="font-semibold text-primary-600 text-lg">
                                                <?php echo number_format($order['total_amount'], 2); ?> MAD
                                            </span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <i class="fas fa-credit-card text-neutral-400"></i>
                                            <span class="text-neutral-600">Paiement en ligne</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex flex-col sm:flex-row gap-3 lg:ml-6">
                                    <a href="?route=user-order-details&id=<?php echo $order['id']; ?>" 
                                       class="inline-flex items-center justify-center px-4 py-2 border-2 border-neutral-200 text-neutral-700 font-medium rounded-xl btn-modern">
                                        <i class="fas fa-eye mr-2"></i>
                                        Voir détails
                                    </a>
                                    
                                    <?php if ($order['status'] === 'completed'): ?>
                                    <div class="flex gap-2">
                                        <a href="?route=user-generate-invoice&id=<?php echo $order['id']; ?>" 
                                           class="inline-flex items-center justify-center px-4 py-2 bg-primary-500 text-white font-medium rounded-xl btn-modern"
                                           target="_blank">
                                            <i class="fas fa-file-invoice mr-2"></i>
                                            Facture
                                        </a>
                                        <button onclick="previewOrderInvoice(<?php echo $order['id']; ?>)" 
                                                class="inline-flex items-center justify-center px-3 py-2 bg-green-500 text-white font-medium rounded-xl btn-modern">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <?php else: ?>
                                    <div class="inline-flex items-center justify-center px-4 py-2 bg-neutral-100 text-neutral-500 font-medium rounded-xl cursor-not-allowed">
                                        <i class="fas fa-clock mr-2"></i>
                                        Facture en attente
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Price Breakdown for Completed Orders -->
                            <?php if ($order['status'] === 'completed'): ?>
                            <div class="mt-4 pt-4 border-t border-neutral-200">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                    <?php
                                    $tvaRate = 20;
                                    $subtotal = $order['total_amount'];
                                    $tvaAmount = $subtotal * ($tvaRate / 100);
                                    $totalTTC = $subtotal + $tvaAmount;
                                    ?>
                                    <div class="flex justify-between">
                                        <span class="text-neutral-600">Sous-total HT:</span>
                                        <span class="font-medium"><?php echo number_format($subtotal, 2); ?> MAD</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-neutral-600">TVA (<?php echo $tvaRate; ?>%):</span>
                                        <span class="font-medium"><?php echo number_format($tvaAmount, 2); ?> MAD</span>
                                    </div>
                                    <div class="flex justify-between font-bold text-primary-600">
                                        <span>Total TTC:</span>
                                        <span><?php echo number_format($totalTTC, 2); ?> MAD</span>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Pagination or Load More -->
                    <?php if (count($orders) > 5): ?>
                    <div class="mt-8 text-center">
                        <button class="inline-flex items-center px-6 py-3 border-2 border-primary-200 text-primary-700 font-medium rounded-xl btn-modern">
                            <i class="fas fa-chevron-down mr-2"></i>
                            Voir plus de commandes
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php else: ?>
            <div class="mt-12">
                <div class="bg-white rounded-2xl card-modern p-8 text-center animate-slide-up">
                    <div class="w-24 h-24 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-shopping-bag text-4xl text-neutral-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-neutral-900 mb-3">Aucune commande trouvée</h3>
                    <p class="text-neutral-600 mb-8 max-w-md mx-auto">
                        Vous n'avez pas encore passé de commande. Découvrez notre collection de bijoux exceptionnels.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="?route=products" class="inline-flex items-center px-8 py-4 bg-primary-500 text-white font-semibold rounded-xl btn-modern">
                            <i class="fas fa-shopping-bag mr-3"></i>
                            Découvrir nos produits
                        </a>
                        <a href="?route=categories" class="inline-flex items-center px-8 py-4 border-2 border-neutral-200 text-neutral-700 font-medium rounded-xl btn-modern">
                            <i class="fas fa-th-large mr-3"></i>
                            Parcourir les catégories
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Invoice Preview Modal -->
    <div id="invoice-preview-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-5xl max-h-[90vh] overflow-auto card-modern">
            <div class="sticky top-0 bg-white border-b border-neutral-200 p-6 flex items-center justify-between rounded-t-3xl z-10">
                <h3 class="text-xl font-bold flex items-center">
                    <i class="fas fa-file-invoice text-primary-500 mr-3"></i>
                    Aperçu de la facture
                </h3>
                <button onclick="closeOrderInvoicePreview()" class="text-neutral-500 hover:text-neutral-700 p-2 rounded-full transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="invoice-modal-content" class="p-6">
                <div class="flex items-center justify-center py-12">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-500"></div>
                </div>
            </div>
        </div>
    </div>

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
        
        // Invoice Preview Functions
        function previewOrderInvoice(orderId) {
            const modal = document.getElementById('invoice-preview-modal');
            const content = document.getElementById('invoice-modal-content');
            
            // Show modal with loading
            modal.classList.remove('hidden');
            content.innerHTML = `
                <div class="flex items-center justify-center py-12">
                    <div class="text-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-500 mx-auto mb-4"></div>
                        <p class="text-neutral-600">Génération de l'aperçu...</p>
                    </div>
                </div>
            `;
            
            // Simulate loading and generate preview
            setTimeout(() => {
                content.innerHTML = generateOrderInvoicePreview(orderId);
            }, 1000);
        }

        function closeOrderInvoicePreview() {
            document.getElementById('invoice-preview-modal').classList.add('hidden');
        }

        function generateOrderInvoicePreview(orderId) {
            // This would normally fetch order data from the server
            // For now, we'll use placeholder data
            return `
                <div class="space-y-8">
                    <div class="text-center border-b border-neutral-200 pb-6">
                        <h1 class="text-4xl font-black text-primary-500 mb-2">BIJUVIA</h1>
                        <p class="text-neutral-600 text-lg">Bijouterie de Luxe • Casablanca</p>
                        <div class="mt-4 inline-flex items-center px-4 py-2 bg-primary-100 text-primary-800 rounded-full text-sm font-medium">
                            <i class="fas fa-certificate mr-2"></i>
                            Facture Officielle
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div class="glassmorphism rounded-2xl p-6">
                                <h3 class="text-lg font-bold text-neutral-900 mb-4 flex items-center">
                                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                    Informations Facture
                                </h3>
                                <div class="space-y-3 text-sm">
                                    <div class="flex justify-between items-center">
                                        <span class="text-neutral-600">Numéro:</span>
                                        <span class="font-mono font-bold">FACT-${String(orderId).padStart(6, '0')}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-neutral-600">Date émission:</span>
                                        <span class="font-medium">${new Date().toLocaleDateString('fr-FR')}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-neutral-600">Statut:</span>
                                        <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Validée
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-neutral-600">Devise:</span>
                                        <span class="font-medium">MAD (Dirham)</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="glassmorphism rounded-2xl p-6">
                                <h3 class="text-lg font-bold text-neutral-900 mb-4 flex items-center">
                                    <i class="fas fa-user text-green-500 mr-2"></i>
                                    Informations Client
                                </h3>
                                <div class="space-y-3 text-sm">
                                    <div class="flex justify-between items-center">
                                        <span class="text-neutral-600">Nom:</span>
                                        <span class="font-medium"><?php echo htmlspecialchars($_SESSION['name'] ?? 'Client'); ?></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-neutral-600">ID Client:</span>
                                        <span class="font-mono"><?php echo $_SESSION['user_id'] ?? '000'; ?></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-neutral-600">Commande:</span>
                                        <span class="font-bold text-primary-600">#${orderId}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border border-neutral-200 rounded-2xl overflow-hidden shadow-sm">
                        <div class="bg-neutral-50 px-6 py-4">
                            <h3 class="font-bold text-neutral-900 flex items-center">
                                <i class="fas fa-list-alt mr-2 text-primary-500"></i>
                                Détail des articles
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-neutral-100">
                                    <tr>
                                        <th class="text-left p-4 font-semibold text-neutral-700">Description</th>
                                        <th class="text-center p-4 font-semibold text-neutral-700">Qté</th>
                                        <th class="text-right p-4 font-semibold text-neutral-700">Prix HT</th>
                                        <th class="text-right p-4 font-semibold text-neutral-700">Total HT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-t border-neutral-200">
                                        <td class="p-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-gem text-primary-600"></i>
                                                </div>
                                                <div>
                                                    <div class="font-semibold text-neutral-900">Commande Bijuvia #${orderId}</div>
                                                    <div class="text-sm text-neutral-500">Collection premium - Bijoux sélectionnés</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4 text-center font-medium">1</td>
                                        <td class="p-4 text-right font-semibold text-primary-600">XXX.XX MAD</td>
                                        <td class="p-4 text-right font-bold text-neutral-900">XXX.XX MAD</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-neutral-50 rounded-2xl p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-neutral-600">💰 Sous-total HT:</span>
                                <span class="font-semibold">XXX.XX MAD</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-neutral-600">📊 TVA (20%):</span>
                                <span class="font-semibold">XXX.XX MAD</span>
                            </div>
                            <div class="border-t border-neutral-300 pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold text-neutral-900">🎯 TOTAL TTC:</span>
                                    <span class="text-2xl font-black text-primary-600">XXX.XX MAD</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center space-y-4">
                        <div class="text-neutral-500">
                            <p class="flex items-center justify-center mb-2">
                                <i class="fas fa-heart text-red-500 mr-2"></i>
                                <strong>Merci pour votre confiance !</strong>
                            </p>
                            <p class="text-sm">
                                📍 BIJUVIA SARL • 123 Avenue Mohammed V, Casablanca<br>
                                📞 +212 522 123 456 • 📧 contact@bijuvia.com
                            </p>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-3 justify-center pt-6">
                            <a href="?route=user-generate-invoice&id=${orderId}" target="_blank"
                               class="inline-flex items-center px-6 py-3 bg-primary-500 text-white font-semibold rounded-xl btn-modern">
                                <i class="fas fa-download mr-2"></i>
                                Télécharger PDF
                            </a>
                            <button onclick="window.print()" 
                                    class="inline-flex items-center px-6 py-3 border-2 border-neutral-300 text-neutral-700 font-medium rounded-xl btn-modern">
                                <i class="fas fa-print mr-2"></i>
                                Imprimer
                            </button>
                        </div>
                    </div>

                    <div class="text-center text-xs text-neutral-400 pt-4 border-t border-neutral-200">
                        <p>Cette facture a été générée automatiquement le ${new Date().toLocaleDateString('fr-FR')} à ${new Date().toLocaleTimeString('fr-FR')}</p>
                    </div>
                </div>
            `;
        }

        // Close modal when clicking outside
        document.getElementById('invoice-preview-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeOrderInvoicePreview();
            }
        });

        // Number animation effect
        function animateNumber(element, target) {
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                element.textContent = Math.floor(current);
            }, 20);
        }

        // Initialize number animations on page load
        document.addEventListener('DOMContentLoaded', function() {
            const numberElements = document.querySelectorAll('.number-counter');
            numberElements.forEach(el => {
                const target = parseInt(el.textContent);
                el.textContent = '0';
                animateNumber(el, target);
            });
        });
    </script>
</body>
</html> 