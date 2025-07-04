<?php
// Par défaut, on met "fr"

function getLanguage($db, $user_id = null)
{
    try {
        if (!$db || !$user_id) {
            return 'en'; // Langue par défaut si la connexion à la base de données ou l'utilisateur est absent
        }
        $stmt = $db->prepare("SELECT setting_value FROM User_Settings WHERE user_id = ? AND setting_key = 'language'");
        $stmt->execute([$user_id]);
        return $stmt->fetchColumn() ?? 'fr';
    } catch (PDOException $e) {
        return 'fr';
    }
}

function getTheme($db, $user_id)
{
    // Pour les utilisateurs non connectés, utiliser le cookie ou 'light' par défaut
    if ($user_id === 0) {
        return $_COOKIE['theme_preference'] ?? 'light';
    }

    try {
        $stmt = $db->prepare("SELECT setting_value 
                             FROM User_Settings 
                             WHERE user_id = ? 
                             AND setting_key = 'theme'");
        $stmt->execute([$user_id]);
        $result = $stmt->fetchColumn();

        // Validation stricte
        return in_array($result, ['light', 'dark']) ? $result : 'light';
    } catch (PDOException $e) {
        error_log("Erreur theme : " . $e->getMessage());
        return 'light';
    }
}

// Définir la langue globale
$lang = isset($db) && isset($user_id) ? getLanguage($db, $user_id) : 'en';

$translations = [

    // FRENCH TRANSLATE
    'fr' => [
        'site_title' => 'YourTicket',
        'welcome' => 'Bienvenue sur YourTicket',
        'welcome_subtext' => 'Gérez vos tickets de support facilement',
        'create_ticket' => 'Créer un ticket',
        'create_ticket_help' => 'Soumettez une nouvelle demande d\'aide',
        'my_tickets' => 'Mes tickets',
        'create' => 'Créer',
        'view' => 'Voir',
        'login_success' => 'Connexion réussie! Bienvenue',
        'logout_success' => 'Vous avez été déconnecté avec succès',
        'admin_panel' => 'Panneau d\'administration',
        'access_admin' => 'Accéder au panel',
        'fill_all_fields' => 'Veuillez remplir tous les champs',
        'ticket_created' => 'Ticket créé avec succès',
        'creation_error' => 'Erreur lors de la création',
        'title' => 'Titre',
        'message' => 'Message',
        'create_ticket_button' => 'Créer le ticket',
        'no_user_specified' => 'Aucun utilisateur spécifié',
        'user_not_found' => 'Utilisateur non trouvé',
        'user_updated' => 'Utilisateur mis à jour',
        'update_error' => 'Erreur de mise à jour',
        'settings' => 'Paramètres',
        'personal_settings' => 'Paramètres personnels',
        'save' => 'Enregistrer',
        'profile' => 'Profil',
        'login_required' => 'Connexion requise',
        'profile_of' => 'Profil de',
        'ticket' => 'Ticket',
        'new_message' => 'Nouveau message',
        'send' => 'Envoyer',
        'admin_actions' => 'Actions administratives',
        'close_ticket' => 'Fermer le Ticket',
        'light_theme' => 'Thème clair',
        'dark_theme' => 'Thème sombre',
        'full_name' => 'Nom complet',
        'select_image' => 'Sélectionner une image',
        'my_conversations' => 'Mes conversations',
        'view_conversation' => 'Voir la conversation',
        'user_management' => 'Gestion des utilisateurs',
        'ticket_management' => 'Gestion des tickets',
        'created_at' => 'Créé le',
        'profile_details' => 'Détails du profil',
        'language' => 'Langue',
        'settings_updated' => 'Paramètres mis à jour avec succès',
        'theme' => 'Thème',
        'YourTicket' => 'YourTicket',
        'Tickets' => 'Tickets',
        'Settings' => 'Paramètres',
        'Profile' => 'Mon Profil',
        'Logout' => 'Déconnexion',
        'Admin' => 'Admin',
        'Login' => 'Connexion',
        'Language' => 'Langue',
        'created_on' => 'Créé le',
        'all_rights_reserved' => 'Tous droits réservés',
        'no_users_permission' => 'Aucun utilisateur trouvé avec les permissions spécifiées',
        'change_profile_picture' => 'Changer la photo de profil',
        'profile_picture_updated' => 'Photo de profil mise à jour avec succès',
        'no_tickets' => 'Aucun ticket trouvé',
        'setting_updated' => 'Paramètre mis à jour avec succès',
        'no_tickets_found' => 'Aucun ticket trouvé',
        'ticket_not_found' => 'Ticket non trouvé',
        'ticket_closed' => 'Ticket fermé avec succès',
        'ticket_already_closed' => 'Le ticket est déjà fermé',
        'message_sent' => 'Message envoyé avec succès',
        'message_error' => 'Erreur lors de l\'envoi du message',
        'no_messages_found' => 'Aucun message trouvé',
        'update' => 'Mettre à jour',
        'email' => 'email',
        'username' => 'Nom d\'utilisateur',
        'role' => 'Rôle',
        'edit' => 'Éditer',
        'delete' => 'Supprimer',
        'permissions' => 'Permission',
        'search_users' => 'Rechercher un utilisateur',
        'search' => 'Rechercher',
        'reset' => 'Réinitialiser',
        'edit_user' => 'Éditer l\'utilisateur',
        'ticket_deleted' => 'Ticket supprimé avec succès',
        'page_not_found' => 'Page non trouvée',
        '404_message' => 'Désolé, la page que vous recherchez n\'existe pas.',
        'return_home' => 'Retour à la page d\'accueil',
        // NEW
        'feature_1_title' => 'Support Rapide',
        'feature_1_text' => 'Obtenez une assistance rapide de notre équipe dédiée.',
        'feature_2_title' => 'Sécurisé',
        'feature_2_text' => 'Vos données sont en sécurité avec nos systèmes sécurisés.',
        'feature_3_title' => 'Disponible 24/7',
        'feature_3_text' => 'Nous sommes disponibles 24/7 pour vous aider avec tout problème.',
        'why_choose_us' => 'Pourquoi nous choisir?',
        'new_ticket' => 'Nouveau Ticket',
        'logout' => 'Déconnexion',
        'need_help_now' => 'Besoin d\'aide maintenant?',
        'help_description' => 'Nous sommes là pour vous aider à chaque étape du processus.',
        'create_ticket_now' => 'Créer un ticket maintenant',
        'quick_links' => 'Liens rapides',
        'resources' => 'Ressources',
        'contact' => 'Contact',
        'faq' => 'FAQ',
        'user_guide' => 'Guide de l\'utilisateur',
        'footer_description' => 'on est là pour vous aider à chaque étape du processus.',
        'home' => 'Accueil',
        'dashboard' => 'Tableau de bord',
        'profile_settings' => 'Paramètres du profil',
        'admin_panel_description' => 'Gérez les utilisateurs et les tickets',
        'user' => 'Utilisateur',
        'my_conversations_subtitle' => 'Consultez vos conversations en cours',
        'no_file_selected' => 'Aucun fichier sélectionné',
        'image_formats' => 'Formats d\'image pris en charge : JPG, PNG, GIF',
        'title_placeholder' => 'Entrez le titre du ticket',
        'message_placeholder' => 'Entrez le message du ticket',
        'edit_user_subtitle' => 'Modifiez les informations de l\'utilisateur',
        'no_tickets_title' => 'Aucun ticket trouvé',
        'confirm_delete_ticket' => 'Êtes-vous sûr de vouloir supprimer ce ticket ?',
        'confirm_delete_user' => 'Êtes-vous sûr de vouloir supprimer cet utilisateur ?',
        'contact_support' => 'Contacter le support',
        'profile_updated' => 'Profil mis à jour avec succès',
        'support_availability' => 'Disponibilité du support',
        'satisfaction_rate' => 'Taux de satisfaction',
        'avg_response_time' => 'Temps de réponse moyen',
        'no_users_found' => 'Aucun utilisateur trouvé',
        'clear_search' => 'Effacer la recherche',
        'users' => 'Utilisateurs',
        'actions' => 'Actions',
    ],

    // ENGLISH TRANSLATE
    'en' => [
        'site_title' => 'YourTicket',
        'welcome' => 'Welcome to YourTicket',
        'welcome_subtext' => 'Manage your support tickets easily',
        'create_ticket' => 'Create a ticket',
        'create_ticket_help' => 'Submit a new help request',
        'my_tickets' => 'My tickets',
        'create' => 'Create',
        'view' => 'View',
        'login_success' => 'Login successful! Welcome',
        'logout_success' => 'You have been logged out successfully',
        'admin_panel' => 'Admin panel',
        'access_admin' => 'Access the panel',
        'fill_all_fields' => 'Please fill all fields',
        'ticket_created' => 'Ticket created successfully',
        'creation_error' => 'Creation error',
        'title' => 'Title',
        'message' => 'Message',
        'create_ticket_button' => 'Create Ticket',
        'no_user_specified' => 'No user specified',
        'user_not_found' => 'User not found',
        'user_updated' => 'User updated',
        'update_error' => 'Update error',
        'settings' => 'Settings',
        'personal_settings' => 'Personal Settings',
        'save' => 'Save',
        'profile' => 'Profile',
        'login_required' => 'Login required',
        'profile_of' => 'Profile of',
        'ticket' => 'Ticket',
        'new_message' => 'New message',
        'send' => 'Send',
        'admin_actions' => 'Admin actions',
        'close_ticket' => 'Close Ticket',
        'my_conversations' => 'My Conversations',
        'view_conversation' => 'View Conversation',
        'user_management' => 'User Management',
        'ticket_management' => 'Ticket Management',
        'created_at' => 'Created At',
        'profile_details' => 'Profile Details',
        'full_name' => 'Full Name',
        'select_image' => 'Select Image',
        'light_theme' => 'Light Theme',
        'dark_theme' => 'Dark Theme',
        'YourTicket' => 'YourTicket',
        'Tickets' => 'Tickets',
        'Settings' => 'Settings',
        'Profile' => 'Profile',
        'Logout' => 'Logout',
        'Admin' => 'Admin',
        'Login' => 'Login',
        'Language' => 'Language',
        'created_on' => 'Created on',
        'all_rights_reserved' => 'All rights reserved',
        'no_users_permission' => 'No users found with the specified permissions',
        'change_profile_picture' => 'Change Profile Picture',
        'profile_picture_updated' => 'Profile picture updated successfully',
        'no_tickets' => 'No tickets found',
        'setting_updated' => 'Setting updated successfully',
        'no_tickets_found' => 'No tickets found',
        'ticket_not_found' => 'Ticket not found',
        'ticket_closed' => 'Ticket closed successfully',
        'ticket_already_closed' => 'The ticket is already closed',
        'message_sent' => 'Message sent successfully',
        'message_error' => 'Error sending message',
        'no_messages_found' => 'No messages found',
        'update' => 'Update',
        'email' => 'email',
        'username' => 'Username',
        'role' => 'Role',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'permissions' => 'Permission',
        'tickets' => 'Tickets',
        'search_users' => 'Search for a user',
        'search' => 'Search',
        'reset' => 'Reset',
        'edit_user' => 'Edit User',
        'settings_updated' => 'Settings updated successfully',
        'ticket_deleted' => 'Ticket deleted successfully',
        'page_not_found' => 'Page not found',
        '404_message' => 'Sorry, the page you are looking for does not exist.',
        'return_home' => 'Return to home page',
        //NEW
        'feature_1_title' => 'Fast Support',
        'feature_1_text' => 'Get quick support from our dedicated team.',
        'feature_2_title' => 'Secure',
        'feature_2_text' => 'Your data is safe with our secure systems.',
        'feature_3_title' => '24/7 Availability',
        'feature_3_text' => 'We are available 24/7 to help you with any issues.',
        'why_choose_us' => 'Why Choose Us?',
        'new_ticket' => 'New Ticket',
        'logout' => 'Logout',
        'need_help_now' => 'Need help now?',
        'help_description' => 'We are here to assist you every step of the way.',
        'create_ticket_now' => 'Create a ticket now',
        'quick_links' => 'Quick Links',
        'resources' => 'Resources',
        'contact' => 'Contact',
        'faq' => 'FAQ',
        'user_guide' => 'User Guide',
        'footer_description' => 'We are here to assist you every step of the way.',
        'home' => 'Home',
        'dashboard' => 'Dashboard',
        'profile_settings' => 'Profile Settings',
        'admin_panel_description' => 'Manage users and tickets',
        'user' => 'User',
        'my_conversations_subtitle' => 'Check your ongoing conversations',
        'no_file_selected' => 'No file selected',
        'image_formats' => 'Supported image formats: JPG, PNG, GIF',
        'title_placeholder' => 'Enter ticket title',
        'message_placeholder' => 'Enter ticket message',
        'edit_user_subtitle' => 'Edit user information',
        'no_tickets_title' => 'No tickets found',
        'confirm_delete_ticket' => 'Are you sure you want to delete this ticket?',
        'confirm_delete_user' => 'Are you sure you want to delete this user?',
        'contact_support' => 'Contact Support',
        'profile_updated' => 'Profile updated successfully',
        'support_availability' => 'Support Availability',
        'satisfaction_rate' => 'Satisfaction Rate',
        'avg_response_time' => 'Average Response Time',
        'no_users_found' => 'No users found',
        'clear_search' => 'Clear Search',
        'users' => 'Users',
        'actions' => 'Actions',
    ],

    // DUTCH TRANSLATE
    'nl' => [
        'site_title' => 'YourTicket',
        'welcome' => 'Welkom bij YourTicket',
        'welcome_subtext' => 'Beheer uw supporttickets eenvoudig',
        'create_ticket' => 'Maak een ticket',
        'create_ticket_help' => 'Dien een nieuw hulpverzoek in',
        'my_tickets' => 'Mijn tickets',
        'create' => 'Aanmaken',
        'view' => 'Bekijken',
        'login_success' => 'Succesvol ingelogd! Welkom',
        'logout_success' => 'U bent succesvol uitgelogd',
        'admin_panel' => 'Beheerderspaneel',
        'access_admin' => 'Toegang tot het paneel',
        'fill_all_fields' => 'Vul alle velden in',
        'ticket_created' => 'Ticket succesvol aangemaakt',
        'creation_error' => 'Fout bij aanmaken',
        'title' => 'Titel',
        'message' => 'Bericht',
        'create_ticket_button' => 'Maak ticket',
        'no_user_specified' => 'Geen gebruiker opgegeven',
        'user_not_found' => 'Gebruiker niet gevonden',
        'user_updated' => 'Gebruiker bijgewerkt',
        'update_error' => 'Fout bij bijwerken',
        'settings' => 'Instellingen',
        'personal_settings' => 'Persoonlijke instellingen',
        'save' => 'Opslaan',
        'profile' => 'Profiel',
        'login_required' => 'Inloggen vereist',
        'profile_of' => 'Profiel van',
        'ticket' => 'Ticket',
        'new_message' => 'Nieuw bericht',
        'send' => 'Verzenden',
        'admin_actions' => 'Beheerdersacties',
        'close_ticket' => 'Sluit ticket',
        'light_theme' => 'Licht thema',
        'dark_theme' => 'Donker thema',
        'full_name' => 'Volledige naam',
        'select_image' => 'Selecteer een afbeelding',
        'my_conversations' => 'Mijn gesprekken',
        'view_conversation' => 'Bekijk gesprek',
        'user_management' => 'Gebruikersbeheer',
        'ticket_management' => 'Ticketbeheer',
        'created_at' => 'Aangemaakt op',
        'profile_details' => 'Profielgegevens',
        'language' => 'Taal',
        'settings_updated' => 'Instellingen succesvol bijgewerkt',
        'theme' => 'Thema',
        'YourTicket' => 'YourTicket',
        'Tickets' => 'Tickets',
        'Settings' => 'Instellingen',
        'Profile' => 'Profiel',
        'Logout' => 'Uitloggen',
        'Admin' => 'Beheerder',
        'Login' => 'Inloggen',
        'Language' => 'Taal',
        'created_on' => 'Aangemaakt op',
        'all_rights_reserved' => 'Alle rechten voorbehouden',
        'no_users_permission' => 'Geen gebruikers gevonden met de opgegeven rechten',
        'change_profile_picture' => 'Profielfoto wijzigen',
        'profile_picture_updated' => 'Profielfoto succesvol bijgewerkt',
        'no_tickets' => 'Geen tickets gevonden',
        'setting_updated' => 'Instelling succesvol bijgewerkt',
        'no_tickets_found' => 'Geen tickets gevonden',
        'ticket_not_found' => 'Ticket niet gevonden',
        'ticket_closed' => 'Ticket succesvol gesloten',
        'ticket_already_closed' => 'Het ticket is al gesloten',
        'message_sent' => 'Bericht succesvol verzonden',
        'message_error' => 'Fout bij verzenden van bericht',
        'no_messages_found' => 'Geen berichten gevonden',
        'update' => 'Bijwerken',
        'email' => 'email',
        'username' => 'Gebruikersnaam',
        'role' => 'Rol',
        'edit' => 'Bewerken',
        'delete' => 'Verwijderen',
        'permissions' => 'Recht',
        'tickets' => 'Tickets',
        'search_users' => 'Zoek een gebruiker',
        'search' => 'Zoeken',
        'reset' => 'Resetten',
        'edit_user' => 'Bewerk gebruiker',
        'ticket_deleted' => 'Ticket succesvol verwijderd',
        'page_not_found' => 'Pagina niet gevonden',
        '404_message' => 'Sorry, de pagina die u zoekt bestaat niet.',
        'return_home' => 'Terug naar de startpagina',
        // NEW
        'feature_1_title' => 'Snelle ondersteuning',
        'feature_1_text' => 'Krijg snelle ondersteuning van ons toegewijde team.',
        'feature_2_title' => 'Veilig',
        'feature_2_text' => 'Uw gegevens zijn veilig met onze beveiligde systemen.',
        'feature_3_title' => '24/7 Beschikbaarheid',
        'feature_3_text' => 'We zijn 24/7 beschikbaar om u te helpen met eventuele problemen.',
        'why_choose_us' => 'Waarom voor ons kiezen?',
        'new_ticket' => 'Nieuw ticket',
        'logout' => 'Uitloggen',
        'need_help_now' => 'Nu hulp nodig?',
        'help_description' => 'We zijn hier om u bij elke stap van het proces te helpen.',
        'create_ticket_now' => 'Maak nu een ticket aan',
        'quick_links' => 'Snelle links',
        'resources' => 'Hulpbronnen',
        'contact' => 'Contact',
        'faq' => 'FAQ',
        'user_guide' => 'Gebruikershandleiding',
        'footer_description' => 'We zijn hier om u bij elke stap van het proces te helpen.',
        'home' => 'Startpagina',
        'dashboard' => 'Dashboard',
        'profile_settings' => 'Profielinstellingen',
        'admin_panel_description' => 'Beheer gebruikers en tickets',
        'user' => 'Gebruiker',
        'my_conversations_subtitle' => 'Bekijk uw lopende gesprekken',
        'no_file_selected' => 'Geen bestand geselecteerd',
        'image_formats' => 'Ondersteunde afbeeldingsformaten: JPG, PNG, GIF',
        'title_placeholder' => 'Voer de titel van het ticket in',
        'message_placeholder' => 'Voer het bericht van het ticket in',
        'edit_user_subtitle' => 'Bewerk gebruikersinformatie',
        'no_tickets_title' => 'Geen tickets gevonden',
        'confirm_delete_ticket' => 'Weet u zeker dat u dit ticket wilt verwijderen?',
        'comfirm_delete_user' => 'Weet u zeker dat u deze gebruiker wilt verwijderen?',
        'contact_support' => 'Neem contact op met de ondersteuning',
        'profile_updated' => 'Profiel succesvol bijgewerkt',
        'support_availability' => 'Beschikbaarheid van ondersteuning',
        'satisfaction_rate' => 'Tevredenheidspercentage',
        'avg_response_time' => 'Gemiddelde responstijd',
        'no_users_found' => 'Geen gebruikers gevonden',
        'clear_search' => 'Zoekopdracht wissen',
        'users' => 'Gebruikers',
        'actions' => 'Acties',
    ],

    // MANDARIN TRANSLATE
    'zh' => [
        'site_title' => 'YourTicket',
        'welcome' => '欢迎来到 YourTicket',
        'welcome_subtext' => '轻松管理您的支持票',
        'create_ticket' => '创建工单',
        'create_ticket_help' => '提交新的帮助请求',
        'my_tickets' => '我的工单',
        'create' => '创建',
        'view' => '查看',
        'login_success' => '登录成功！欢迎',
        'logout_success' => '您已成功登出',
        'admin_panel' => '管理面板',
        'access_admin' => '访问面板',
        'fill_all_fields' => '请填写所有字段',
        'ticket_created' => '工单创建成功',
        'creation_error' => '创建错误',
        'title' => '标题',
        'message' => '消息',
        'create_ticket_button' => '创建工单',
        'no_user_specified' => '未指定用户',
        'user_not_found' => '未找到用户',
        'user_updated' => '用户已更新',
        'update_error' => '更新错误',
        'settings' => '设置',
        'personal_settings' => '个人设置',
        'save' => '保存',
        'profile' => '个人资料',
        'login_required' => '需要登录',
        'profile_of' => '的个人资料',
        'ticket' => '工单',
        'new_message' => '新消息',
        'send' => '发送',
        'admin_actions' => '管理操作',
        'close_ticket' => '关闭工单',
        'light_theme' => '浅色主题',
        'dark_theme' => '深色主题',
        'full_name' => '全名',
        'select_image' => '选择图片',
        'my_conversations' => '我的对话',
        'view_conversation' => '查看对话',
        'user_management' => '用户管理',
        'ticket_management' => '工单管理',
        'created_at' => '创建于',
        'profile_details' => '个人资料详情',
        'language' => '语言',
        'settings_updated' => '设置已成功更新',
        'theme' => '主题',
        'YourTicket' => 'YourTicket',
        'Tickets' => '工单',
        'Settings' => '设置',
        'Profile' => '个人资料',
        'Logout' => '登出',
        'Admin' => '管理员',
        'Login' => '登录',
        'Language' => '语言',
        'created_on' => '创建于',
        'all_rights_reserved' => '版权所有',
        'no_users_permission' => '未找到具有指定权限的用户',
        'change_profile_picture' => '更改个人资料图片',
        'profile_picture_updated' => '个人资料图片已成功更新',
        'no_tickets' => '未找到工单',
        'setting_updated' => '设置已成功更新',
        'no_tickets_found' => '未找到工单',
        'ticket_not_found' => '未找到工单',
        'ticket_closed' => '工单已成功关闭',
        'ticket_already_closed' => '工单已关闭',
        'message_sent' => '消息已成功发送',
        'message_error' => '发送消息时出错',
        'no_messages_found' => '未找到消息',
        'update' => '更新',
        'email' => '电子邮件',
        'username' => '用户名',
        'role' => '角色',
        'edit' => '编辑',
        'delete' => '删除',
        'permissions' => '权限',
        'tickets' => '工单',
        'search_users' => '搜索用户',
        'search' => '搜索',
        'reset' => '重置',
        'edit_user' => '编辑用户',
        'ticket_deleted' => '工单已成功删除',
        'page_not_found' => '页面未找到',
        '404_message' => '抱歉，您要找的页面不存在。',
        'return_home' => '返回主页',
        // NEW
        'feature_1_title' => '快速支持',
        'feature_1_text' => '从我们专门的团队获得快速支持。',
        'feature_2_title' => '安全',
        'feature_2_text' => '您的数据在我们的安全系统中是安全的。',
        'feature_3_title' => '24/7 可用性',
        'feature_3_text' => '我们全天候 24/7 提供帮助。',
        'why_choose_us' => '为什么选择我们？',
        'new_ticket' => '新工单',
        'logout' => '登出',
        'need_help_now' => '现在需要帮助？',
        'help_description' => '我们在每一步都在这里帮助您。',
        'create_ticket_now' => '立即创建工单',
        'quick_links' => '快速链接',
        'resources' => '资源',
        'contact' => '联系',
        'faq' => '常见问题解答',
        'user_guide' => '用户指南',
        'footer_description' => '我们在每一步都在这里帮助您。',
        'home' => '主页',
        'dashboard' => '仪表板',
        'profile_settings' => '个人资料设置',
        'admin_panel_description' => '管理用户和工单',
        'user' => '用户',
        'my_conversations_subtitle' => '查看您正在进行的对话',
        'no_file_selected' => '未选择文件',
        'image_formats' => '支持的图像格式：JPG、PNG、GIF',
        'title_placeholder' => '输入工单标题',
        'message_placeholder' => '输入工单消息',
        'edit_user_subtitle' => '编辑用户信息',
        'no_tickets_title' => '未找到工单',
        'confirm_delete_ticket' => '您确定要删除此工单吗？',
        'confirm_delete_user' => '您确定要删除此用户吗？',
        'contact_support' => '联系支持',
        'profile_updated' => '个人资料已成功更新',
        'support_availability' => '支持可用性',
        'satisfaction_rate' => '满意度',
        'avg_response_time' => '平均响应时间',
        'no_users_found' => '未找到用户',
        'clear_search' => '清除搜索',
        'users' => '用户',
        'actions' => '操作',
    ],
    // PUNJABI TRANSLATE
    'pa' => [
        'site_title' => 'YourTicket',
        'welcome' => 'YourTicket ਤੇ ਤੁਹਾਡਾ ਸਵਾਗਤ ਹੈ',
        'welcome_subtext' => 'ਆਸਾਨੀ ਨਾਲ ਆਪਣੇ ਸਹਾਇਤਾ ਟਿਕਟਾਂ ਦਾ ਪ੍ਰਬੰਧ ਕਰੋ',
        'create_ticket' => 'ਟਿਕਟ ਬਣਾਓ',
        'create_ticket_help' => 'ਨਵਾਂ ਸਹਾਇਤਾ ਬੇਨਤੀ ਜਮ੍ਹਾਂ ਕਰੋ',
        'my_tickets' => 'ਮੇਰੇ ਟਿਕਟ',
        'create' => 'ਬਣਾਉਣਾ',
        'view' => 'ਵੇਖੋ',
        'login_success' => 'ਲੌਗਿਨ ਸਫਲ! ਸੁਆਗਤ ਹੈ',
        'logout_success' => 'ਤੁਸੀਂ ਸਫਲਤਾਪੂਰਕ ਲੌਗਆਉਟ ਹੋ ਗਏ ਹੋ',
        'admin_panel' => 'ਐਡਮਿਨ ਪੈਨਲ',
        'access_admin' => 'ਪੈਨਲ ਤੱਕ ਪਹੁੰਚੋ',
        'fill_all_fields' => 'ਕਿਰਪਾ ਕਰਕੇ ਸਾਰੇ ਖੇਤਰ ਭਰੋ',
        'ticket_created' => 'ਟਿਕਟ ਸਫਲਤਾਪੂਰਕ ਬਣਾਇਆ ਗਿਆ',
        'creation_error' => 'ਬਣਾਉਣ ਵਿੱਚ ਗਲਤੀ',
        'title' => 'ਸਿਰਲੇਖ',
        'message' => 'ਸੁਨੇਹਾ',
        'create_ticket_button' => 'ਟਿਕਟ ਬਣਾਓ',
        'no_user_specified' => 'ਕੋਈ ਉਪਭੋਗਤਾ ਨਿਰਧਾਰਤ ਨਹੀਂ',
        'user_not_found' => 'ਉਪਭੋਗਤਾ ਨਹੀਂ ਮਿਲਿਆ',
        'user_updated' => 'ਉਪਭੋਗਤਾ ਅੱਪਡੇਟ ਕੀਤਾ ਗਿਆ',
        'update_error' => 'ਅੱਪਡੇਟ ਵਿੱਚ ਗਲਤੀ',
        'settings' => 'ਸੈਟਿੰਗਾਂ',
        'personal_settings' => 'ਨਿੱਜੀ ਸੈਟਿੰਗਾਂ',
        'save' => 'ਸੁਰੱਖਿਅਤ ਕਰੋ',
        'profile' => 'ਪ੍ਰੋਫਾਈਲ',
        'login_required' => 'ਲੌਗਿਨ ਦੀ ਲੋੜ ਹੈ',
        'profile_of' => 'ਦਾ ਪ੍ਰੋਫਾਈਲ',
        'ticket' => 'ਟਿਕਟ',
        'new_message' => 'ਨਵਾਂ ਸੁਨੇਹਾ',
        'send' => 'ਭੇਜੋ',
        'admin_actions' => 'ਐਡਮਿਨ ਕਾਰਵਾਈਆਂ',
        'close_ticket' => 'ਟਿਕਟ ਬੰਦ ਕਰੋ',
        'light_theme' => 'ਹਲਕਾ ਥੀਮ',
        'dark_theme' => 'ਗੂੜ੍ਹਾ ਥੀਮ',
        'full_name' => 'ਪੂਰਾ ਨਾਮ',
        'select_image' => 'ਤਸਵੀਰ ਚੁਣੋ',
        'my_conversations' => 'ਮੇਰੀ ਗੱਲਬਾਤਾਂ',
        'view_conversation' => 'ਗੱਲਬਾਤ ਵੇਖੋ',
        'user_management' => 'ਉਪਭੋਗਤਾ ਪ੍ਰਬੰਧਨ',
        'ticket_management' => 'ਟਿਕਟ ਪ੍ਰਬੰਧਨ',
        'created_at' => 'ਤੇ ਬਣਾਇਆ ਗਿਆ',
        'profile_details' => 'ਪ੍ਰੋਫਾਈਲ ਵੇਰਵੇ',
        'language' => 'ਭਾਸ਼ਾ',
        'settings_updated' => 'ਸੈਟਿੰਗਾਂ ਸਫਲਤਾਪੂਰ�� ਅੱਪਡੇਟ ਕੀਤੀਆਂ ਗਈਆਂ',
        'theme' => 'ਥੀਮ',
        'YourTicket' => 'YourTicket',
        'Tickets' => 'ਟਿਕਟ',
        'Settings' => 'ਸੈਟਿੰਗਾਂ',
        'Profile' => 'ਪ੍ਰੋਫਾਈਲ',
        'Logout' => 'ਲੌਗਆਉਟ',
        'Admin' => 'ਐਡਮਿਨ',
        'Login' => 'ਲੌਗਿਨ',
        'Language' => 'ਭਾਸ਼ਾ',
        'created_on' => 'ਤੇ ਬਣਾਇਆ ਗਿਆ',
        'all_rights_reserved' => 'ਸਾਰੇ ਹੱਕ ਰਾਖਵੇਂ ਹਨ',
        'no_users_permission' => 'ਨਿਰਧਾਰਤ ਅਧਿਕਾਰਾਂ ਨਾਲ ਕੋਈ ਉਪਭੋਗਤਾ ਨਹੀਂ ਮਿਲਿਆ',
        'change_profile_picture' => 'ਪ੍ਰੋਫਾਈਲ ਤਸਵੀਰ ਬਦਲੋ',
        'profile_picture_updated' => 'ਪ੍ਰੋਫਾਈਲ ਤਸਵੀਰ ਸਫਲਤਾਪੂਰਕ ਅੱਪਡੇਟ ਕੀਤੀ ਗਈ',
        'no_tickets' => 'ਕੋਈ ਟਿਕਟ ਨਹੀਂ ਮਿਲਿਆ',
        'setting_updated' => 'ਸੈਟਿੰਗ ਸਫਲਤਾਪੂਰਕ ਅੱਪਡੇਟ ਕੀਤੀ ਗਈ',
        'no_tickets_found' => 'ਕੋਈ ਟਿਕਟ ਨਹੀਂ ਮਿਲਿਆ',
        'ticket_not_found' => 'ਟਿਕਟ ਨਹੀਂ ਮਿਲਿਆ',
        'ticket_closed' => 'ਟਿਕਟ ਸਫਲਤਾਪੂਰਕ ਬੰਦ ਕੀਤਾ ਗਿਆ',
        'ticket_already_closed' => 'ਟਿਕਟ ਪਹਿਲਾਂ ਹੀ ਬੰਦ ਕੀਤਾ ਗਿਆ ਹੈ',
        'message_sent' => 'ਸੁਨੇਹਾ ਸਫਲਤਾਪੂਰਕ ਭੇਜਿਆ ਗਿਆ',
        'message_error' => 'ਸੁਨੇਹਾ ਭੇਜਣ ਵਿੱਚ ਗਲਤੀ',
        'no_messages_found' => 'ਕੋਈ ਸੁਨੇਹਾ ਨਹੀਂ ਮਿਲਿਆ',
        'update' => 'ਅੱਪਡੇਟ ਕਰੋ',
        'email' => 'ਈਮੇਲ',
        'username' => 'ਉਪਭੋਗਤਾ ਨਾਮ',
        'role' => 'ਭੂਮਿਕਾ',
        'edit' => 'ਸੋਧੋ',
        'delete' => 'ਹਟਾਓ',
        'permissions' => 'ਅਧਿਕਾਰ',
        'tickets' => 'ਟਿਕਟ',
        'search_users' => 'ਉਪਭੋਗਤਾ ਖੋਜੋ',
        'search' => 'ਖੋਜੋ',
        'reset' => 'ਰੀਸੈਟ ਕਰੋ',
        'edit_user' => 'ਉਪਭੋਗਤਾ ਸੋਧੋ',
        'ticket_deleted' => 'ਟਿਕਟ ਸਫਲਤਾਪੂਰਕ ਹਟਾਇਆ ਗਿਆ',
        'page_not_found' => 'ਪੰਨਾ ਨਹੀਂ ਮਿਲਿਆ',
        '404_message' => 'ਮਾਫ ਕਰਨਾ, ਤੁਸੀਂ ਜੋ ਪੰਨਾ ਲੱਭ ਰਹੇ ਹੋ ਉਹ ਮੌਜੂਦ ਨਹੀਂ ਹੈ।',
        'return_home' => 'ਮੁੱਖ ਪੰਨੇ ਤੇ ਵਾਪਸ ਜਾਓ',
        // NEW
        'feature_1_title' => 'ਤੁਰੰਤ ਸਹਾਇਤਾ',
        'feature_1_text' => 'ਸਾਡੇ ਸਮਰਪਿਤ ਟੀਮ ਤੋਂ ਤੁਰੰਤ ਸਹਾਇਤਾ ਪ੍ਰਾਪਤ ਕਰੋ।',
        'feature_2_title' => 'ਸੁਰੱਖਿਅਤ',
        'feature_2_text' => 'ਸਾਡੇ ਸੁਰੱਖਿਅਤ ਸਿਸਟਮਾਂ ਨਾਲ ਤੁਹਾਡੇ ਡੇਟਾ ਦੀ ਸੁਰੱਖਿਆ ਹੈ।',
        'feature_3_title' => '24/7 ਉਪਲਬਧਤਾ',
        'feature_3_text' => 'ਕਿਸੇ ਵੀ ਸਮੱਸਿਆ ਲਈ ਸਾਡੀ ਸਹਾਇਤਾ 24/7 ਉਪਲਬਧ ਹੈ।',
        'why_choose_us' => 'ਸਾਨੂੰ ਕਿਉਂ ਚੁਣੋ?',
        'new_ticket' => 'ਨਵਾਂ ਟਿਕਟ',
        'logout' => 'ਲੌਗਆਉਟ',
        'need_help_now' => 'ਹੁਣ ਸਹਾਇਤਾ ਦੀ ਲੋੜ ਹੈ?',
        'help_description' => 'ਅਸੀਂ ਹਰ ਪਦਰ ਤੇ ਤੁਹਾਡੀ ਮਦਦ ਕਰਨ ਲਈ ਇੱਥੇ ਹਾਂ।',
        'create_ticket_now' => 'ਹੁਣ ਟਿਕਟ ਬਣਾਓ',
        'quick_links' => 'ਤੁਰੰਤ ਲਿੰਕ',
        'resources' => 'ਸਾਧਨ',
        'contact' => 'ਸੰਪਰਕ',
        'faq' => 'ਅਕਸਰ ਪੁੱਛੇ ਜਾਣ ਵਾਲੇ ਸਵਾਲ',
        'user_guide' => 'ਉਪਭੋਗਤਾ ਮਾਰਗਦਰਸ਼ਕ',
        'footer_description' => 'ਅਸੀਂ ਹਰ ਪਦਰ ਤੇ ਤੁਹਾਡੀ ਮਦਦ ਕਰਨ ਲਈ ਇੱਥੇ ਹਾਂ।',
        'home' => 'ਮੁੱਖ ਪੰਨਾ',
        'dashboard' => 'ਡੈਸ਼ਬੋਰਡ',
        'profile_settings' => 'ਪ੍ਰੋਫਾਈਲ ਸੈਟਿੰਗਾਂ',
        'admin_panel_description' => 'ਉਪਭੋਗਤਾਵਾਂ ਅਤੇ ਟਿਕਟਾਂ ਦਾ ਪ੍ਰਬੰਧਨ ਕਰੋ',
        'user' => 'ਉਪਭੋਗਤਾ',
        'my_conversations_subtitle' => 'ਆਪਣੇ ਚੱਲ ਰਹੇ ਗੱਲਬਾਤਾਂ ਦੀ ਜਾਂਚ ਕਰੋ',
        'no_file_selected' => 'ਕੋਈ ਫਾਈਲ ਚੁਣੀ ਨਹੀਂ ਗਈ',
        'image_formats' => 'ਸਹਾਇਤਾਪ੍ਰਾਪਤ ਚਿੱਤਰ ਫਾਰਮੈਟ: JPG, PNG, GIF',
        'title_placeholder' => 'ਟਿਕਟ ਦਾ ਸਿਰਲੇਖ ਦਰਜ ਕਰੋ',
        'message_placeholder' => 'ਟਿਕਟ ਦਾ ਸੁਨੇਹਾ ਦਰਜ ਕਰੋ',
        'edit_user_subtitle' => 'ਉਪਭੋਗਤਾ ਜਾਣਕਾਰੀ ਸੋਧੋ',
        'no_tickets_title' => 'ਕੋਈ ਟਿਕਟ ਨਹੀਂ ਮਿਲਿਆ',
        'confirm_delete_ticket' => 'ਕੀ ਤੁਸੀਂ ਯਕੀਨੀ ਹੋ ਕਿ ਤੁਸੀਂ ਇਸ ਟਿਕਟ ਨੂੰ ਹਟਾਉਣਾ ਚਾਹੁੰਦੇ ਹੋ?',
        'confirm_delete_user' => 'ਕੀ ਤੁਸੀਂ ਯਕੀਨੀ ਹੋ ਕਿ ਤੁਸੀਂ ਇਸ ਉਪਭੋਗਤਾ ਨੂੰ ਹਟਾਉਣਾ ਚਾਹੁੰਦੇ ਹੋ?',
        'contact_support' => 'ਸਹਾਇਤਾ ਨਾਲ ਸੰਪਰਕ ਕਰੋ',
        'profile_updated' => 'ਪ੍ਰੋਫਾਈਲ ਸਫਲਤਾਪੂਰਕ ਅੱਪਡੇਟ ਕੀਤਾ ਗਿਆ',
        'support_availability' => 'ਸਹਾਇਤਾ ਉਪਲਬਧਤਾ',
        'satisfaction_rate' => 'ਸੰਤੁਸ਼ਟੀ ਦਰ',
        'avg_response_time' => 'ਔਸਤ ਪ੍ਰਤੀਕਿਰਿਆ ਸਮਾਂ',
        'no_users_found' => 'ਕੋਈ ਉਪਭੋਗਤਾ ਨਹੀਂ ਮਿਲਿਆ',
        'clear_search' => 'ਖੋਜ ਸਾਫ਼ ਕਰੋ',
        'users' => 'ਉਪਭੋਗਤਾ',
        'actions' => 'ਕਾਰਵਾਈਆਂ',
    ],

    // SPANISH TRANSLATE
    'es' => [
        'site_title' => 'YourTicket',
        'welcome' => 'Bienvenido a YourTicket',
        'welcome_subtext' => 'Administra tus tickets de soporte fácilmente',
        'create_ticket' => 'Crear un ticket',
        'create_ticket_help' => 'Enviar una nueva solicitud de ayuda',
        'my_tickets' => 'Mis tickets',
        'create' => 'Crear',
        'view' => 'Ver',
        'login_success' => '¡Inicio de sesión exitoso! Bienvenido',
        'logout_success' => 'Has cerrado sesión con éxito',
        'admin_panel' => 'Panel de administración',
        'access_admin' => 'Acceder al panel',
        'fill_all_fields' => 'Por favor completa todos los campos',
        'ticket_created' => 'Ticket creado con éxito',
        'creation_error' => 'Error de creación',
        'title' => 'Título',
        'message' => 'Mensaje',
        'create_ticket_button' => 'Crear ticket',
        'no_user_specified' => 'No se especificó ningún usuario',
        'user_not_found' => 'Usuario no encontrado',
        'user_updated' => 'Usuario actualizado',
        'update_error' => 'Error de actualización',
        'settings' => 'Configuraciones',
        'personal_settings' => 'Configuraciones personales',
        'save' => 'Guardar',
        'profile' => 'Perfil',
        'login_required' => 'Inicio de sesión requerido',
        'profile_of' => 'Perfil de',
        'ticket' => 'Ticket',
        'new_message' => 'Nuevo mensaje',
        'send' => 'Enviar',
        'admin_actions' => 'Acciones de administrador',
        'close_ticket' => 'Cerrar ticket',
        'light_theme' => 'Tema claro',
        'dark_theme' => 'Tema oscuro',
        'full_name' => 'Nombre completo',
        'select_image' => 'Seleccionar imagen',
        'my_conversations' => 'Mis conversaciones',
        'view_conversation' => 'Ver conversación',
        'user_management' => 'Gestión de usuarios',
        'ticket_management' => 'Gestión de tickets',
        'created_at' => 'Creado en',
        'profile_details' => 'Detalles del perfil',
        'language' => 'Idioma',
        'settings_updated' => 'Configuración actualizada con éxito',
        'theme' => 'Tema',
        'YourTicket' => 'YourTicket',
        'Tickets' => 'Tickets',
        'Settings' => 'Configuraciones',
        'Profile' => 'Perfil',
        'Logout' => 'Cerrar sesión',
        'Admin' => 'Administrador',
        'Login' => 'Iniciar sesión',
        'Language' => 'Idioma',
        'created_on' => 'Creado en',
        'all_rights_reserved' => 'Todos los derechos reservados',
        'no_users_permission' => 'No se encontraron usuarios con los permisos especificados',
        'change_profile_picture' => 'Cambiar foto de perfil',
        'profile_picture_updated' => 'Foto de perfil actualizada con éxito',
        'no_tickets' => 'No se encontraron tickets',
        'setting_updated' => 'Configuración actualizada con éxito',
        'no_tickets_found' => 'No se encontraron tickets',
        'ticket_not_found' => 'Ticket no encontrado',
        'ticket_closed' => 'Ticket cerrado con éxito',
        'ticket_already_closed' => 'El ticket ya está cerrado',
        'message_sent' => 'Mensaje enviado con éxito',
        'message_error' => 'Error al enviar el mensaje',
        'no_messages_found' => 'No se encontraron mensajes',
        'update' => 'Actualizar',
        'email' => 'correo electrónico',
        'username' => 'Nombre de usuario',
        'role' => 'Rol',
        'edit' => 'Editar',
        'delete' => 'Eliminar',
        'permissions' => 'Permisos',
        'tickets' => 'Tickets',
        'search_users' => 'Buscar un usuario',
        'search' => 'Buscar',
        'reset' => 'Restablecer',
        'edit_user' => 'Editar usuario',
        'ticket_deleted' => 'Ticket eliminado con éxito',
        'page_not_found' => 'Página no encontrada',
        '404_message' => 'Lo sentimos, la página que buscas no existe.',
        'return_home' => 'Volver a la página de inicio',
        // NEW
        'feature_1_title' => 'Soporte rápido',
        'feature_1_text' => 'Obtén soporte rápido de nuestro equipo dedicado.',
        'feature_2_title' => 'Seguro',
        'feature_2_text' => 'Tus datos están seguros con nuestros sistemas seguros.',
        'feature_3_title' => 'Disponibilidad 24/7',
        'feature_3_text' => 'Estamos disponibles 24/7 para ayudarte con cualquier problema.',
        'why_choose_us' => '¿Por qué elegirnos?',
        'new_ticket' => 'Nuevo ticket',
        'logout' => 'Cerrar sesión',
        'need_help_now' => '¿Necesitas ayuda ahora?',
        'help_description' => 'Estamos aquí para ayudarte en cada paso del proceso.',
        'create_ticket_now' => 'Crea un ticket ahora',
        'quick_links' => 'Enlaces rápidos',
        'resources' => 'Recursos',
        'contact' => 'Contacto',
        'faq' => 'Preguntas frecuentes',
        'user_guide' => 'Guía del usuario',
        'footer_description' => 'Estamos aquí para ayudarte en cada paso del proceso.',
        'home' => 'Inicio',
        'dashboard' => 'Tablero',
        'profile_settings' => 'Configuraciones del perfil',
        'admin_panel_description' => 'Gestiona usuarios y tickets',
        'user' => 'Usuario',
        'my_conversations_subtitle' => 'Ver tus conversaciones en curso',
        'no_file_selected' => 'Ningún archivo seleccionado',
        'image_formats' => 'Formatos de imagen admitidos: JPG, PNG, GIF',
        'title_placeholder' => 'Ingresa el título del ticket',
        'message_placeholder' => 'Ingresa el mensaje del ticket',
        'edit_user_subtitle' => 'Editar información del usuario',
        'no_tickets_title' => 'No se encontraron tickets',
        'confirm_delete_ticket' => '¿Estás seguro de que deseas eliminar este ticket?',
        'confirm_delete_user' => '¿Estás seguro de que deseas eliminar este usuario?',
        'contact_support' => 'Contactar soporte',
        'profile_updated' => 'Perfil actualizado con éxito',
        'support_availability' => 'Disponibilidad de soporte',
        'satisfaction_rate' => 'Tasa de satisfacción',
        'avg_response_time' => 'Tiempo promedio de respuesta',
        'no_users_found' => 'No se encontraron usuarios',
        'clear_search' => 'Limpiar búsqueda',
        'users' => 'Usuarios',
        'actions' => 'Acciones',
    ],
];

// Fonction de traduction mise à jour pour utiliser la langue globale par défaut
function t($key, $translations, $lang = 'en')
{
    // Correction : évitez la confusion avec le global $lang
    $currentLang = $lang;
    if (!isset($translations[$currentLang])) {
        $currentLang = 'en';
    }
    return $translations[$currentLang][$key] ?? $key;
}
