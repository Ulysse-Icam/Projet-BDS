<?php
include 'includes/db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    echo "ID d'événement non fourni.";
    exit();
}

$id = $_GET['id'];

// Récupérer les détails de l'événement
$sql = "SELECT * FROM events WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event || $event['organizer_id'] != $user_id) {
    echo "Vous n'avez pas l'autorisation de modifier cet événement.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $discipline = $_POST['discipline'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $description = $_POST['description'];
    $max_participants = $_POST['max_participants'];

    // Mise à jour de l'événement
    $sql = "UPDATE events SET title = ?, discipline = ?, date = ?, time = ?, description = ?, max_participants = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$title, $discipline, $date, $time, $description, $max_participants, $id]);

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'événement</title>
    <link rel="stylesheet" href="styles/style.css">
    <style>
        /* Style général du body */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Conteneur du formulaire de modification */
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        h1 {
            text-align: center;
            color: #003366;
            margin-bottom: 20px;
        }

        /* Formulaire et labels */
        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            color: #003366;
            margin-bottom: 5px;
        }

        input, textarea {
            padding: 12px;
            margin-bottom: 15px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        input:focus, textarea:focus {
            border-color: #f5b400;
            outline: none;
        }

        /* Style de la description */
        textarea {
            resize: vertical;
            min-height: 120px;
        }

        /* Bouton de soumission */
        .btn-submit {
            background-color: #f5b400;
            color: #003366;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .btn-submit:hover {
            background-color: #e5a300;
        }

        /* Espacement entre les groupes de champs */
        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Modifier l'événement</h1>
        <form action="" method="post">
            <div class="form-group">
                <label for="title">Titre :</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required>
            </div>

            <div class="form-group">
                <label for="discipline">Discipline :</label>
                <input type="text" id="discipline" name="discipline" value="<?php echo htmlspecialchars($event['discipline']); ?>" required>
            </div>

            <div class="form-group">
                <label for="date">Date :</label>
                <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($event['date']); ?>" required>
            </div>

            <div class="form-group">
                <label for="time">Heure :</label>
                <input type="time" id="time" name="time" value="<?php echo htmlspecialchars($event['time']); ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description :</label>
                <textarea id="description" name="description" required><?php echo htmlspecialchars($event['description']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="max_participants">Max participants :</label>
                <input type="number" id="max_participants" name="max_participants" value="<?php echo htmlspecialchars($event['max_participants']); ?>" required>
            </div>

            <button type="submit" class="btn-submit">Mettre à jour l'événement</button>
        </form>
    </div>
</body>
</html>
