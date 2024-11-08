<?php
include 'includes/db.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Vérification de l'ID d'événement envoyé via POST
if (!isset($_POST['event_id'])) {
    echo "ID d'événement non fourni.";
    exit();
}

$event_id = $_POST['event_id'];

// Vérifier si l'utilisateur est déjà inscrit
$sql = "SELECT * FROM event_registrations WHERE user_id = ? AND event_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $event_id]);
$registration = $stmt->fetch(PDO::FETCH_ASSOC);

if ($registration) {
    echo "Vous êtes déjà inscrit à cet événement.";
    exit();
}

// Inscrire l'utilisateur à l'événement
$sql = "INSERT INTO event_registrations (user_id, event_id) VALUES (?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $event_id]);

header("Location: index.php");
exit();
?>
