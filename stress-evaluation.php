<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user_id = $_SESSION['user_id'];
    $stress_level = $_POST['stress_level'];
    $evaluation_date = date('Y-m-d'); // Automatically use today's date

    // Prepare the SQL statement
    $sql = "INSERT INTO stress_evaluation (user_id, stress_level, evaluation_date) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Database error: " . $conn->error);
    }

    $stmt->bind_param("iis", $user_id, $stress_level, $evaluation_date);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: stress-evaluation.php");
        exit();
    } else {
        echo "Error inserting stress evaluation: " . $stmt->error;
    }

    $stmt->close();
}

// Get the current date
$user_id = $_SESSION['user_id'];
$current_date = date('Y-m-d');

// Fetch latest stress evaluation
$sql = "SELECT stress_level FROM stress_evaluation WHERE user_id = ? AND evaluation_date = ? ORDER BY id DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $current_date);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$current_stress = isset($row['stress_level']) ? (int)$row['stress_level'] : 0;

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/stress-evaluation.css">
    <title>Stress Level Tracker</title>
</head>
<body class="stress-evaluation-page">
    <?php include 'includes/header.php'; ?>
    <h1>Stress Level Tracker</h1>
    <div class="stress-container">
        <div class="progress-container">
            <h2>Today's Stress Level</h2>
            <div class="progress-bar">
                <div class="progress" style="width: <?php echo ($current_stress * 10); ?>%;"><?php echo $current_stress; ?></div>
            </div>
            <p>Your recorded stress level: <strong><?php echo $current_stress; ?></strong></p>
        </div>
        <div class="stress-form">
            <form action="stress-evaluation.php" method="post">
                <h2>Add Stress Evaluation</h2>
                
                <label for="stress_level">Stress Level (1-10):</label>
                <input type="number" id="stress_level" name="stress_level" min="1" max="10" required>

                <button type="submit">Submit</button>
            </form>
        </div>
    </div>
</body>
</html>