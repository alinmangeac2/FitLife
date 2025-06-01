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
            <!-- Simple Recipes Only -->
            <!-- Simple Recipes Only -->
            <div class="recipe">
                <h2>Benedict Eggs</h2>
                <div class="collapse-content" style="display:none; text-align:left;">
                    <strong>Ingredients:</strong>
                    <ul>
                        <li>2 eggs</li>
                        <li>1 English muffin, split</li>
                        <li>2 slices Canadian bacon</li>
                        <li>Hollandaise sauce (store-bought or homemade)</li>
                        <li>Vinegar (for poaching)</li>
                        <li>Salt & pepper</li>
                    </ul>
                    <strong>Instructions:</strong>
                    <ol>
                        <li>Toast the English muffin halves and warm the Canadian bacon in a skillet.</li>
                        <li>Poach eggs in simmering water with a splash of vinegar until whites are set.</li>
                        <li>Top each muffin half with bacon, a poached egg, and hollandaise sauce. Season and serve.</li>
                    </ol>
                </div>
            </div>
            <div class="recipe">
                <h2>Avocado Toast</h2>
                <div class="collapse-content" style="display:none; text-align:left;">
                    <strong>Ingredients:</strong>
                    <ul>
                        <li>2 slices whole-grain bread</li>
                        <li>1 ripe avocado</li>
                        <li>Salt & pepper</li>
                        <li>Chili flakes (optional)</li>
                        <li>Lemon juice (optional)</li>
                    </ul>
                    <strong>Instructions:</strong>
                    <ol>
                        <li>Toast the bread slices.</li>
                        <li>Mash avocado with salt, pepper, and lemon juice if desired.</li>
                        <li>Spread avocado on toast and sprinkle with chili flakes. Serve immediately.</li>
                    </ol>
                </div>
            </div>
            <div class="recipe">
                <h2>Grilled Chicken</h2>
                <div class="collapse-content" style="display:none; text-align:left;">
                    <strong>Ingredients:</strong>
                    <ul>
                        <li>2 boneless, skinless chicken breasts</li>
                        <li>1 tbsp olive oil</li>
                        <li>Salt & pepper</li>
                        <li>1 tsp paprika (optional)</li>
                    </ul>
                    <strong>Instructions:</strong>
                    <ol>
                        <li>Preheat grill to medium-high. Brush chicken with olive oil and season with salt, pepper, and paprika.</li>
                        <li>Grill chicken 5-7 minutes per side, until cooked through and juices run clear.</li>
                        <li>Let rest a few minutes before slicing and serving.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</body>
</html>