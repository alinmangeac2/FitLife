<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user_id = $_SESSION['user_id'];
    $water_intake = $_POST['water_intake'];
    $intake_date = date('Y-m-d'); // Automatically use today's date

    // Prepare the SQL statement
    $sql = "INSERT INTO water_reports (user_id, water_intake, intake_date) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Database error: " . $conn->error);
    }

    $stmt->bind_param("iis", $user_id, $water_intake, $intake_date);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: water-reports.php");
        exit();
    } else {
        echo "Error inserting water intake: " . $stmt->error;
    }

    $stmt->close();
}

// Get the current date
$user_id = $_SESSION['user_id'];
$current_date = date('Y-m-d');

// Fetch total water intake for today
$sql = "SELECT SUM(water_intake) as total_water FROM water_reports WHERE user_id = ? AND intake_date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $current_date);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_water = $row['total_water'] ? $row['total_water'] : 0;

$stmt->close();
$conn->close();

// Calculate progress percentage
$water_goal = 3000; // Example goal in milliliters
$progress_percentage = min(100, ($total_water / $water_goal) * 100);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/water-reports.css">
    <title>Water Intake Tracker</title>
</head>
<body class="water-reports-page">
    <?php include 'includes/header.php'; ?>
    <h1>Water Intake Tracker</h1>
    <div class="water-container">
        <div class="progress-container">
            <h2>Daily Water Intake Goal</h2>
            <div class="progress-bar">
                <div class="progress" style="width: <?php echo $progress_percentage; ?>%;"><?php echo round($progress_percentage); ?>%</div>
            </div>
            <p>You've consumed <?php echo $total_water; ?> out of <?php echo $water_goal; ?> ml of water today.</p>
        </div>
        <div class="water-form">
            <form action="water-reports.php" method="post">
                <h2>Add Water Intake</h2>
                
                <label for="water_intake">Water Intake (ml):</label>
                <input type="number" id="water_intake" name="water_intake" required>

                <button type="submit">Add Water Intake</button>
            </form>
        </div>
    </div>
</body>
</html>
