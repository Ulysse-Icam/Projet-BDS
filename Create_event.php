<?php
include 'includes/db.php';
include 'includes/header.php';  // Inclusion du header

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $discipline = $_POST['discipline'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $max_participants = $_POST['max_participants'];
    $organizer_id = $_SESSION['user_id'];

    // Requête pour insérer un nouvel événement
    $sql = "INSERT INTO events (title, discipline, description, date, time, max_participants, organizer_id)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$title, $discipline, $description, $date, $time, $max_participants, $organizer_id])) {
        echo "Événement créé avec succès.";
    } else {
        echo "Erreur lors de la création de l'événement.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un événement - BDS Icam</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <h1>Créer un événement</h1>
    <form method="POST">
        <label>Titre :</label>
        <input type="text" name="title" required>
        <label>Discipline :</label>
        <input type="text" name="discipline" required>
        <label>Description :</label>
        <textarea name="description"></textarea>
        <label>Date :</label>
        <input type="date" name="date" required>
        <label>Heure :</label>
        <input type="time" name="time" required>
        <label>Nombre maximum de participants :</label>
        <input type="number" name="max_participants">
        <button type="submit">Créer</button>
    </form>

    <?php include 'includes/footer.php'; // Inclusion du footer ?>
</body>
</html>
