<header class="mb-12">
    <nav class="bg-white fixed top-0 left-0 w-full py-4 px-6 z-50 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <!-- Logo -->
            <a href="index.php" class="text-2xl text-yellow-500 font-semibold hover:text-yellow-500 transition duration-300 ease-in-out">
                Bijuvia
            </a>
            
            <!-- Desktop Navbar (caché en mobile) -->
            <div class="hidden md:flex space-x-6 text-gray-800 text-lg font-medium">
                <a href="index.php" class="hover:text-yellow-500 transition duration-300 ease-in-out">Accueil</a>
                <a href="?route=products" class="hover:text-yellow-500 transition duration-300 ease-in-out">Produits</a>
                <a href="?route=home#tendances" class="hover:text-yellow-500 transition duration-300 ease-in-out">Tendances</a>
                <a href="?route=home#about" class="hover:text-yellow-500 transition duration-300 ease-in-out">À propos</a>
            </div>

            <!-- Desktop Actions -->
            <div class="hidden md:flex items-center space-x-6 text-gray-800">
                <a href="?route=cart" class="text-lg hover:text-yellow-500 transition duration-300 ease-in-out">
                    <i class="fas fa-shopping-cart"></i>
                </a>

                <!-- Account Dropdown -->
                <div class="relative">
                    <button id="account-dropdown-button" class="flex items-center space-x-2 text-lg hover:text-yellow-500 transition duration-300 ease-in-out">
                        <i class="fas fa-user"></i>
                        <span>Compte</span>
                    </button>
                    <!-- Dropdown Menu -->
                    <div id="account-dropdown-menu" class="hidden absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-md shadow-md text-gray-800">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <!-- Si l'utilisateur est connecté, afficher un lien pour se déconnecter -->
                            <a href="?route=profile" class="block px-4 py-2 hover:bg-yellow-100 transition duration-300 ease-in-out"><?php echo htmlspecialchars($_SESSION['name']); ?></a>

                            <form action="?route=logout" method="POST">
                                <button type="submit" class="block px-4 py-2 hover:bg-red-100 transition duration-300 ease-in-out">
                                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                                </button>
                            </form>
                        <?php else: ?>
                            <!-- Sinon, afficher les liens pour se connecter ou s'inscrire -->
                            <a href="?route=login" class="block px-4 py-2 hover:bg-yellow-100 transition duration-300 ease-in-out">Connexion</a>
                            <a href="?route=register" class="block px-4 py-2 hover:bg-yellow-100 transition duration-300 ease-in-out">Inscription</a>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
            
            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-button" class="text-gray-800">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white text-gray-800 space-y-4 mt-4 p-4 shadow-md rounded-md">
            <a href="index.php" class="block hover:text-yellow-500 transition duration-300 ease-in-out">Accueil</a>
            <a href="?route=products" class="block hover:text-yellow-500 transition duration-300 ease-in-out">Produits</a>
            <a href="?route=home#tendances" class="block hover:text-yellow-500 transition duration-300 ease-in-out">Tendances</a>
            <a href="?route=home#about" class="block hover:text-yellow-500 transition duration-300 ease-in-out">À propos</a>
            <hr class="my-2">
            <a href="?route=cart" class="block hover:text-yellow-500 transition duration-300 ease-in-out">
                <i class="fas fa-shopping-cart"></i> Panier
            </a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Si l'utilisateur est connecté, afficher le lien de déconnexion -->
                <a href="?route=profile" class="block hover:text-yellow-500 transition duration-300 ease-in-out">
                    <i class="fas fa-user"></i> Profile
                </a>
                <form action="?route=logout" method="POST">
                    <button type="submit" class="block hover:text-red-500 transition duration-300 ease-in-out">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </button>
                </form>
                
            <?php else: ?>
                <!-- Si l'utilisateur n'est pas connecté, afficher les liens de connexion et d'inscription -->
                <a href="?route=login" class="block hover:text-yellow-500 transition duration-300 ease-in-out">
                    <i class="fas fa-sign-in-alt"></i> Connexion
                </a>
                <a href="?route=register" class="block hover:text-yellow-500 transition duration-300 ease-in-out">
                    <i class="fas fa-user-plus"></i> Inscription
                </a>
            <?php endif; ?>

        </div>
    </nav>
</header>

<!-- Script pour les menus -->
<script>
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const accountDropdownButton = document.getElementById('account-dropdown-button');
    const accountDropdownMenu = document.getElementById('account-dropdown-menu');

    // Toggle mobile menu visibility
    mobileMenuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    // Toggle account dropdown visibility
    accountDropdownButton.addEventListener('click', () => {
        accountDropdownMenu.classList.toggle('hidden');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', (event) => {
        if (!accountDropdownButton.contains(event.target) && !accountDropdownMenu.contains(event.target)) {
            accountDropdownMenu.classList.add('hidden');
        }
    });
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

</script>

