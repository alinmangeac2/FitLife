<?php
// Start the session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Health Tracker</title>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="content">
        <div class="header-wrapper">
            <?php if (isset($_SESSION['username'])): ?>
                <h1>Welcome to Your Health Tracker, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            <?php else: ?>
                <h1>Welcome to Your Health Tracker!</h1>
            <?php endif; ?>
        </div>
        <div class="hero">
            <p>Monitor your steps, calories, water intake, and more!</p>
        </div>
        <section class="features">
            <div class="feature">
                <h2>Track Your Activity</h2>
                <p>Keep track of your daily activities and stay fit.</p>
            </div>
            <div class="feature">
                <h2>Food Journal</h2>
                <p>Log your meals and monitor your calorie intake.</p>
            </div>
            <div class="feature">
                <h2>Water Reports</h2>
                <p>Ensure you are staying hydrated throughout the day.</p>
            </div>
            <div class="feature">
                <h2>Goals</h2>
                <p>Set and achieve your health and fitness goals.</p>
            </div>
            <div class="feature">
                <h2>Healthy Recipes</h2>
                <p>Discover delicious and healthy recipes.</p>
            </div>
            <div class="feature">
                <h2>Stress Evaluation</h2>
                <p>Evaluate and manage your stress levels.</p>
            </div>
        </section>
    </div>

    <?php include 'includes/footer.html'; ?>
</body>
</html>