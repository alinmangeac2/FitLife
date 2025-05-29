<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $food_item = $_POST['meal'];
    $calories = $_POST['calories'];
    $meal_type = $_POST['meal_type'];
    $food_date = date('Y-m-d');

    // Prepare the SQL statement
    $sql = "INSERT INTO food_journal (user_id, food_item, calories, meal_type, food_date) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Database error: " . $conn->error);
    }

    $stmt->bind_param("isiss", $user_id, $food_item, $calories, $meal_type, $food_date);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: food-journal.php");
        exit();
    } else {
        echo "Error inserting food journal entry: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch food journal entries for the current user
$user_id = $_SESSION['user_id'];
$sql = "SELECT food_item, calories, meal_type, food_date FROM food_journal WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$food_entries = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/food-journal.css">
    <title>Food Journal</title>
</head>
<body class="food-journal-page">
<?php include 'includes/header.php'; ?>
<h1>Food Journal</h1>
<div class="content">
    <form id="food-form" class="food-form" action="food-journal.php" method="post">
<div class="form-group">
        <label for="meal">Meal:</label>
<input type="text" id="meal" name="meal" required>
        </div>
        <div class="form-group">
        <label for="calories">Calories:</label>
<input type="number" id="calories" name="calories" required>
        </div>
        <div class="form-group">
        <label for="meal_type">Meal Type:</label> 
        <select id="meal_type" name="meal_type" required>
            <option value="breakfast">Breakfast</option>
            <option value="lunch">Lunch</option>
            <option value="dinner">Dinner</option>
            <option value="snack">Snack</option>
        </select>
        </div>
        <button type="submit" class="btn-submit">Add Meal</button>
    </form>
    <h2>Daily Totals</h2>
    <p>Calories: <span id="total-calories">0</span></p>
    <ul id="food-list">
        <?php foreach ($food_entries as $entry): ?>
        <li>
            <strong>Food Item:</strong> <?php echo htmlspecialchars($entry['food_item']); ?> <br>
            <strong>Calories:</strong> <?php echo htmlspecialchars($entry['calories']); ?> <br>
            <strong>Meal Type:</strong> <?php echo htmlspecialchars($entry['meal_type']); ?> <br>
            <strong>Date:</strong> <?php echo htmlspecialchars($entry['food_date']); ?>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
<script src="js/food-journal.js"></script>
</body>
</html>