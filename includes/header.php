<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/login.css">
    <title>Health Tracker</title>
</head>
<body>
    <nav>
        <a href="index.php" class="home-button">Home</a>
        <?php if (isset($_SESSION['username'])): ?>
            <a href="activity.php">Activity Tracker</a>
            <a href="food-journal.php">Food Journal</a>
            <a href="water-reports.php">Water Reports</a>
            <a href="goals.php">Goals</a>
            <a href="recipes.php">Healthy Recipes</a>
            <a href="stress-evaluation.php">Stress Evaluation</a>
            <div class="auth-buttons">
                <a href="logout.php" class="logout-button">Logout</a>
            </div>
        <?php else: ?>
            <div class="auth-buttons">
                <a href="login.php" class="login-button">Login</a>
                <a href="register.php" class="sign-up-button">Sign Up</a>
            </div>
        <?php endif; ?>
    </nav>
</body>
</html>