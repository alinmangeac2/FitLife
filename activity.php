<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session
    $activity_type = $_POST['activity_type'];
    $duration = $_POST['duration'];
    $calories_burned = $_POST['calories_burned'];
    $activity_date = $_POST['activity_date'];

    // Validate and format the date
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $activity_date)) {
        echo "Invalid date format! Please use YYYY-MM-DD.";
        exit();
    }

    // Prepare the SQL statement
    $sql = "INSERT INTO activity (user_id, activity_type, duration, calories_burned, activity_date) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Database error: " . $conn->error);
    }

    $stmt->bind_param("sssis", $user_id, $activity_type, $duration, $calories_burned, $activity_date);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: activity.php");
        exit();
    } else {
        echo "Error inserting activity: " . $stmt->error;
    }

    $stmt->close();
}

// Get the current date
$user_id = $_SESSION['user_id'];
$current_date = date('Y-m-d');

// Fetch total duration and calories burned for today
$sql = "SELECT SUM(duration) as total_duration, SUM(calories_burned) as total_calories FROM activity WHERE user_id = ? AND activity_date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $current_date);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_duration = $row['total_duration'] ? $row['total_duration'] : 0;
$total_calories = $row['total_calories'] ? $row['total_calories'] : 0;

$stmt->close();

// Fetch total duration of each activity type for today
$sql = "SELECT activity_type, SUM(duration) as total_duration FROM activity WHERE user_id = ? AND activity_date = ? GROUP BY activity_type";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $current_date);
$stmt->execute();
$result = $stmt->get_result();
$activity_durations = [];
while ($row = $result->fetch_assoc()) {
    $activity_durations[] = $row;
}

$stmt->close();
$conn->close();

// Calculate progress percentage
$calories_goal = 500; // Example goal
$progress_percentage = min(100, ($total_calories / $calories_goal) * 100);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/activity.css">
    <title>Activity Tracker</title>
</head>
<body class="activity-page">
    <?php include 'includes/header.php'; ?>
    <h1>Activity Tracker</h1>
    <div class="activity-container">
        <div class="progress-container">
            <h2>Daily Activity Goal</h2>
            <div class="progress-bar">
                <div class="progress" style="width: <?php echo $progress_percentage; ?>%;"><?php echo round($progress_percentage); ?>%</div>
            </div>
            <p>You've burned <?php echo $total_calories; ?> out of <?php echo $calories_goal; ?> calories today.</p>
        </div>
        <div class="activity-form">
            <form action="activity.php" method="post">
                <h2>Add New Activity</h2>
                
                <label for="activity_type">Activity Type:</label>
                <select id="activity_type" name="activity_type" required>
                    <option value="">Select an activity</option>
                    <option value="Running">Running</option>
                    <option value="Cycling">Cycling</option>
                    <option value="Swimming">Swimming</option>
                    <option value="Walking">Walking</option>
                    <option value="Yoga">Yoga</option>
                    <option value="Weightlifting">Weightlifting</option>
                </select>

                <label for="duration">Duration (minutes):</label>
                <input type="number" id="duration" name="duration" required>

                <label for="calories_burned">Calories Burned:</label>
                <input type="number" id="calories_burned" name="calories_burned" required>

                <label for="activity_date">Date:</label>
                <input type="date" id="activity_date" name="activity_date" required>

                <button type="submit">Add Activity</button>
            </form>
        </div>
        <ul class="activity-list">
            <li>
                <strong>Total Activity Time Today:</strong> <?php echo $total_duration; ?> minutes
            </li>
            <?php foreach ($activity_durations as $activity): ?>
                <li>
                    <strong><?php echo $activity['activity_type']; ?>:</strong> <?php echo $activity['total_duration']; ?> minutes
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
