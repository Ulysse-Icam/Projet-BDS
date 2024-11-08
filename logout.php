<?php
session_start(); // Démarrer la session pour y avoir accès

// Détruire toutes les données de session
session_unset();  // Efface toutes les variables de session
session_destroy(); // Détruit la session active

// Rediriger l'utilisateur vers la page d'accueil après la déconnexion
header("Location: login.php"); 
exit(); // S'assure que le script s'arrête après la redirection
?>
