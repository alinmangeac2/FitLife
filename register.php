<?php
// Start the session
session_start();

// Include database connection
include 'config/db.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $age = intval($_POST['age']);
    $weight = floatval($_POST['weight']);
    $height = floatval($_POST['height']);
    $activity_level = $_POST['activity_level'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if username already exists
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username already exists.";
        } else {
            // Check if email already exists
            $sql = "SELECT id FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $error = "Email already exists.";
            } else {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert user into the database
                $sql = "INSERT INTO users (username, email, password, age, weight, height, activity_level) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssddds", $username, $email, $hashed_password, $age, $weight, $height, $activity_level);

                if ($stmt->execute()) {
                    echo "<script>alert('User created successfully!'); window.location.href='login.php';</script>";
                    exit();
                } else {
                    $error = "Registration failed. Please try again.";
                }
            }
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/register.css">
</head>
<body class="register-page">
    <?php include 'includes/header.php'; ?>
    <div class="register-container">
        <h1>Register</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form id="register-form" action="register.php" method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <label for="age">Age</label>
            <input type="number" id="age" name="age" min="10" required>

            <label for="weight">Weight (kg)</label>
            <input type="number" id="weight" name="weight" step="0.1" required>

            <label for="height">Height (cm)</label>
            <input type="number" id="height" name="height" step="0.1" required>

            <label for="activity_level">Activity Level</label>
            <select id="activity_level" name="activity_level" required>
                <option value="Sedentary">Sedentary</option>
                <option value="Lightly active">Lightly active</option>
                <option value="Moderately active">Moderately active</option>
                <option value="Very active">Very active</option>
                <option value="Super active">Super active</option>
            </select>

            <button type="submit">Register</button>

            <div class="links">
                <a href="login.php">Already have an account? Login</a>
            </div>
        </form>

    </div>

    <?php include 'includes/footer.html'; ?>
</body>
</html>
