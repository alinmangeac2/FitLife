<?php
session_start();
include 'config/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user-specific data if needed
$user_id = $_SESSION['user_id'];

// You can add more user-specific logic here if necessary

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css"> <!-- Global styles -->
    <link rel="stylesheet" href="css/goals.css"> <!-- Scoped styles -->
    <title>Set Goals</title>
</head>
<body class="goals-page">
    <?php include 'includes/header.php'; ?>
    <h1>Set Your Goals</h1>
    <div class="goals-container">
        <!-- Goal Form -->
        <form id="goal-form" action="save_goal.php" method="post">
            <label>Current Weight (kg):</label> <input type="number" name="current-weight" required><br>
            <label>Target Weight (kg):</label> <input type="number" name="target-weight" required><br>
            <label>Goal:</label>
            <select name="goal" required>
                <option value="lose-weight">Lose Weight</option>
                <option value="gain-muscle">Gain Muscle</option>
                <option value="maintain-weight">Maintain Weight</option>
            </select><br>
            <button type="submit">Save Goal</button>
        </form>

        <!-- Progress Bar Section -->
        <div class="progress-container">
            <h2>Goal Progress</h2>
            <div class="progress-bar">
                <div class="progress" style="width: 40%;">40%</div>
            </div>
            <p>You are 40% towards your goal of losing 10 kg.</p>
        </div>
    </div>
</body>
</html>