<?php
include 'includes/db.php';
include 'includes/header.php';  // Inclusion du header
include 'includes/footer.php';  // Inclusion du header

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Récupérer tous les événements
$sql = "SELECT * FROM events";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>

    <main>
        <section class="event-section">
            <h1>Événements disponibles</h1>
            <div class="event-container">
                <?php foreach ($events as $event): ?>
                    <div class="event-card">
                        <h2><?php echo htmlspecialchars($event['title']); ?></h2>
                        <p class="discipline"><?php echo htmlspecialchars($event['discipline']); ?></p>
                        <p class="date"><?php echo date("d M Y", strtotime($event['date'])); ?> à <?php echo htmlspecialchars($event['time']); ?></p>
                        <p class="description"><?php echo htmlspecialchars($event['description']); ?></p>
                        <div class="buttons">
                            <form action="register_event.php" method="post">
                                <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                <button type="submit" class="btn-yellow">S'inscrire</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>


    <div class="event-container">
        <?php foreach ($events as $event): ?>
            <div class="event">
                <h2><?php echo htmlspecialchars($event['title']); ?></h2>
                <p><strong>Discipline :</strong> <?php echo htmlspecialchars($event['discipline']); ?></p>
                <p><strong>Date :</strong> <?php echo htmlspecialchars($event['date']); ?></p>
                <p><strong>Heure :</strong> <?php echo htmlspecialchars($event['time']); ?></p>
                <p><strong>Description :</strong> <?php echo htmlspecialchars($event['description']); ?></p>
                <p><strong>Nombre maximum de participants :</strong> <?php echo htmlspecialchars($event['max_participants']); ?></p>

                <!-- Afficher les participants -->
                <p><strong>Participants :</strong></p>
                <ul>
                    <?php
                    $sql = "SELECT u.last_name FROM event_registrations er JOIN users u ON er.user_id = u.id WHERE er.event_id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$event['id']]);
                    $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if ($participants) {
                        foreach ($participants as $participant) {
                            echo "<li>" . htmlspecialchars($participant['last_name']) . "</li>";
                        }
                    } else {
                        echo "<li>Aucun participant pour le moment.</li>";
                    }
                    ?>
                </ul>

                <?php if ($user_id): ?>
                    <?php
                    $sql = "SELECT * FROM event_registrations WHERE user_id = ? AND event_id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$user_id, $event['id']]);
                    $already_registered = $stmt->fetch(PDO::FETCH_ASSOC);
                    ?>

                    <?php if ($already_registered): ?>
                        <p>Vous êtes déjà inscrit à cet événement.</p>
                    <?php else: ?>
                        <form action="register_event.php" method="post">
                            <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                            <input type="submit" class="button yellow" value="S'inscrire">
                        </form>
                    <?php endif; ?>

                    <?php if ($event['organizer_id'] == $user_id): ?>
                        <form action="edit_event.php" method="get">
                            <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
                            <input type="submit" class="button yellow" value="Modifier">
                        </form>

                        <form action="delete_event.php" method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?');">
                            <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
                            <input type="submit" class="button yellow" value="Supprimer">
                        </form>
                    <?php endif; ?>
                <?php else: ?>
                    <p><a href="login.php">Connectez-vous pour vous inscrire, modifier ou supprimer les événements.</a></p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
