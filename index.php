<?php
require_once __DIR__ . '/src/php/dbconn.php';
require_once __DIR__ . '/src/php/lang.php';

// Vérifier le thème avant d'inclure le header
$user_id = $_SESSION['id'] ?? 0;
$lang = getLanguage($db, $user_id);
$theme = getTheme($db, $user_id);

// Utiliser le cookie si disponible
$theme = $_COOKIE['theme_preference'] ?? $theme;

// Maintenant inclure le header
require_once __DIR__ . '/src/components/header.php';
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>" class="<?= $theme === 'dark' ? 'dark' : '' ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= t('site_title', $translations, $lang) ?></title>
    <style>
        :root {
            --gradient-start: #6366f1;
            /* indigo-500 */
            --gradient-end: #a5b4fc;
            /* indigo-200 */
            --cta-bg: #f9fafb;
            /* gray-50, plus doux */
            --cta-text: #312e81;
            /* indigo-900 */
            --section-bg: #f9fafb;
            /* gray-50, plus doux */
            --feature-bg: #f9fafb;
            /* gray-50, plus doux */
            --body-bg: #f9fafb;
            /* gray-50, plus doux */
        }

        .dark {
            --gradient-start: #3730A3;
            --gradient-end: #6D28D9;
            --cta-bg: #1e293b;
            /* slate-800 */
            --cta-text: #fff;
            --section-bg: #1e293b;
            --feature-bg: #374151;
            /* gray-700 */
            --body-bg: #111827;
            /* gray-900 */
        }

        body {
            background: var(--body-bg) !important;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .cta-soft {
            background: var(--body-bg);
            /* même couleur que le body */
            color: var(--cta-text);
        }

        .cta-soft .cta-btn {
            background: var(--gradient-start);
            color: #fff;
        }

        .cta-soft .cta-btn:hover {
            opacity: 0.9;
        }

        main {
            flex: 1;
        }
    </style>
</head>

<body class="transition-colors duration-200">

    <?php require_once 'src/components/header.php'; ?>

    <main>
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
        <section class="py-12 md:py-20 px-4 soft-section">
            <div class="container mx-auto text-center max-w-4xl">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">
                    <span class="gradient-text"><?= t('welcome', $translations, $lang) ?></span>
                </h1>
                <p class="text-xl lg:text-2xl text-gray-600 dark:text-gray-400 mb-10 max-w-3xl mx-auto">
                    <?= t('welcome_subtext', $translations, $lang) ?>
                </p>
                <div class="flex flex-col md:flex-row justify-center gap-4">
                    <a href="create_ticket.php" class="gradient-bg text-white py-3 px-8 rounded-lg font-medium shadow-lg hover:opacity-90 transition text-lg">
                        <?= t('create_ticket', $translations, $lang) ?>
                    </a>
                    <a href="yourticket.php" class="bg-white dark:bg-gray-800 text-indigo-600 dark:text-indigo-400 border border-indigo-600 dark:border-indigo-400 py-3 px-8 rounded-lg font-medium hover:bg-indigo-50 dark:hover:bg-gray-700 transition text-lg">
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

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                    <?php
                    $features = [
                        [
                            'icon' => 'fas fa-rocket',
                            'title' => t('feature_1_title', $translations, $lang),
                            'text' => t('feature_1_text', $translations, $lang)
                        ],
                        [
                            'icon' => 'fas fa-shield-alt',
                            'title' => t('feature_2_title', $translations, $lang),
                            'text' => t('feature_2_text', $translations, $lang)
                        ],
                        [
                            'icon' => 'fas fa-headset',
                            'title' => t('feature_3_title', $translations, $lang),
                            'text' => t('feature_3_text', $translations, $lang)
                        ]
                    ];

                    foreach ($features as $feature): ?>
                        <div class="feature-card dark:bg-gray-700 p-8 rounded-xl">
                            <div class="h-16 w-16 rounded-lg gradient-bg flex items-center justify-center text-white mb-6 mx-auto">
                                <i class="<?= $feature['icon'] ?> text-2xl"></i>
                            </div>
                            <h4 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4 text-center">
                                <?= $feature['title'] ?>
                            </h4>
                            <p class="text-gray-600 dark:text-gray-400 text-center">
                                <?= $feature['text'] ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
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
        });
    </script>
</body>

</html>