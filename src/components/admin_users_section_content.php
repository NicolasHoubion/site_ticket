<?php
// ...existing code...
// DEBUG TEMPORAIRE - À RETIRER EN PRODUCTION
// error_log("Permissions de l'utilisateur: " . implode(', ', $currentUserPermissions));
// error_log("Can manage users: " . ($canManageUsers ? 'yes' : 'no'));
// error_log("User count: " . count($users));
// foreach ($users as $u) {
//     error_log("User: " . $u['username'] . " - " . $u['email']);
// }
// FIN DU DEBUG
?>
<?php
// Vérifier si l'utilisateur a les permissions pour gérer les utilisateurs
?>

<?php if (count($users) > 0 && $canManageUsers): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <?php foreach ($users as $user): ?>
            <?php
            $roleName = strtolower($user['role']);
            switch ($roleName) {
                case 'admin':
                    $badgeClass = 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300 border border-red-200 dark:border-red-700';
                    $iconClass = 'fas fa-crown text-red-500';
                    break;
                case 'helper':
                    $badgeClass = 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300 border border-purple-200 dark:border-purple-700';
                    $iconClass = 'fas fa-hands-helping text-purple-500';
                    break;
                case 'dev':
                    $badgeClass = 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-700';
                    $iconClass = 'fas fa-code text-emerald-500';
                    break;
                default:
                    $badgeClass = 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-700';
                    $iconClass = 'fas fa-user text-blue-500';
                    break;
            }
            ?>
            <div class="user-row bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden transition-transform duration-300 hover:-translate-y-1 hover:shadow-xl">
                <div class="gradient-bg p-6 flex justify-between items-center">
                    <div class="flex items-center">
                        <div class="h-12 w-12 rounded-full bg-white/20 flex items-center justify-center text-white">
                            <i class="<?= $iconClass ?> text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-bold text-white"><?= htmlspecialchars($user['username']) ?></h3>
                            <p class="text-indigo-100 text-sm">
                                <?= !empty($user['firstname']) ? htmlspecialchars($user['firstname']) : '' ?>
                                <?= !empty($user['email']) ? '- ' . htmlspecialchars($user['email']) : '' ?>
                            </p>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $badgeClass; ?> whitespace-nowrap">
                        <?= htmlspecialchars($user['role']) ?>
                    </span>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                            <?= t('permissions', $translations, $lang) ?>
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach ($user['permissions'] as $permission): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                    <?= htmlspecialchars($permission) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="edit_user.php?id=<?= $user['user_id'] ?>" class="inline-flex items-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                            <i class="fas fa-edit mr-1"></i> <?= t('edit', $translations, $lang) ?>
                        </a>
                        <a href="delete_user.php?id=<?= $user['user_id'] ?>" class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg shadow-sm transition-colors delete-user-btn">
                            <i class="fas fa-trash-alt mr-1"></i> <?= t('delete', $translations, $lang) ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden p-12 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 mb-6 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-500 dark:text-indigo-400">
            <i class="fas fa-users text-2xl"></i>
        </div>
        <h3 class="text-xl font-medium text-gray-900 dark:text-gray-200 mb-2">
            <?= t('no_users_found', $translations, $lang) ?? 'Aucun utilisateur trouvé' ?>
        </h3>
        <p class="text-gray-500 dark:text-gray-400 mb-6">
            <?= t('no_users_permission', $translations, $lang) ?? 'Aucun utilisateur ne correspond à vos critères de recherche.' ?>
        </p>
        <?php if (!empty($searchTermRaw)): ?>
            <a href="#" id="clear-search" class="gradient-bg text-white py-2.5 px-5 rounded-lg font-medium shadow-sm hover:opacity-90 transition">
                <i class="fas fa-redo mr-1"></i> <?= t('clear_search', $translations, $lang) ?? 'Effacer la recherche' ?>
            </a>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php if (hasPermission('Access Admin Panel', $currentUserPermissions)): ?>
    <div class="mt-8 text-center">
        <a href="add_user.php" class="gradient-bg text-white py-3 px-8 rounded-lg font-medium shadow-lg hover:opacity-90 transition inline-flex items-center">
            <i class="fas fa-user-plus mr-2"></i> <?= t('add_user', $translations, $lang) ?? 'Ajouter un utilisateur' ?>
        </a>
    </div>
<?php endif ?>