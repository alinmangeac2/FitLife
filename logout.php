<?php
session_start();
$_SESSION = []; // Clear session data
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Ensure session cookies are also deleted
setcookie(session_name(), '', time() - 3600, '/');

// Redirect to login page
header("Location: login.php");
exit();
?>
