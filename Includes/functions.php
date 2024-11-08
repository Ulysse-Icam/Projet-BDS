<?php
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function redirectToLogin() {
    if (!isLoggedIn()) {
        header('Location: /login.php');
        exit();
    }
}
?>
