<?php
session_start();
require_once 'src/php/dbconn.php';
require_once 'src/php/lang.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];
$lang = getLanguage($db, $user_id);
$theme = getTheme($db, $user_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $db->prepare("
        INSERT INTO User_Settings (user_id, setting_key, setting_value)
        VALUES (:user_id, :key, :value)
        ON DUPLICATE KEY UPDATE setting_value = :value
    ");

    foreach ($_POST['settings'] as $key => $value) {
        $stmt->execute([
            ':user_id' => $user_id,
            ':key'     => $key,
            ':value'   => $value
        ]);

        // Mettre à jour le cookie pour le thème
        if ($key === 'theme') {
            setcookie('theme_preference', $value, time() + (365 * 24 * 60 * 60), "/");
        }
    }

    // Mettre à jour $lang si la langue a été changée
    if (isset($_POST['settings']['language'])) {
        $lang = $_POST['settings']['language'];
    }

    $_SESSION['success_message'] = t('settings_updated', $translations, $lang);
    header("Location: param.php");
    exit;
}

$settings = $db->prepare("SELECT setting_key, setting_value FROM User_Settings WHERE user_id = ?");
$settings->execute([$_SESSION['id']]);
$userSettings = $settings->fetchAll(PDO::FETCH_KEY_PAIR);

// Déplacez ce require_once ici, juste avant le HTML
require_once 'src/components/header.php';
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>" class="<?= $theme === 'dark' ? 'dark' : '' ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= t('settings', $translations, $lang) ?> - <?= t('site_title', $translations, $lang) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            light: '#4F46E5',
                            dark: '#3730A3'
                        },
                        secondary: {
                            light: '#7C3AED',
                            dark: '#6D28D9'
                        }
                    },
                    gradientColorStops: theme => ({
                        'gradient-start': theme('colors.primary.light'),
                        'gradient-end': theme('colors.secondary.light'),
                    })
                }
            }
        }
    </script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
        }

        .dark .gradient-bg {
            background: linear-gradient(135deg, #3730A3 0%, #6D28D9 100%);
        }

        /* Nouveau style pour le sélecteur de langue */
        .language-selector {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 0.75rem;
        }

        .language-option {
            background-color: #f9fafb;
            color: #1f2937;
            /* Couleur de texte plus foncée */
            border: 2px solid #e5e7eb;
            /* Bordure plus visible */
        }

        .language-option:hover {
            background-color: #f3f4f6;
            /* Fond légèrement plus foncé au survol */
        }

        .language-option.active {
            border-color: #4F46E5;
            background-color: #e0e7ff;
            /* Fond plus visible pour l'option active */
            color: #3730a3;
            /* Texte plus foncé */
        }

        /* Prévisualisation du thème plus visible */
        .theme-preview {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .theme-preview::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 50%;
            border: 2px solid #d1d5db;
            transition: all 0.2s ease;
        }

        .theme-preview.light {
            background: linear-gradient(135deg, #f9fafb 50%, #6366f1 50%);
        }

        .theme-preview.dark {
            background: linear-gradient(135deg, #111827 50%, #3730A3 50%);
        }

        .theme-preview.active::before {
            border-color: #4F46E5;
            border-width: 3px;
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2);
        }

        .dark .theme-preview::before {
            border-color: #4b5563;
        }

        .dark .theme-preview.active::before {
            border-color: #818cf8;
            box-shadow: 0 0 0 2px rgba(129, 140, 248, 0.2);
        }

        /* Effet de survol */
        .theme-preview:hover::before {
            border-color: #9ca3af;
        }

        /* Bouton de sauvegarde plus visible */
        .save-btn {
            background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
            color: white;
        }

        .save-btn:hover {
            opacity: 0.9;
        }

        /* Amélioration du texte dans le formulaire */
        .settings-form label {
            color: #1f2937;
            /* Texte plus foncé en light mode */
        }

        .dark .settings-form label {
            color: #d1d5db;
            /* Texte clair en dark mode */
        }

        button[type="submit"] {
            background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%) !important;
            color: white !important;
        }
    </style>
</head>

<body class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <main class="max-w-4xl mx-auto p-6 flex-grow w-full min-h-[calc(100vh-12rem)]">
        <h2 class="text-3xl font-bold mb-6 text-gray-800 dark:text-gray-200 text-center">
            <?= t('personal_settings', $translations, $lang) ?>
        </h2>
        <?php
        if (!empty($_SESSION['success_message'])):
            $success_message = $_SESSION['success_message'];
            unset($_SESSION['success_message']);
        ?>
            <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-300 rounded border border-green-400 dark:border-green-600">
                <?= htmlspecialchars($success_message) ?>
            </div>
        <?php endif; ?>

        <form method="post" class="settings-form space-y-6 bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl max-w-xl mx-auto border border-gray-200 dark:border-gray-700">
            <div class="space-y-6">
                <div>
                    <label class="block font-semibold mb-3 text-gray-700 dark:text-gray-300">
                        <i class="fas fa-language mr-2"></i><?= t('language', $translations, $lang) ?>
                    </label>

                    <!-- Nouveau sélecteur de langue -->
                    <div class="language-selector">
                        <input type="hidden" name="settings[language]" id="selected-language" value="<?= $userSettings['language'] ?? 'en' ?>">

                        <div class="language-option <?= ($userSettings['language'] ?? 'en') === 'en' ? 'active' : '' ?>" data-lang="en">
                            <div class="language-icon">🇬🇧</div>
                            <span class="text-sm font-medium">English</span>
                        </div>

                        <div class="language-option <?= ($userSettings['language'] ?? 'en') === 'fr' ? 'active' : '' ?>" data-lang="fr">
                            <div class="language-icon">🇫🇷</div>
                            <span class="text-sm font-medium">Français</span>
                        </div>

                        <div class="language-option <?= ($userSettings['language'] ?? 'en') === 'es' ? 'active' : '' ?>" data-lang="es">
                            <div class="language-icon">🇪🇸</div>
                            <span class="text-sm font-medium">Español</span>
                        </div>

                        <div class="language-option <?= ($userSettings['language'] ?? 'en') === 'nl' ? 'active' : '' ?>" data-lang="nl">
                            <div class="language-icon">🇳🇱</div>
                            <span class="text-sm font-medium">Nederlands</span>
                        </div>

                        <div class="language-option <?= ($userSettings['language'] ?? 'en') === 'zh' ? 'active' : '' ?>" data-lang="zh">
                            <div class="language-icon">🇨🇳</div>
                            <span class="text-sm font-medium">中文</span>
                        </div>

                        <div class="language-option <?= ($userSettings['language'] ?? 'en') === 'pa' ? 'active' : '' ?>" data-lang="pa">
                            <div class="language-icon">🇮🇳</div>
                            <span class="text-sm font-medium">ਪੰਜਾਬੀ</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block font-semibold mb-3 text-gray-700 dark:text-gray-300">
                        <i class="fas fa-moon mr-2"></i><?= t('theme', $translations, $lang) ?>
                    </label>
                    <div class="flex justify-center space-x-8">
                        <div class="flex flex-col items-center">
                            <div class="theme-preview light <?= ($userSettings['theme'] ?? 'light') === 'light' ? 'active' : '' ?>" data-theme="light"></div>
                            <span class="mt-2 text-sm font-medium text-gray-700 dark:text-gray-300"><?= t('light_theme', $translations, $lang) ?></span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="theme-preview dark <?= ($userSettings['theme'] ?? 'light') === 'dark' ? 'active' : '' ?>" data-theme="dark"></div>
                            <span class="mt-2 text-sm font-medium text-gray-700 dark:text-gray-300"><?= t('dark_theme', $translations, $lang) ?></span>
                        </div>
                    </div>
                    <input type="hidden" name="settings[theme]" id="selected-theme" value="<?= $userSettings['theme'] ?? 'light' ?>">
                </div>
            </div>

            <button type="submit" class="w-full save-btn font-bold py-3 px-6 rounded-lg transition duration-200 hover:opacity-90 flex items-center justify-center">
                <i class="fas fa-save mr-2"></i><?= t('save', $translations, $lang) ?>
            </button>
        </form>
    </main>

    <?php require_once 'src/components/footer.php'; ?>

    <script>
        // Gestion du sélecteur de langue
        document.querySelectorAll('.language-option').forEach(option => {
            option.addEventListener('click', () => {
                // Retirer la classe active de toutes les options
                document.querySelectorAll('.language-option').forEach(opt => {
                    opt.classList.remove('active');
                });

                // Ajouter la classe active à l'option sélectionnée
                option.classList.add('active');

                // Mettre à jour la valeur cachée
                document.getElementById('selected-language').value = option.dataset.lang;
            });
        });

        // Gestion du sélecteur de thème
        document.querySelectorAll('.theme-preview').forEach(preview => {
            preview.addEventListener('click', () => {
                // Retirer la classe active de toutes les prévisualisations
                document.querySelectorAll('.theme-preview').forEach(p => {
                    p.classList.remove('active');
                });

                // Ajouter la classe active à la prévisualisation sélectionnée
                preview.classList.add('active');

                // Mettre à jour la valeur cachée
                document.getElementById('selected-theme').value = preview.dataset.theme;
            });
        });
    </script>
</body>

</html>