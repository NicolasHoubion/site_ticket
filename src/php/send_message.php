<?php

setlocale(LC_ALL, 'en_US.UTF-8'); // Ajoute cette ligne tout en haut

session_start();
require_once './dbconn.php';
require_once './lang.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$lang = getLanguage($db, $_SESSION['id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ticketId = intval($_POST['ticket_id']);
    $message = trim($_POST['message']);
    $createdBy = $_SESSION['id'];

    // Récupérer l'email de l'utilisateur connecté
    $stmtUser = $db->prepare("SELECT mail, Username FROM Users WHERE Id = ?");
    $stmtUser->execute([$createdBy]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
    $userMail = $user['mail'] ?? 'no-reply@example.com';
    $userName = $user['Username'] ?? 'Utilisateur';

    // Récupérer le titre du ticket
    $stmtTicket = $db->prepare("SELECT Title FROM Ticket WHERE Id = ?");
    $stmtTicket->execute([$ticketId]);
    $ticketData = $stmtTicket->fetch(PDO::FETCH_ASSOC);
    $ticketTitle = isset($ticketData['Title']) ? $ticketData['Title'] : '(Titre inconnu)';

    if (!empty($message)) {
        // Insertion du message dans la base de données
        $stmt = $db->prepare("INSERT INTO Messages (Ticket_id, Message, Created_by) VALUES (?, ?, ?)");
        $insertion_reussie = $stmt->execute([$ticketId, $message, $createdBy]);

        if ($insertion_reussie) {
            $_SESSION['success_message'] = "Message envoyé avec succès.";
            unset($_SESSION['error_message']);
            header("Location: ../../ticket_view.php?id=" . intval($_POST['ticket_id']));
            exit;
        } else {
            $_SESSION['error_message'] = "Erreur lors de l'envoi du message.";
            unset($_SESSION['success_message']);
            header("Location: ../../ticket_view.php?id=" . intval($_POST['ticket_id']));
            exit;
        }
    } else {
        $_SESSION['error_message'] = t('message_error', $translations, $lang);
        header("Location: ../../ticket_view.php?id=" . $ticketId);
        exit;
    }
}
?>