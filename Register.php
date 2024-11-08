<?php
session_start();  // Démarre la session pour avoir accès à $_SESSION

include 'includes/db.php'; // Inclusion de la base de données
include 'includes/header.php'; // Inclusion du header si nécessaire

// Si l'utilisateur est déjà connecté, redirigez-le vers la page d'accueil (index.php)
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Traitement du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Vérification des mots de passe
    if ($password !== $confirm_password) {
        echo "Les mots de passe ne correspondent pas.";
        exit();
    }

    // Vérifier si l'email est déjà utilisé
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $existing_user = $stmt->fetch();

    if ($existing_user) {
        echo "Cet email est déjà utilisé.";
        exit();
    }

    // Hash du mot de passe
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insérer l'utilisateur dans la base de données
    $sql = "INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$first_name, $last_name, $email, $hashed_password]);

    // Rediriger vers la page de connexion après l'inscription réussie
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un compte</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <h1>Créer un compte</h1>
    <form method="POST">
        <label>Prénom :</label>
        <input type="text" name="first_name" required>

        <label>Nom :</label>
        <input type="text" name="last_name" required>

        <label>Email :</label>
        <input type="email" name="email" required>

        <label>Mot de passe :</label>
        <input type="password" name="password" required>

        <label>Confirmer le mot de passe :</label>
        <input type="password" name="confirm_password" required>

        <button type="submit">S'inscrire</button>
    </form>
    <p>Vous avez déjà un compte ? <a href="login.php">Se connecter</a></p>
</body>
</html>
