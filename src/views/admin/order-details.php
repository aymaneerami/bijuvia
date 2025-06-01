<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails Commande #<?php echo $order['id']; ?> - Bijuvia Admin</title>

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
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .card-admin {
            border: 1px solid rgba(229, 229, 229, 0.3);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .status-completed {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-rejected {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .btn-admin {
            transition: all 0.2s ease-in-out;
        }
        
        .btn-admin:hover {
            transform: translateY(-1px);
        }
        
        .invoice-preview {
            background: white;
            border: 1px solid #e5e5e5;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-neutral-50 font-inter">
    <?php include __DIR__ . '/../../includes/header.php'; ?>

    <main class="min-h-screen pt-24 pb-16">
        <div class="container mx-auto px-4 max-w-6xl">
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center space-x-4 mb-2">
                            <a href="?route=admin" class="text-neutral-600 hover:text-primary-500 transition-colors duration-200">
                                <i class="fas fa-arrow-left mr-2"></i>Retour au dashboard
                            </a>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-bold text-neutral-900">
                            Commande #<?php echo $order['id']; ?>
                        </h1>
                        <p class="text-neutral-600">Détails et facturation de la commande</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <?php
                        $statusClass = 'status-pending';
                        $statusText = 'En attente';
                        switch ($order['status']) {
                            case 'completed':
                                $statusClass = 'status-completed';
                                $statusText = 'Complétée';
                                break;
                            case 'rejected':
                                $statusClass = 'status-rejected';
                                $statusText = 'Rejetée';
                                break;
                        }
                        ?>
                        <span class="status-badge <?php echo $statusClass; ?>">
                            <?php echo $statusText; ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Informations Commande -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Détails de la commande -->
                    <div class="bg-white rounded-2xl card-admin p-6">
                        <h2 class="text-xl font-semibold text-neutral-900 mb-6">Informations de la commande</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="font-medium text-neutral-900 mb-3">Détails</h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-neutral-600">Numéro:</span>
                                        <span class="font-medium">#<?php echo $order['id']; ?></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-neutral-600">Date:</span>
                                        <span class="font-medium"><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-neutral-600">Statut:</span>
                                        <span class="font-medium capitalize"><?php echo $order['status']; ?></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-neutral-600">Montant total:</span>
                                        <span class="font-bold text-primary-600"><?php echo number_format($order['total_amount'], 2); ?> MAD</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="font-medium text-neutral-900 mb-3">Client</h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-neutral-600">Nom:</span>
                                        <span class="font-medium"><?php echo htmlspecialchars($order['user_name']); ?></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-neutral-600">Email:</span>
                                        <span class="font-medium"><?php echo htmlspecialchars($order['user_email']); ?></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-neutral-600">ID Client:</span>
                                        <span class="font-medium">#<?php echo $order['user_id']; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions sur la commande -->
                    <div class="bg-white rounded-2xl card-admin p-6">
                        <h2 class="text-xl font-semibold text-neutral-900 mb-6">Actions sur la commande</h2>
                        
                        <div class="flex flex-wrap gap-4">
                            <!-- Changer le statut -->
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 mb-2">Changer le statut</label>
                                <select id="order-status" class="px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                    <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>En attente</option>
                                    <option value="completed" <?php echo $order['status'] === 'completed' ? 'selected' : ''; ?>>Complétée</option>
                                    <option value="rejected" <?php echo $order['status'] === 'rejected' ? 'selected' : ''; ?>>Rejetée</option>
                                </select>
                            </div>
                            
                            <div class="flex items-end">
                                <button onclick="updateStatus()" class="px-6 py-2 bg-primary-500 text-white font-medium rounded-lg btn-admin">
                                    <i class="fas fa-save mr-2"></i>Mettre à jour
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Calculs TVA -->
                    <div class="bg-white rounded-2xl card-admin p-6">
                        <h2 class="text-xl font-semibold text-neutral-900 mb-6">Détail des prix</h2>
                        
                        <?php
                        $tvaRate = 20; // TVA à 20%
                        $subtotal = $order['total_amount'];
                        $tvaAmount = $subtotal * ($tvaRate / 100);
                        $totalTTC = $subtotal + $tvaAmount;
                        ?>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-neutral-600">Sous-total HT:</span>
                                <span class="font-semibold text-lg"><?php echo number_format($subtotal, 2); ?> MAD</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-neutral-600">TVA (<?php echo $tvaRate; ?>%):</span>
                                <span class="font-semibold text-lg"><?php echo number_format($tvaAmount, 2); ?> MAD</span>
                            </div>
                            <div class="border-t border-neutral-200 pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-neutral-900">Total TTC:</span>
                                    <span class="text-xl font-bold text-primary-600"><?php echo number_format($totalTTC, 2); ?> MAD</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Actions -->
                <div class="space-y-6">
                    <!-- Facturation -->
                    <div class="bg-white rounded-2xl card-admin p-6">
                        <h3 class="text-lg font-semibold text-neutral-900 mb-4">
                            <i class="fas fa-file-invoice text-primary-500 mr-2"></i>
                            Facturation
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="text-sm text-neutral-600">
                                <p>Numéro de facture:</p>
                                <p class="font-mono font-bold text-neutral-900">FACT-<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></p>
                            </div>
                            
                            <div class="space-y-3">
                                <a href="?route=admin-generate-invoice&id=<?php echo $order['id']; ?>" 
                                   class="w-full inline-flex items-center justify-center px-4 py-3 bg-primary-500 text-white font-medium rounded-lg btn-admin"
                                   target="_blank">
                                    <i class="fas fa-download mr-2"></i>
                                    Télécharger la facture
                                </a>
                                
                                <button onclick="previewInvoice()" 
                                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-neutral-300 text-neutral-700 font-medium rounded-lg btn-admin">
                                    <i class="fas fa-eye mr-2"></i>
                                    Aperçu
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Informations complémentaires -->
                    <div class="bg-white rounded-2xl card-admin p-6">
                        <h3 class="text-lg font-semibold text-neutral-900 mb-4">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                            Informations
                        </h3>
                        
                        <div class="space-y-3 text-sm">
                            <div>
                                <span class="text-neutral-600">Créée le:</span>
                                <p class="font-medium"><?php echo date('d/m/Y à H:i', strtotime($order['created_at'])); ?></p>
                            </div>
                            <?php if ($order['updated_at'] && $order['updated_at'] !== $order['created_at']): ?>
                            <div>
                                <span class="text-neutral-600">Modifiée le:</span>
                                <p class="font-medium"><?php echo date('d/m/Y à H:i', strtotime($order['updated_at'])); ?></p>
                            </div>
                            <?php endif; ?>
                            <div>
                                <span class="text-neutral-600">Devise:</span>
                                <p class="font-medium">Dirham Marocain (MAD)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions rapides -->
                    <div class="bg-white rounded-2xl card-admin p-6">
                        <h3 class="text-lg font-semibold text-neutral-900 mb-4">
                            <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                            Actions rapides
                        </h3>
                        
                        <div class="space-y-3">
                            <?php if ($order['status'] === 'pending'): ?>
                            <button onclick="quickStatusUpdate('completed')" 
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-500 text-white font-medium rounded-lg btn-admin">
                                <i class="fas fa-check mr-2"></i>
                                Marquer comme complétée
                            </button>
                            <button onclick="quickStatusUpdate('rejected')" 
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-500 text-white font-medium rounded-lg btn-admin">
                                <i class="fas fa-times mr-2"></i>
                                Rejeter la commande
                            </button>
                            <?php endif; ?>
                            
                            <a href="mailto:<?php echo htmlspecialchars($order['user_email']); ?>?subject=Concernant votre commande #<?php echo $order['id']; ?>" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 border border-neutral-300 text-neutral-700 font-medium rounded-lg btn-admin">
                                <i class="fas fa-envelope mr-2"></i>
                                Contacter le client
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Message Container -->
            <div id="message-container" class="hidden mt-6"></div>
        </div>
    </main>

    <!-- Modal d'aperçu de facture -->
    <div id="invoice-preview-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-4xl max-h-[90vh] overflow-auto invoice-preview">
            <div class="sticky top-0 bg-white border-b border-neutral-200 p-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold">Aperçu de la facture</h3>
                <button onclick="closePreview()" class="text-neutral-500 hover:text-neutral-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="invoice-content" class="p-6">
                <!-- Le contenu de la facture sera inséré ici -->
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>

    <script>
        function updateStatus() {
            const status = document.getElementById('order-status').value;
            const formData = new FormData();
            formData.append('order_id', <?php echo $order['id']; ?>);
            formData.append('status', status);

            fetch('?route=admin-update-order', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                showMessage(data.message, data.success ? 'success' : 'error');
                if (data.success) {
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                }
            })
            .catch(error => {
                showMessage('Erreur lors de la mise à jour du statut.', 'error');
            });
        }

        function quickStatusUpdate(status) {
            if (confirm(`Êtes-vous sûr de vouloir changer le statut à "${status}" ?`)) {
                const formData = new FormData();
                formData.append('order_id', <?php echo $order['id']; ?>);
                formData.append('status', status);

                fetch('?route=admin-update-order', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    showMessage(data.message, data.success ? 'success' : 'error');
                    if (data.success) {
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    }
                })
                .catch(error => {
                    showMessage('Erreur lors de la mise à jour du statut.', 'error');
                });
            }
        }

        function previewInvoice() {
            const modal = document.getElementById('invoice-preview-modal');
            const content = document.getElementById('invoice-content');
            
            // Générer l'aperçu de la facture
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
                <div style="font-family: Arial, sans-serif;">
                    <div style="border-bottom: 2px solid #eab308; padding-bottom: 20px; margin-bottom: 30px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <h1 style="color: #eab308; margin: 0; font-size: 2rem;">BIJUVIA</h1>
                                <p style="margin: 5px 0; color: #666;">Bijouterie de Luxe</p>
                            </div>
                            <div style="text-align: right;">
                                <p><strong>BIJUVIA SARL</strong></p>
                                <p>123 Avenue Mohammed V</p>
                                <p>Casablanca, Maroc</p>
                                <p>Tél: +212 522 123 456</p>
                                <p>Email: contact@bijuvia.com</p>
                            </div>
                        </div>
                    </div>

                    <div style="margin: 20px 0;">
                        <h2>FACTURE N° ${invoiceNumber}</h2>
                        <p><strong>Date:</strong> ${invoiceDate}</p>
                        <p><strong>Statut:</strong> <?php echo ucfirst($order['status']); ?></p>
                    </div>

                    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 20px 0;">
                        <h3>Facturé à:</h3>
                        <p><strong><?php echo htmlspecialchars($order['user_name']); ?></strong></p>
                        <p>Email: <?php echo htmlspecialchars($order['user_email']); ?></p>
                    </div>

                    <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
                        <thead>
                            <tr style="background: #f8f9fa;">
                                <th style="padding: 12px; border-bottom: 1px solid #ddd; text-align: left;">Description</th>
                                <th style="padding: 12px; border-bottom: 1px solid #ddd; text-align: left;">Quantité</th>
                                <th style="padding: 12px; border-bottom: 1px solid #ddd; text-align: left;">Prix unitaire</th>
                                <th style="padding: 12px; border-bottom: 1px solid #ddd; text-align: left;">Total HT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 12px; border-bottom: 1px solid #ddd;">Commande Bijuvia #<?php echo $order['id']; ?></td>
                                <td style="padding: 12px; border-bottom: 1px solid #ddd;">1</td>
                                <td style="padding: 12px; border-bottom: 1px solid #ddd; color: #eab308; font-weight: bold;">${subtotal.toFixed(2)} MAD</td>
                                <td style="padding: 12px; border-bottom: 1px solid #ddd; color: #eab308; font-weight: bold;">${subtotal.toFixed(2)} MAD</td>
                            </tr>
                        </tbody>
                    </table>

                    <div style="width: 300px; margin-left: auto;">
                        <table style="width: 100%; margin: 0;">
                            <tr>
                                <td style="padding: 8px;"><strong>Sous-total HT:</strong></td>
                                <td style="padding: 8px; color: #eab308; font-weight: bold;">${subtotal.toFixed(2)} MAD</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px;"><strong>TVA (20%):</strong></td>
                                <td style="padding: 8px; color: #eab308; font-weight: bold;">${tvaAmount.toFixed(2)} MAD</td>
                            </tr>
                            <tr style="background: #eab308; color: white;">
                                <td style="padding: 8px;"><strong>TOTAL TTC:</strong></td>
                                <td style="padding: 8px;"><strong>${totalTTC.toFixed(2)} MAD</strong></td>
                            </tr>
                        </table>
                    </div>

                    <div style="margin-top: 40px; text-align: center; color: #666;">
                        <p>Merci pour votre confiance !</p>
                        <p style="font-size: 12px;">Cette facture a été générée automatiquement</p>
                    </div>
                </div>
            `;
        }

        function showMessage(message, type) {
            const messageContainer = document.getElementById('message-container');
            messageContainer.className = `mt-6 p-4 rounded-xl ${type === 'success' ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200'}`;
            messageContainer.innerHTML = `
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                ${message}
            `;
            messageContainer.classList.remove('hidden');
            
            if (type === 'success') {
                setTimeout(() => {
                    messageContainer.classList.add('hidden');
                }, 5000);
            }
        }

        // Fermer la modal en cliquant en dehors
        document.getElementById('invoice-preview-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePreview();
            }
        });
    </script>
</body>
</html> 