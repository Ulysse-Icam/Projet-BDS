<?php
include 'includes/db.php';
include 'includes/header.php';  // Inclusion du header

if (isset($_SESSION['user_id'])) {
    // Si l'utilisateur est déjà connecté, redirigez-le vers la page d'accueil
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Requête pour vérifier les informations de connexion
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user_id'] = $user['id'];

        
        header('Location: index.php');
        exit;  // Assurez-vous que le script s'arrête après la redirection
    } else {
        echo "Identifiants incorrects.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - BDS Icam</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <h1>Connexion</h1>
    <form method="POST">
        <label>Email :</label>
        <input type="email" name="email" required>
        <label>Mot de passe :</label>
        <input type="password" name="password" required>
        <button type="submit">Se connecter</button>
    </form>
    <a href="register.php">Créer un compte</a>
</body>
</html>
