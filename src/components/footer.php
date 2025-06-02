<!-- Footer équilibré avec style sobre -->
<footer class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 transition-colors duration-200" id="footer">
    <div class="container mx-auto px-4 py-8">
        <!-- Contenu principal -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
            
            <!-- Logo et titre -->
            <div class="flex items-center justify-center md:justify-start space-x-3 footer-logo">
                <div class="bg-indigo-600 dark:bg-indigo-500 p-2 rounded-lg icon-hover">
                    <i class="fas fa-ticket-alt text-white"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-800 dark:text-white">YourTicket</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Gestion de tickets</p>
                </div>
            </div>

            <!-- Navigation rapide -->
            <nav class="flex flex-wrap justify-center gap-4 footer-nav">
                <a href="index.php" class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition text-sm font-medium nav-link">
                    Accueil
                </a>
                <a href="yourticket.php" class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition text-sm font-medium nav-link">
                    Mes Tickets
                </a>
                <a href="create_ticket.php" class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition text-sm font-medium nav-link">
                    Nouveau Ticket
                </a>
            </nav>

            <!-- Contact et réseaux sociaux -->
            <div class="flex flex-col items-center md:items-end space-y-2">
                <div class="text-sm text-gray-600 dark:text-gray-300">
                    <i class="fas fa-envelope text-indigo-600 dark:text-indigo-400 mr-1"></i>
                    support@yourticket.com
                </div>
                
                <!-- Réseaux sociaux -->
                <div class="flex space-x-3 social-links">
                    <a href="https://www.facebook.com/nicolas.houbion.14/" target="_blank" 
                       class="w-8 h-8 flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-200 social-icon">
                        <i class="fab fa-facebook-f text-sm"></i>
                    </a>
                    <a href="https://www.linkedin.com/in/nicolas-houbion-ba6bb5204/" target="_blank" 
                       class="w-8 h-8 flex items-center justify-center bg-blue-700 hover:bg-blue-800 text-white rounded-lg transition-all duration-200 social-icon">
                        <i class="fab fa-linkedin-in text-sm"></i>
                    </a>
                    <a href="https://github.com/NicolasHoubion" target="_blank" 
                       class="w-8 h-8 flex items-center justify-center bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-all duration-200 social-icon">
                        <i class="fab fa-github text-sm"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Séparateur -->
        <div class="border-t border-gray-200 dark:border-gray-700 mt-6 pt-4">
            <div class="flex flex-col sm:flex-row justify-between items-center text-sm text-gray-500 dark:text-gray-400">
                <p>&copy; 2025 YourTicket - Tous droits réservés</p>
                <div class="flex items-center mt-2 sm:mt-0">
                    <i class="fas fa-shield-alt text-indigo-600 dark:text-indigo-400 mr-1"></i>
                    <span>Sécurisé & Fiable</span>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .footer-logo {
        animation: slideUp 0.6s ease-out;
    }

    .footer-nav {
        animation: slideUp 0.8s ease-out;
    }

    .social-links {
        animation: slideUp 1s ease-out;
    }

    .icon-hover {
        transition: all 0.3s ease;
    }

    .icon-hover:hover {
        transform: rotate(5deg) scale(1.05);
    }

    .nav-link {
        position: relative;
        overflow: hidden;
    }

    .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 1px;
        background: linear-gradient(90deg, #4f46e5, #7c3aed);
        transition: width 0.3s ease;
    }

    .nav-link:hover::after {
        width: 100%;
    }

    .social-icon {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .social-icon:hover {
        transform: translateY(-2px) scale(1.1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation au scroll pour le footer
        const footer = document.getElementById('footer');
        
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    footer.style.opacity = '1';
                    footer.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Style initial pour l'animation
        footer.style.opacity = '0';
        footer.style.transform = 'translateY(20px)';
        footer.style.transition = 'opacity 0.6s ease, transform 0.6s ease';

        observer.observe(footer);

        // Animation staggered pour les liens de navigation
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach((link, index) => {
            link.style.animationDelay = `${0.2 + (index * 0.1)}s`;
        });

        // Animation staggered pour les icônes sociales
        const socialIcons = document.querySelectorAll('.social-icon');
        socialIcons.forEach((icon, index) => {
            icon.style.animationDelay = `${0.5 + (index * 0.1)}s`;
        });
    });
</script>