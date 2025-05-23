<?php
session_start();
require_once 'src/php/dbconn.php';
require_once 'src/php/lang.php';
require_once 'src/components/header.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$user_id = $_SESSION['id'] ?? 0;
$lang = getLanguage($db, $user_id);
$theme = getTheme($db, $user_id);
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>" class="<?= $theme === 'dark' ? 'dark' : '' ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= t('site_title', $translations, $lang) ?></title>
    <style>
:root {
    --gradient-start: #6366f1; /* indigo-500 */
    --gradient-end: #a5b4fc;   /* indigo-200 */
    --cta-bg: #f9fafb;         /* gray-50, plus doux */
    --cta-text: #312e81;       /* indigo-900 */
    --section-bg: #f9fafb;     /* gray-50, plus doux */
    --feature-bg: #f9fafb;     /* gray-50, plus doux */
    --body-bg: #f9fafb;        /* gray-50, plus doux */
}

.dark {
    --gradient-start: #3730A3;
    --gradient-end: #6D28D9;
    --cta-bg: #1e293b;         /* slate-800 */
    --cta-text: #fff;
    --section-bg: #1e293b;
    --feature-bg: #374151;     /* gray-700 */
    --body-bg: #111827;        /* gray-900 */
}

body {
    background: var(--body-bg) !important;
}

.gradient-bg {
    background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
}

.gradient-text {
    background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    color: transparent;
}

.soft-section {
    background: var(--section-bg);
}

.feature-card {
    background: var(--feature-bg);
    border: 1px solid #e5e7eb; /* Ajout d'une bordure pour les cartes */
}

.cta-soft {
    background: var(--body-bg); /* même couleur que le body */
    color: var(--cta-text);
}

.cta-soft .cta-btn {
    background: var(--gradient-start);
    color: #fff;
}

.cta-soft .cta-btn:hover {
    opacity: 0.9;
}

    </style>
</head>

<body class="min-h-screen transition-colors duration-200">

    <?php require_once 'src/components/header.php'; ?>

    <main class="flex-grow">
        <!-- Messages flash -->
        <?php if (isset($_SESSION['login_success']) && $_SESSION['login_success'] === true): ?>
            <div class="bg-green-100 dark:bg-green-900/20 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded mb-6 text-center">
                ✅ <?= t('login_success', $translations, $lang) ?>
            </div>
            <?php unset($_SESSION['login_success']); ?>
        <?php endif; ?>

        <?php if (isset($_GET['logout']) && $_GET['logout'] == 'success'): ?>
            <div class="bg-blue-100 dark:bg-blue-900/20 border border-blue-400 dark:border-blue-600 text-blue-700 dark:text-blue-300 px-4 py-3 rounded mb-6 text-center">
                🔒 <?= t('logout_success', $translations, $lang) ?>
            </div>
        <?php endif; ?>

        <!-- Hero Section -->
        <section class="py-16 md:py-24 px-4 soft-section">
            <div class="container mx-auto text-center max-w-4xl">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">
                    <span class="gradient-text"><?= t('welcome', $translations, $lang) ?></span>
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-400 mb-8">
                    <?= t('welcome_subtext', $translations, $lang) ?>
                </p>
                <div class="flex flex-col md:flex-row justify-center gap-4">
                    <a href="create_ticket.php" class="gradient-bg text-white py-3 px-8 rounded-lg font-medium shadow-lg hover:opacity-90 transition">
                        <?= t('create_ticket', $translations, $lang) ?>
                    </a>
                    <a href="yourticket.php" class="bg-white dark:bg-gray-800 text-indigo-600 dark:text-indigo-400 border border-indigo-600 dark:border-indigo-400 py-3 px-8 rounded-lg font-medium hover:bg-indigo-50 dark:hover:bg-gray-700 transition">
                        <?= t('my_tickets', $translations, $lang) ?>
                    </a>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-16 soft-section dark:bg-gray-800">
            <div class="container mx-auto px-4">
                <h3 class="text-3xl font-bold text-center text-gray-800 dark:text-gray-200 mb-12">
                    <?= t('why_choose_us', $translations, $lang) ?>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <?php
                    $features = [
                        [
                            'icon' => 'fas fa-rocket', // Exemple d'icône
                            'title' => t('feature_1_title', $translations, $lang),
                            'text' => t('feature_1_text', $translations, $lang)
                        ],
                        [
                            'icon' => 'fas fa-shield-alt', // Exemple d'icône
                            'title' => t('feature_2_title', $translations, $lang),
                            'text' => t('feature_2_text', $translations, $lang)
                        ],
                        [
                            'icon' => 'fas fa-headset', // Exemple d'icône
                            'title' => t('feature_3_title', $translations, $lang),
                            'text' => t('feature_3_text', $translations, $lang)
                        ]
                    ];

                    foreach ($features as $feature): ?>
                        <div class="feature-card dark:bg-gray-700 p-6 rounded-xl shadow-sm">
                            <div class="h-12 w-12 rounded-lg gradient-bg flex items-center justify-center text-white mb-4">
                                <i class="<?= $feature['icon'] ?> text-xl"></i>
                            </div>
                            <h4 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-2">
                                <?= $feature['title'] ?>
                            </h4>
                            <p class="text-gray-600 dark:text-gray-400">
                                <?= $feature['text'] ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-16">
            <div class="container mx-auto px-4 max-w-4xl">
                <div class="cta-soft rounded-2xl p-8 md:p-12 text-center transition-colors duration-200">
                    <h3 class="text-3xl font-bold mb-4">
                        <?= t('need_help_now', $translations, $lang) ?>
                    </h3>
                    <p class="text-lg mb-8" style="color:inherit;">
                        <?= t('help_description', $translations, $lang) ?>
                    </p>
                    <a href="create_ticket.php" class="cta-btn py-3 px-8 rounded-lg font-medium shadow-lg transition">
                        <?= t('create_ticket_now', $translations, $lang) ?>
                    </a>
                </div>
            </div>
        </section>
    </main>

    <?php require_once 'src/components/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Mobile menu toggle
            const menuBtn = document.querySelector('.fa-bars')?.parentElement;
            const closeBtn = document.getElementById('close-menu');
            const mobileMenu = document.getElementById('mobile-menu');

            if (menuBtn) {
                menuBtn.addEventListener('click', () => {
                    mobileMenu.classList.remove('hidden');
                });
            }

            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    mobileMenu.classList.add('hidden');
                });
            }

            // Gestion du thème (harmonisé avec header.php)
            const html = document.documentElement;
            let savedTheme = localStorage.getItem('theme');
            if (!savedTheme) {
                savedTheme = '<?= $theme ?>';
            }
            html.classList.toggle('dark', savedTheme === 'dark');
        });
    </script>
</body>
</html>