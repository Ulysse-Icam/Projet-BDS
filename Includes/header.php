<?php
session_start(); // Démarre la session pour gérer les utilisateurs connectés
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bureau des Sports - Icam</title>
    <link rel="stylesheet" href="/styles/style.css">
</head>
<body>
    <header class="site-header">
        <div class="logo">
            <a href="http://localhost/htdocs/Site_BDS/index.php">
                <img src="images/image1.png" alt="Logo du BDS Icam" class="header-logo">
            </a>
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="http://localhost/htdocs/Site_BDS/index.php">Accueil</a></li>
                <li><a href="http://localhost/htdocs/Site_BDS/create_event.php">Créer un événement</a></li>
                <?php if (isset($_SESSION['user_id'])) { ?>
                    <li><a href="http://localhost/htdocs/Site_BDS/logout.php">Déconnexion</a></li>
                <?php } else { ?>
                    <li><a href="http://localhost/htdocs/Site_BDS/login.php">Connexion</a></li>
                    <li><a href="http://localhost/htdocs/Site_BDS/register.php">Inscription</a></li>
                <?php } ?>
            </ul>
        </nav>
    </header>
    <main>
