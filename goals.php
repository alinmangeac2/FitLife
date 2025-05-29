<?php
session_start();
include 'config/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle form submission (insert new goal)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_weight = isset($_POST['current-weight']) ? (int)$_POST['current-weight'] : null;
    $target_weight = isset($_POST['target-weight']) ? (int)$_POST['target-weight'] : null;
    $goal_type = isset($_POST['goal']) ? $_POST['goal'] : null;
    $goal_date = date('Y-m-d');

    if ($current_weight && $target_weight && $goal_type) {
        $sql = "INSERT INTO goals (user_id, goal_type, target_value, current_value, goal_date) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isiss", $user_id, $goal_type, $target_weight, $current_weight, $goal_date);
        $stmt->execute();
        $stmt->close();
        header("Location: goals.php"); // reload to show progress bar
        exit();
    }
}

// Fetch the latest goal for the user
$goal_query = "SELECT * FROM goals WHERE user_id = ? ORDER BY goal_date DESC, id DESC LIMIT 1";
$stmt = $conn->prepare($goal_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$goal_result = $stmt->get_result();
$goal = $goal_result->fetch_assoc();
$stmt->close();

$progress_percent = 0;
$progress_text = "No goal set.";

if ($goal) {
    $goal_type = $goal['goal_type'];
    $target = (int)$goal['target_value'];
    $current = (int)$goal['current_value'];
    $goal_date = $goal['goal_date'];

    // Calculate total calories burned from activities since the goal was set
    $activity_query = "SELECT SUM(calories_burned) as total_burned FROM activity WHERE user_id = ? AND activity_date >= ?";
    $stmt2 = $conn->prepare($activity_query);
    $stmt2->bind_param("is", $user_id, $goal_date);
    $stmt2->execute();
    $activity_result = $stmt2->get_result();
    $activity = $activity_result->fetch_assoc();
    $total_burned = $activity['total_burned'] ? (int)$activity['total_burned'] : 0;
    $stmt2->close();

    if ($goal_type == 'lose-weight') {
        $goal_amount = $current - $target; // e.g., 80kg - 70kg = 10kg to lose
        if ($goal_amount > 0) {
            // Estimate weight lost: 7700 kcal = 1kg fat
            $weight_lost = $total_burned / 7700;
            $progress_percent = min(100, max(0, ($weight_lost / $goal_amount) * 100));
            $progress_text = "You are " . round($progress_percent) . "% towards your goal of losing $goal_amount kg.";
        } else {
            $progress_percent = 100;
            $progress_text = "You have already reached your goal!";
        }
    } elseif ($goal_type == 'gain-muscle') {
        $goal_amount = $target - $current;
        if ($goal_amount > 0) {
            $progress_percent = 0; // Not implemented
            $progress_text = "Progress calculation for muscle gain is not implemented.";
        } else {
            $progress_percent = 100;
            $progress_text = "You have already reached your goal!";
        }
    } elseif ($goal_type == 'maintain-weight') {
        $progress_percent = 100;
        $progress_text = "You are maintaining your weight!";
    }
}
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
        <?php if (!$goal): ?>
        <form id="goal-form" action="goals.php" method="post">
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
        <?php endif; ?>

        <!-- Progress Bar Section -->
        <?php if ($goal): ?>
        <div class="progress-container">
            <h2>Goal Progress</h2>
            <div class="progress-bar">
                <div class="progress" style="width: <?php echo round($progress_percent); ?>%;"><?php echo round($progress_percent); ?>%</div>
            </div>
            <p><?php echo $progress_text; ?></p>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
