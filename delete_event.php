<?php
include 'includes/db.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['id'])) {
        echo "ID d'événement non fourni.";
        exit();
    }

    $event_id = $_POST['id'];

    // Vérifier que l'utilisateur est bien le créateur de l'événement
    $sql = "SELECT * FROM events WHERE id = ? AND organizer_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$event_id, $user_id]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        echo "Vous n'avez pas l'autorisation de supprimer cet événement.";
        exit();
    }

    // Supprimer les inscriptions associées
    $sql = "DELETE FROM event_registrations WHERE event_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$event_id]);

    // Supprimer l'événement
    $sql = "DELETE FROM events WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$event_id]);

    header("Location: index.php");
    exit();
}
?>
