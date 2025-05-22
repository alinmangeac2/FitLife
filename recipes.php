<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/recipes.css">
    <title>Healthy Recipes</title>
</head>
<body class="recipes-page">
    <?php include 'includes/header.php'; ?>
    <h1>Healthy Recipes</h1>
    <div class="content-wrapper">
        <div class="recipes-container">
            <div class="recipe">
                <h2>Avocado Toast</h2>
                <p>A quick and healthy breakfast option with avocado, whole-grain bread, and a sprinkle of chili flakes.</p>
            </div>
            
            <div class="recipe">
                <h2>Quinoa Salad</h2>
                <p>A refreshing salad with quinoa, cherry tomatoes, cucumbers, and a lemon vinaigrette.</p>
            </div>
            
            <div class="recipe">
                <h2>Grilled Chicken</h2>
                <p>Perfectly seasoned grilled chicken served with steamed vegetables.</p>
            </div>
        </div>
    </div>
</body>
</html>