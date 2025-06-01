<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande #<?php echo $order['id']; ?> - Bijuvia</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
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
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
        }
        
        .card-modern {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            border-radius: 2rem;
            font-size: 0.875rem;
            font-weight: 600;
            position: relative;
            overflow: hidden;
        }
        
        .status-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }
        
        .status-badge:hover::before {
            left: 100%;
        }
        
        .status-pending {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            border: 1px solid #fbbf24;
        }
        
        .status-completed {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            border: 1px solid #10b981;
        }
        
        .status-rejected {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
            border: 1px solid #ef4444;
        }
        
        .btn-modern {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .btn-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-modern:hover::before {
            left: 100%;
        }
        
        .invoice-icon {
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .pulse-ring {
            animation: pulse-ring 1.5s infinite;
        }
        
        @keyframes pulse-ring {
            0% {
                transform: scale(0.33);
                opacity: 1;
            }
            80%, 100% {
                transform: scale(1.3);
                opacity: 0;
            }
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 1rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, #eab308, #fbbf24);
        }
        
        .timeline-item:last-child::before {
            background: linear-gradient(to bottom, #eab308, transparent);
        }
        
        .loading-dots {
            display: inline-block;
        }
        
        .loading-dots::after {
            content: '';
            animation: loading-dots 1.5s infinite;
        }
        
        @keyframes loading-dots {
            0%, 20% { content: ''; }
            40% { content: '.'; }
            60% { content: '..'; }
            80%, 100% { content: '...'; }
        }
    </style>
</head>
<body class="bg-neutral-50 font-inter">
    <?php include __DIR__ . '/../../includes/header.php'; ?>

    <main class="min-h-screen pt-24 pb-16">
        <div class="container mx-auto px-4 max-w-7xl">
            <!-- Header Section -->
            <div class="mb-8" data-aos="fade-down">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center space-x-4 mb-2">
                            <a href="?route=profile" class="text-neutral-600 hover:text-primary-500 transition-colors duration-300 flex items-center">
                                <i class="fas fa-arrow-left mr-2"></i>Retour au profil
                            </a>
                        </div>
                        <h1 class="text-4xl md:text-5xl font-black text-neutral-900 tracking-tight">
                            Commande #<?php echo $order['id']; ?>
                        </h1>
                        <p class="text-xl text-neutral-600 mt-2">Détails et facturation de votre commande</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <?php
                        $statusClass = 'status-pending';
                        $statusText = '⏳ En attente';
                        $statusIcon = 'fas fa-clock';
                        switch ($order['status']) {
                            case 'completed':
                                $statusClass = 'status-completed';
                                $statusText = '✅ Complétée';
                                $statusIcon = 'fas fa-check-circle';
                                break;
                            case 'rejected':
                                $statusClass = 'status-rejected';
                                $statusText = '❌ Rejetée';
                                $statusIcon = 'fas fa-times-circle';
                                break;
                        }
                        ?>
                        <div class="status-badge <?php echo $statusClass; ?>">
                            <i class="<?php echo $statusIcon; ?> mr-2"></i>
                            <?php echo $statusText; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Order Summary -->
                    <div class="card-modern rounded-3xl p-8" data-aos="fade-up" data-aos-delay="100">
                        <div class="flex items-center justify-between mb-8">
                            <h2 class="text-2xl font-bold text-neutral-900 flex items-center">
                                <i class="fas fa-receipt mr-3 text-primary-500"></i>
                                Résumé de la commande
                            </h2>
                            <div class="text-sm text-neutral-500">
                                <i class="fas fa-calendar mr-2"></i>
                                <?php echo date('d/m/Y à H:i', strtotime($order['created_at'])); ?>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <div class="glass-effect rounded-2xl p-6">
                                    <h3 class="font-semibold text-neutral-900 mb-4 flex items-center">
                                        <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                                        Informations
                                    </h3>
                                    <div class="space-y-3">
                                        <div class="flex justify-between">
                                            <span class="text-neutral-600">Numéro:</span>
                                            <span class="font-mono font-bold text-neutral-900">#<?php echo $order['id']; ?></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-neutral-600">Date:</span>
                                            <span class="font-medium"><?php echo date('d/m/Y', strtotime($order['created_at'])); ?></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-neutral-600">Montant:</span>
                                            <span class="font-bold text-primary-600 text-lg"><?php echo number_format($order['total_amount'], 2); ?> MAD</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-6">
                                <div class="glass-effect rounded-2xl p-6">
                                    <h3 class="font-semibold text-neutral-900 mb-4 flex items-center">
                                        <i class="fas fa-user mr-2 text-green-500"></i>
                                        Client
                                    </h3>
                                    <div class="space-y-3">
                                        <div class="flex justify-between">
                                            <span class="text-neutral-600">Nom:</span>
                                            <span class="font-medium"><?php echo htmlspecialchars($order['user_name']); ?></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-neutral-600">Email:</span>
                                            <span class="font-medium text-sm"><?php echo htmlspecialchars($order['user_email']); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Timeline -->
                    <div class="card-modern rounded-3xl p-8" data-aos="fade-up" data-aos-delay="200">
                        <h2 class="text-2xl font-bold text-neutral-900 mb-8 flex items-center">
                            <i class="fas fa-route mr-3 text-primary-500"></i>
                            Suivi de commande
                        </h2>
                        
                        <div class="relative">
                            <div class="timeline-item relative pl-12 pb-8">
                                <div class="absolute left-0 top-0 w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-shopping-cart text-white text-sm"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-neutral-900">Commande passée</h3>
                                    <p class="text-neutral-600 text-sm">Votre commande a été enregistrée avec succès</p>
                                    <p class="text-xs text-neutral-500 mt-1"><?php echo date('d/m/Y à H:i', strtotime($order['created_at'])); ?></p>
                                </div>
                            </div>
                            
                            <?php if ($order['status'] === 'completed'): ?>
                            <div class="timeline-item relative pl-12 pb-8">
                                <div class="absolute left-0 top-0 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-neutral-900">Commande complétée</h3>
                                    <p class="text-neutral-600 text-sm">Votre commande a été traitée et complétée</p>
                                    <p class="text-xs text-neutral-500 mt-1"><?php echo date('d/m/Y à H:i', strtotime($order['updated_at'])); ?></p>
                                </div>
                            </div>
                            <?php elseif ($order['status'] === 'rejected'): ?>
                            <div class="timeline-item relative pl-12 pb-8">
                                <div class="absolute left-0 top-0 w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-times text-white text-sm"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-neutral-900">Commande rejetée</h3>
                                    <p class="text-neutral-600 text-sm">Votre commande a été rejetée</p>
                                    <p class="text-xs text-neutral-500 mt-1"><?php echo date('d/m/Y à H:i', strtotime($order['updated_at'])); ?></p>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="timeline-item relative pl-12">
                                <div class="absolute left-0 top-0 w-8 h-8 bg-neutral-300 rounded-full flex items-center justify-center">
                                    <div class="w-3 h-3 bg-neutral-500 rounded-full pulse-ring"></div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-neutral-900">En cours de traitement</h3>
                                    <p class="text-neutral-600 text-sm">Votre commande est en cours de traitement<span class="loading-dots"></span></p>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-8">
                    <!-- Invoice Section -->
                    <div class="card-modern rounded-3xl p-8" data-aos="fade-left" data-aos-delay="300">
                        <h3 class="text-xl font-bold text-neutral-900 mb-6 flex items-center">
                            <i class="fas fa-file-invoice text-primary-500 mr-3 invoice-icon"></i>
                            Facturation
                        </h3>
                        
                        <div class="space-y-6">
                            <div class="glass-effect rounded-2xl p-4">
                                <div class="text-sm text-neutral-600 mb-2">Numéro de facture:</div>
                                <div class="font-mono font-bold text-lg text-neutral-900">
                                    FACT-<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?>
                                </div>
                            </div>
                            
                            <?php if ($order['status'] === 'completed'): ?>
                            <div class="space-y-4">
                                <div class="bg-green-50 border border-green-200 rounded-2xl p-4">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                        <span class="text-green-800 font-medium">Facture disponible</span>
                                    </div>
                                    <p class="text-green-700 text-sm">Votre facture est prête à être téléchargée.</p>
                                </div>
                                
                                <a href="?route=user-generate-invoice&id=<?php echo $order['id']; ?>" 
                                   class="w-full inline-flex items-center justify-center px-6 py-4 bg-primary-500 text-white font-semibold rounded-2xl btn-modern"
                                   target="_blank">
                                    <i class="fas fa-download mr-3"></i>
                                    Télécharger la facture
                                </a>
                                
                                <button onclick="previewInvoice()" 
                                        class="w-full inline-flex items-center justify-center px-6 py-3 border-2 border-neutral-200 text-neutral-700 font-medium rounded-2xl btn-modern">
                                    <i class="fas fa-eye mr-3"></i>
                                    Aperçu de la facture
                                </button>
                            </div>
                            <?php else: ?>
                            <div class="bg-neutral-50 border border-neutral-200 rounded-2xl p-4">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-clock text-neutral-500 mr-2"></i>
                                    <span class="text-neutral-800 font-medium">Facture en attente</span>
                                </div>
                                <p class="text-neutral-600 text-sm">
                                    <?php if ($order['status'] === 'pending'): ?>
                                        La facture sera disponible une fois la commande complétée.
                                    <?php else: ?>
                                        Aucune facture disponible pour cette commande.
                                    <?php endif; ?>
                                </p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Pricing Details -->
                    <div class="card-modern rounded-3xl p-8" data-aos="fade-left" data-aos-delay="400">
                        <h3 class="text-xl font-bold text-neutral-900 mb-6 flex items-center">
                            <i class="fas fa-calculator text-primary-500 mr-3"></i>
                            Détail des prix
                        </h3>
                        
                        <?php
                        $tvaRate = 20;
                        $subtotal = $order['total_amount'];
                        $tvaAmount = $subtotal * ($tvaRate / 100);
                        $totalTTC = $subtotal + $tvaAmount;
                        ?>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-neutral-200">
                                <span class="text-neutral-600">Sous-total HT:</span>
                                <span class="font-semibold text-lg"><?php echo number_format($subtotal, 2); ?> MAD</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-neutral-200">
                                <span class="text-neutral-600">TVA (<?php echo $tvaRate; ?>%):</span>
                                <span class="font-semibold text-lg"><?php echo number_format($tvaAmount, 2); ?> MAD</span>
                            </div>
                            <div class="glass-effect rounded-2xl p-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold text-neutral-900">Total TTC:</span>
                                    <span class="text-2xl font-black text-primary-600"><?php echo number_format($totalTTC, 2); ?> MAD</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Support -->
                    <div class="card-modern rounded-3xl p-8" data-aos="fade-left" data-aos-delay="500">
                        <h3 class="text-xl font-bold text-neutral-900 mb-6 flex items-center">
                            <i class="fas fa-headset text-primary-500 mr-3"></i>
                            Besoin d'aide ?
                        </h3>
                        
                        <div class="space-y-4">
                            <a href="mailto:contact@bijuvia.com?subject=Commande #<?php echo $order['id']; ?>" 
                               class="w-full inline-flex items-center justify-center px-6 py-3 border-2 border-primary-200 text-primary-700 font-medium rounded-2xl btn-modern">
                                <i class="fas fa-envelope mr-3"></i>
                                Contacter le support
                            </a>
                            
                            <div class="text-center text-sm text-neutral-500">
                                <p>📞 +212 522 123 456</p>
                                <p>📧 contact@bijuvia.com</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Invoice Preview Modal -->
    <div id="invoice-preview-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-4xl max-h-[90vh] overflow-auto card-modern">
            <div class="sticky top-0 bg-white border-b border-neutral-200 p-6 flex items-center justify-between rounded-t-3xl">
                <h3 class="text-xl font-bold">Aperçu de la facture</h3>
                <button onclick="closePreview()" class="text-neutral-500 hover:text-neutral-700 p-2 rounded-full transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="invoice-content" class="p-6">
                <!-- Invoice content will be inserted here -->
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true
        });

        function previewInvoice() {
            const modal = document.getElementById('invoice-preview-modal');
            const content = document.getElementById('invoice-content');
            
            content.innerHTML = generateInvoicePreview();
            modal.classList.remove('hidden');
        }

        function closePreview() {
            document.getElementById('invoice-preview-modal').classList.add('hidden');
        }

        function generateInvoicePreview() {
            const invoiceNumber = 'FACT-<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?>';
            const invoiceDate = '<?php echo date('d/m/Y', strtotime($order['created_at'])); ?>';
            const subtotal = <?php echo $order['total_amount']; ?>;
            const tvaAmount = subtotal * 0.2;
            const totalTTC = subtotal + tvaAmount;

            return `
                <div class="space-y-8">
                    <div class="text-center border-b border-neutral-200 pb-6">
                        <h1 class="text-4xl font-black text-primary-500 mb-2">BIJUVIA</h1>
                        <p class="text-neutral-600">Bijouterie de Luxe • Casablanca</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-lg font-bold text-neutral-900 mb-4">📋 Informations Facture</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-neutral-600">Numéro:</span>
                                    <span class="font-mono font-bold">${invoiceNumber}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-neutral-600">Date:</span>
                                    <span class="font-medium">${invoiceDate}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-neutral-600">Statut:</span>
                                    <span class="font-medium text-green-600">✅ <?php echo ucfirst($order['status']); ?></span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-bold text-neutral-900 mb-4">👤 Client</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-neutral-600">Nom:</span>
                                    <span class="font-medium"><?php echo htmlspecialchars($order['user_name']); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-neutral-600">Email:</span>
                                    <span class="font-medium"><?php echo htmlspecialchars($order['user_email']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border border-neutral-200 rounded-2xl overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-neutral-50">
                                <tr>
                                    <th class="text-left p-4 font-semibold">Description</th>
                                    <th class="text-left p-4 font-semibold">Quantité</th>
                                    <th class="text-left p-4 font-semibold">Prix HT</th>
                                    <th class="text-left p-4 font-semibold">Total HT</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-t border-neutral-200">
                                    <td class="p-4">
                                        <strong>Commande Bijuvia #<?php echo $order['id']; ?></strong><br>
                                        <small class="text-neutral-500">Produits de bijouterie</small>
                                    </td>
                                    <td class="p-4">1</td>
                                    <td class="p-4 font-semibold text-primary-600">${subtotal.toFixed(2)} MAD</td>
                                    <td class="p-4 font-semibold text-primary-600">${subtotal.toFixed(2)} MAD</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="bg-neutral-50 rounded-2xl p-6">
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="font-medium">Sous-total HT:</span>
                                <span class="font-semibold">${subtotal.toFixed(2)} MAD</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">TVA (20%):</span>
                                <span class="font-semibold">${tvaAmount.toFixed(2)} MAD</span>
                            </div>
                            <div class="flex justify-between text-xl font-bold text-primary-600 pt-3 border-t border-neutral-300">
                                <span>TOTAL TTC:</span>
                                <span>${totalTTC.toFixed(2)} MAD</span>
                            </div>
                        </div>

                    </div>

                    <div class="text-center text-neutral-500 text-sm">
                        <p>🙏 Merci pour votre confiance !</p>
                        <p class="mt-2">📍 123 Avenue Mohammed V, Casablanca • 📞 +212 522 123 456</p>
                    </div>
                </div>
            `;
        }

        // Close modal when clicking outside
        document.getElementById('invoice-preview-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePreview();
            }
        });
    </script>
</body>
</html> 