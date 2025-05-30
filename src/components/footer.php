<footer class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 py-8">
    <div class="container mx-auto px-4 max-w-6xl">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Column 1 - About -->
            <div>
                <div class="flex items-center mb-4">
                    <div class="bg-indigo-600 dark:bg-indigo-500 p-2 rounded-lg">
                        <i class="fas fa-ticket-alt text-white text-xl"></i>
                    </div>
                    <h2 class="text-xl font-bold ml-3 dark:text-white"><?= t('site_title', $translations, $lang) ?></h2>
                </div>
                <p class="text-gray-600 dark:text-gray-300 text-sm">
                    <?= t('footer_description', $translations, $lang) ?>
                </p>
            </div>

            <!-- Column 2 - Quick Links -->
            <div>
                <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-4 text-lg"><?= t('quick_links', $translations, $lang) ?></h3>
                <ul class="space-y-2">
                    <li>
                        <a href="index.php" class="flex items-center text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition text-sm">
                            <i class="fas fa-home mr-2 text-xs"></i> <?= t('home', $translations, $lang) ?>
                        </a>
                    </li>
                    <li>
                        <a href="yourticket.php" class="flex items-center text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition text-sm">
                            <i class="fas fa-ticket-alt mr-2 text-xs"></i> <?= t('my_tickets', $translations, $lang) ?>
                        </a>
                    </li>
                    <li>
                        <a href="create_ticket.php" class="flex items-center text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition text-sm">
                            <i class="fas fa-plus-circle mr-2 text-xs"></i> <?= t('new_ticket', $translations, $lang) ?>
                        </a>
                    </li>
                    <?php if ($isAdmin): ?>
                    <li>
                        <a href="dashboard.php" class="flex items-center text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition text-sm">
                            <i class="fas fa-chart-line mr-2 text-xs"></i> <?= t('dashboard', $translations, $lang) ?>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Column 3 - Contact -->
            <div>
                <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-4 text-lg"><?= t('contact', $translations, $lang) ?></h3>
                <ul class="space-y-2">
                    <li class="flex items-start text-gray-600 dark:text-gray-300 text-sm">
                        <i class="fas fa-envelope mt-1 mr-2 text-xs"></i>
                        <span>support@yourticket.com</span>
                    </li>
                    <li class="flex items-start text-gray-600 dark:text-gray-300 text-sm">
                        <i class="fas fa-phone mt-1 mr-2 text-xs"></i>
                        <span>+33 1 23 45 67 89</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-200 dark:border-gray-700 mt-8 pt-6"></div>

        <!-- Copyright & Social -->
        <div class="flex flex-col md:flex-row justify-between items-center">
            <p class="text-gray-600 dark:text-gray-300 text-sm text-center md:text-left">
                &copy; <?= date('Y') ?> <?= t('site_title', $translations, $lang) ?>. <?= t('all_rights_reserved', $translations, $lang) ?>
            </p>
            
            <div class="flex space-x-4 mt-4 md:mt-0">
                <a href="https://www.facebook.com/nicolas.houbion.14/" target="_blank" class="w-8 h-8 flex items-center justify-center bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-indigo-600 hover:text-white rounded-full transition">
                    <i class="fab fa-facebook-f text-sm"></i>
                </a>
                <a href="https://www.linkedin.com/in/nicolas-houbion-ba6bb5204/" target="_blank" class="w-8 h-8 flex items-center justify-center bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-indigo-600 hover:text-white rounded-full transition">
                    <i class="fab fa-linkedin-in text-sm"></i>
                </a>
                <a href="https://github.com/NicolasHoubion" target="_blank" class="w-8 h-8 flex items-center justify-center bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-indigo-600 hover:text-white rounded-full transition">
                    <i class="fab fa-github text-sm"></i>
                </a>
            </div>
        </div>
    </div>
</footer>