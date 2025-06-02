<?php
session_start();
require_once __DIR__ . '/src/php/dbconn.php';
require_once __DIR__ . '/src/php/lang.php';

// --- GESTION AJAX (avant tout HTML) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax']) && $_POST['ajax'] == '1') {
    // DEBUG TEMPORAIRE : afficher les erreurs PHP (à retirer en prod)
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Récupération des préférences utilisateur
    $user_id = $_SESSION['id'] ?? 0;

    // --- ✅ SOLUTION : AJOUTE CETTE LIGNE CI-DESSOUS ---
    // On charge la variable $translations pour que le composant puisse l'utiliser.
    // if (function_exists('t')) {
    //     $translations = t($lang);
    // }
    // Correction : $translations est déjà défini dans lang.php, il suffit de l'utiliser tel quel

    // Vérification des permissions
    $currentUserPermissions = [];
    if ($user_id > 0) {
        try {
            $stmt = $db->prepare("
                SELECT p.Name 
                FROM Users u
                JOIN Roles r ON u.Role_id = r.Id
                JOIN Permission_Roles pr ON r.Id = pr.Role_id
                JOIN Permissions p ON pr.Permission_id = p.Id
                WHERE u.Id = :user_id
            ");
            $stmt->bindValue(':user_id', $user_id);
            $stmt->execute();
            $currentUserPermissions = $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            $currentUserPermissions = [];
        }
    }

    // Récupérer le terme de recherche depuis POST
    $searchTermRaw = isset($_POST['search']) ? trim($_POST['search']) : '';
    $searchTerm = $searchTermRaw !== '' ? "%{$searchTermRaw}%" : null;

    // Requête SQL pour les utilisateurs
    $sql = "
    SELECT 
        u.Id AS user_id,
        u.Username,
        u.Firstname,
        u.mail,
        r.Name AS role_name,
        GROUP_CONCAT(DISTINCT p.Name SEPARATOR ', ') AS permissions
    FROM Users u
    JOIN Roles r ON u.Role_id = r.Id
    LEFT JOIN Permission_Roles pr ON r.Id = pr.Role_id
    LEFT JOIN Permissions p ON pr.Permission_id = p.Id
    WHERE u.Deleted_at IS NULL
";

    if ($searchTerm) {
        $sql .= " AND (
        u.Username LIKE :search1 
        OR u.mail LIKE :search2 
        OR u.Firstname LIKE :search3
    )";
    }

    $sql .= " GROUP BY u.Id"; // Regroupement par ID utilisateur

    // Exécution
    try {
        $queryUsers = $db->prepare($sql);
        if ($searchTerm) {
            $queryUsers->bindValue(':search1', $searchTerm);
            $queryUsers->bindValue(':search2', $searchTerm);
            $queryUsers->bindValue(':search3', $searchTerm);
        }
        $queryUsers->execute();
        $results = $queryUsers->fetchAll();

        $users = [];
        foreach ($results as $row) {
            $id = $row['user_id'];
            $permissions = [];
            if (!empty($row['permissions'])) {
                $permissions = explode(', ', $row['permissions']);
            }
            $users[$id] = [
                'user_id' => $id,
                'username' => $row['Username'],
                'firstname' => $row['Firstname'],
                'email' => $row['mail'],
                'role' => $row['role_name'],
                'permissions' => $permissions
            ];
        }
    } catch (PDOException $e) {
        error_log("Erreur SQL: " . $e->getMessage());
        $users = [];
    }

    // Correction : définir $canManageUsers pour le composant
    $canManageUsers = hasPermission('Manage Users', $currentUserPermissions) || hasPermission('Access Admin Panel', $currentUserPermissions);

    // Inclure uniquement le contenu de la section utilisateurs (HTML)
    include __DIR__ . '/src/components/admin_users_section_content.php';
    exit;
}

// Récupération des préférences
$user_id = $_SESSION['id'] ?? 0;
$lang = getLanguage($db, $user_id);
$theme = getTheme($db, $user_id);

// Vérification des permissions (doit être AVANT la suppression)
$currentUserPermissions = [];
if ($user_id > 0) {
    try {
        $stmt = $db->prepare("
            SELECT p.Name 
            FROM Users u
            JOIN Roles r ON u.Role_id = r.Id
            JOIN Permission_Roles pr ON r.Id = pr.Role_id
            JOIN Permissions p ON pr.Permission_id = p.Id
            WHERE u.Id = :user_id
        ");
        $stmt->bindValue(':user_id', $user_id);
        $stmt->execute();
        $currentUserPermissions = $stmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        $currentUserPermissions = [];
    }
}

// Fonction pour vérifier les permissions
function hasPermission($permission, $permissions)
{
    return is_array($permissions) && in_array($permission, $permissions);
}

// AJOUTER ICI : définition de $canManageUsers pour le rendu initial
$canManageUsers = hasPermission('Manage Users', $currentUserPermissions) || hasPermission('Access Admin Panel', $currentUserPermissions);

// Vérifier si l'utilisateur a accès à cette page
if (!hasPermission('Manage Users', $currentUserPermissions) && !hasPermission('Access Admin Panel', $currentUserPermissions)) {
    header("Location: index.php");
    exit;
}

// Suppression de ticket si requête POST
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['delete_ticket_id'])
    && (hasPermission('Delete Tickets', $currentUserPermissions) || hasPermission('Access Admin Panel', $currentUserPermissions))
) {
    $ticketIdToDelete = intval($_POST['delete_ticket_id']);
    try {
        // --- SUPPRESSION LOGIQUE (soft delete) ---
        // $stmt = $db->prepare("UPDATE Ticket SET Deleted_at = NOW() WHERE Id = :id");
        // $stmt->bindValue(':id', $ticketIdToDelete, PDO::PARAM_INT);
        // $stmt->execute();

        // --- POUR UNE SUPPRESSION PHYSIQUE, DÉCOMMENTE LA LIGNE SUIVANTE ET COMMENTE LES 3 LIGNES AU-DESSUS ---
        // $stmt = $db->prepare("DELETE FROM Ticket WHERE Id = :id");
        // $stmt->bindValue(':id', $ticketIdToDelete, PDO::PARAM_INT);
        // $stmt->execute();

        header("Location: admin.php?deleted=1");
        exit;
    } catch (PDOException $e) {
        // Affiche une erreur si la colonne Deleted_at n'existe pas
        echo "<div style='color:red'>Erreur lors de la suppression du ticket : " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}

// Inclure le header APRÈS la gestion des headers/redirections
require_once __DIR__ . '/src/components/header.php';

// Fonction utilitaire pour formater la date en français
function formatDateFr($datetime)
{
    $fmt = new IntlDateFormatter(
        'fr_FR',
        IntlDateFormatter::LONG,
        IntlDateFormatter::SHORT,
        'Europe/Brussels',
        IntlDateFormatter::GREGORIAN,
        "d MMMM yyyy 'à' HH:mm"
    );
    return $fmt->format(new DateTime($datetime));
}

// Récupération des tickets
try {
    $queryTickets = $db->query("
        SELECT t.Id, t.Title, u.Username, t.Created_at 
        FROM Ticket t
        LEFT JOIN Users u ON t.User_id = u.Id
        WHERE t.Deleted_at IS NULL
        ORDER BY t.Created_at DESC
    ");
    $tickets = $queryTickets->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $tickets = [];
}

// Récupération du terme de recherche
$searchTermRaw = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchTerm = $searchTermRaw !== '' ? "%{$searchTermRaw}%" : null;

// Requête SQL pour les utilisateurs
$sql = "
    SELECT 
        u.Id AS user_id,
        u.Username,
        u.Firstname,
        u.mail,
        r.Name AS role_name,
        GROUP_CONCAT(DISTINCT p.Name SEPARATOR ', ') AS permissions
    FROM Users u
    JOIN Roles r ON u.Role_id = r.Id
    LEFT JOIN Permission_Roles pr ON r.Id = pr.Role_id
    LEFT JOIN Permissions p ON pr.Permission_id = p.Id
    WHERE u.Deleted_at IS NULL
";

if ($searchTerm) {
    $sql .= " AND (
        u.Username LIKE :search1 
        OR u.mail LIKE :search2 
        OR u.Firstname LIKE :search3
    )";
}

$sql .= " GROUP BY u.Id"; // Ajoutez cette ligne

// Exécution
try {
    $queryUsers = $db->prepare($sql);
    if ($searchTerm) {
        $queryUsers->bindValue(':search1', $searchTerm);
        $queryUsers->bindValue(':search2', $searchTerm);
        $queryUsers->bindValue(':search3', $searchTerm);
    }
    $queryUsers->execute();
    $results = $queryUsers->fetchAll();

    $users = [];
    foreach ($results as $row) {
        $id = $row['user_id'];
        $permissions = [];
        if (!empty($row['permissions'])) {
            $permissions = explode(', ', $row['permissions']);
        }
        $users[$id] = [
            'user_id' => $id,
            'username' => $row['Username'],
            'firstname' => $row['Firstname'],
            'email' => $row['mail'],
            'role' => $row['role_name'],
            'permissions' => $permissions
        ];
    }
} catch (PDOException $e) {
    error_log("Erreur SQL: " . $e->getMessage());
    $users = [];
}
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>" class="<?= $theme === 'dark' ? 'dark' : '' ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= t('admin_panel', $translations, $lang) ?> - <?= t('site_title', $translations, $lang) ?></title>
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
        :root {
            --gradient-start: #6366f1;
            --gradient-end: #a5b4fc;
            --cta-bg: #f9fafb;
            --cta-text: #312e81;
            --section-bg: #f9fafb;
            --feature-bg: #f9fafb;
            --body-bg: #f9fafb;
        }

        .dark {
            --gradient-start: #3730A3;
            --gradient-end: #6D28D9;
            --cta-bg: #1e293b;
            --cta-text: #fff;
            --section-bg: #1e293b;
            --feature-bg: #374151;
            --body-bg: #111827;
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
            border: 1px solid #e5e7eb;
        }

        .cta-soft {
            background: var(--body-bg);
            color: var(--cta-text);
        }

        .cta-soft .cta-btn {
            background: var(--gradient-start);
            color: #fff;
        }

        .cta-soft .cta-btn:hover {
            opacity: 0.9;
        }

        /* Nouveaux styles pour indicateur de chargement */
        .loading-indicator {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }

        .search-container {
            position: relative;
        }

        .spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body class="min-h-screen transition-colors duration-200">
    <main class="flex-grow py-12 px-4">
        <div class="container mx-auto max-w-7xl">
            <!-- En-tête de la page -->
            <div class="mb-8 text-center">
                <h1 class="text-4xl font-bold mb-2 gradient-text inline-block">
                    <i class="fas fa-shield-alt mr-2"></i><?= t('admin_panel', $translations, $lang) ?>
                </h1>
                <p class="text-gray-600 dark:text-gray-400 text-lg">
                    <?= t('admin_panel_description', $translations, $lang) ?? 'Gérez les tickets et les utilisateurs de votre plateforme.' ?>
                </p>
            </div>

            <!-- Messages flash -->
            <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
                <div class="bg-green-100 dark:bg-green-900/20 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded mb-6 text-center">
                    <i class="fas fa-check-circle mr-2"></i><?= t('ticket_deleted', $translations, $lang) ?? 'Le ticket a été supprimé avec succès.' ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['user_deleted']) && $_GET['user_deleted'] == 1): ?>
                <div class="bg-green-100 dark:bg-green-900/20 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded mb-6 text-center">
                    <i class="fas fa-check-circle mr-2"></i><?= t('user_deleted', $translations, $lang) ?? 'L\'utilisateur a été supprimé avec succès.' ?>
                </div>
            <?php endif; ?>

            <!-- Navigation par onglets -->
            <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
                    <li class="mr-2">
                        <a href="#tickets" class="inline-block p-4 border-b-2 border-indigo-600 dark:border-indigo-500 text-indigo-600 dark:text-indigo-500 rounded-t-lg active" aria-current="page">
                            <i class="fas fa-ticket-alt mr-2"></i><?= t('tickets', $translations, $lang) ?>
                        </a>
                    </li>
                    <li class="mr-2">
                        <a href="#users" class="inline-block p-4 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 rounded-t-lg">
                            <i class="fas fa-users mr-2"></i><?= t('users', $translations, $lang) ?>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- SECTION TICKETS -->
            <section id="tickets" class="mb-12">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold dark:text-gray-200">
                        <i class="fas fa-ticket-alt mr-2 text-indigo-500 dark:text-indigo-400"></i><?= t('ticket_management', $translations, $lang) ?>
                    </h2>
                    <span class="bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300 text-sm font-semibold px-3 py-1 rounded-full">
                        <?= count($tickets) ?> <?= t('tickets', $translations, $lang) ?>
                    </span>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-8">
                    <?php if (count($tickets) > 0): ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"><?= t('title', $translations, $lang) ?></th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"><?= t('user', $translations, $lang) ?></th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"><?= t('created_at', $translations, $lang) ?></th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"><?= t('actions', $translations, $lang) ?></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <?php foreach ($tickets as $ticket): ?>
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200"><?= htmlspecialchars($ticket['Id']) ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-300"><?= htmlspecialchars($ticket['Title']) ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-300">
                                                <span class="inline-flex items-center">
                                                    <i class="fas fa-user-circle mr-2 text-indigo-500 dark:text-indigo-400"></i>
                                                    <?= htmlspecialchars($ticket['Username']) ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                <span class="inline-flex items-center">
                                                    <i class="far fa-calendar-alt mr-2"></i>
                                                    <?= formatDateFr($ticket['Created_at']) ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="ticket_view.php?id=<?= $ticket['Id'] ?>" class="gradient-bg text-white px-4 py-2 rounded-lg shadow-sm hover:opacity-90 transition mr-2">
                                                    <i class="fas fa-eye mr-1"></i> <?= t('view', $translations, $lang) ?>
                                                </a>
                                                <?php if (hasPermission('Delete Tickets', $currentUserPermissions) || hasPermission('Access Admin Panel', $currentUserPermissions)): ?>
                                                    <form method="POST" action="admin.php" class="inline-block" onsubmit="return confirm('<?= t('confirm_delete_ticket', $translations, $lang) ?? 'Êtes-vous sûr de vouloir supprimer ce ticket ? Cette action est irréversible.' ?>');">
                                                        <input type="hidden" name="delete_ticket_id" value="<?= $ticket['Id'] ?>">
                                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg shadow-sm transition">
                                                            <i class="fas fa-trash-alt mr-1"></i> <?= t('delete', $translations, $lang) ?>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="p-12 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 mb-6 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-500 dark:text-indigo-400">
                                <i class="fas fa-ticket-alt text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-medium text-gray-900 dark:text-gray-200 mb-2"><?= t('no_tickets_title', $translations, $lang) ?? 'Aucun ticket' ?></h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-6"><?= t('no_tickets', $translations, $lang) ?? 'Il n\'y a actuellement aucun ticket dans le système.' ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <!-- SECTION UTILISATEURS -->
            <section id="users" class="mb-12">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold dark:text-gray-200">
                        <i class="fas fa-users mr-2 text-indigo-500 dark:text-indigo-400"></i><?= t('user_management', $translations, $lang) ?>
                    </h2>
                    <span id="user-count" class="bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300 text-sm font-semibold px-3 py-1 rounded-full">
                        <?= count($users) ?> <?= t('users', $translations, $lang) ?>
                    </span>
                </div>

                <!-- Formulaire de recherche -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-8 p-6">
                    <form id="search-form" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-[200px] search-container">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="fas fa-search text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <input type="text" id="search-input" name="search" value="<?= htmlspecialchars($searchTermRaw) ?>"
                                    placeholder="<?= t('search_users', $translations, $lang) ?>"
                                    class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 p-2.5">
                                <div id="loading-indicator" class="loading-indicator hidden">
                                    <i class="fas fa-spinner spinner text-indigo-500 dark:text-indigo-400"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="gradient-bg text-white py-2.5 px-5 rounded-lg font-medium shadow-sm hover:opacity-90 transition">
                                <i class="fas fa-search mr-1"></i> <?= t('search', $translations, $lang) ?>
                            </button>
                            <a href="#" id="reset-search" class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 py-2.5 px-5 rounded-lg font-medium shadow-sm hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                                <i class="fas fa-redo mr-1"></i> <?= t('reset', $translations, $lang) ?>
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Conteneur pour les résultats de recherche -->
                <div id="search-results">
                    <?php include __DIR__ . '/src/components/admin_users_section_content.php'; ?>
                </div>
            </section>

        </div>
    </main>

    <?php require_once 'src/components/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // ... (code existant) ...

            // Nouveau code pour la recherche AJAX
            const searchForm = document.getElementById('search-form');
            const searchInput = document.getElementById('search-input');
            const resetSearchBtn = document.getElementById('reset-search');
            const searchResults = document.getElementById('search-results');
            const loadingIndicator = document.getElementById('loading-indicator');
            const userCountSpan = document.getElementById('user-count');

            // Fonction pour charger les résultats de recherche
            function loadSearchResults(searchTerm) {
                loadingIndicator.classList.remove('hidden');
                const formData = new FormData();
                formData.append('search', searchTerm);
                formData.append('ajax', '1');
                fetch('admin.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(html => {
                        searchResults.innerHTML = html;
                        // Correction : compter les lignes utilisateurs (tr ou .user-row selon votre HTML)
                        const newUserCount = searchResults.querySelectorAll('tr.user-row').length;
                        if (userCountSpan) {
                            // Correction : traduction dynamique du mot "utilisateurs"
                            userCountSpan.textContent = `${newUserCount} <?= t('users', $translations, $lang) ?>`;
                        }
                        attachDeleteHandlers();
                    })
                    .catch(error => {
                        console.error('Erreur lors de la recherche:', error);
                        searchResults.innerHTML = `<div class="bg-red-100 dark:bg-red-900/20 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 px-4 py-3 rounded mb-6 text-center">
                <i class="fas fa-exclamation-triangle mr-2"></i><?= t('search_error', $translations, $lang) ?? 'Erreur lors de la recherche.' ?>
            </div>`;
                    })
                    .finally(() => {
                        loadingIndicator.classList.add('hidden');
                    });
            }

            // Gestionnaire de soumission du formulaire
            searchForm.addEventListener('submit', (e) => {
                e.preventDefault();
                loadSearchResults(searchInput.value);
            });

            // Gestionnaire de réinitialisation
            resetSearchBtn.addEventListener('click', (e) => {
                e.preventDefault();
                searchInput.value = '';
                loadSearchResults('');
            });

            // Gestionnaire d'entrée pour la recherche instantanée (avec debounce)
            let searchTimeout;
            searchInput.addEventListener('input', () => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    loadSearchResults(searchInput.value);
                }, 300);
            });

            // Fonction pour réattacher les gestionnaires de suppression
            function attachDeleteHandlers() {
                document.querySelectorAll('.delete-user-btn').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        if (!confirm('<?= t('confirm_delete_user', $translations, $lang) ?>')) {
                            e.preventDefault();
                        }
                    });
                });
            }

            // Attacher initialement les gestionnaires
            attachDeleteHandlers();
        });
        // Correction : déplacer la fonction loadSearchResults dans le scope global pour clear-search
        function loadSearchResults(searchTerm) {
            const loadingIndicator = document.getElementById('loading-indicator');
            const searchResults = document.getElementById('search-results');
            const userCountSpan = document.getElementById('user-count');
            loadingIndicator.classList.remove('hidden');
            const formData = new FormData();
            formData.append('search', searchTerm);
            formData.append('ajax', '1');
            fetch('admin.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(html => {
                    searchResults.innerHTML = html;
                    const newUserCount = searchResults.querySelectorAll('tr.user-row').length;
                    if (userCountSpan) {
                        userCountSpan.textContent = `${newUserCount} <?= t('users', $translations, $lang) ?>`;
                    }
                })
                .finally(() => {
                    loadingIndicator.classList.add('hidden');
                });
        }
        document.addEventListener('click', function(e) {
            if (e.target && e.target.id === 'clear-search') {
                e.preventDefault();
                document.getElementById('search-input').value = '';
                loadSearchResults('');
            }
        });
    </script>
</body>

</html>
<?php
// Fermer la connexion
$db = null;
?>