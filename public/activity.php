<?php
include('../includes/db.php');
include('../includes/header.php');
?>
<h1>Activity Tracker</h1>
<ul>
    <?php
    $sql = "SELECT * FROM activity";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<li><strong>Type:</strong> " . $row["activity_type"]. " <br><strong>Duration:</strong> " . $row["duration"]. " minutes <br><strong>Calories Burned:</strong> " . $row["calories_burned"]. " <br><strong>Date:</strong> " . $row["activity_date"]. "</li>";
        }
    } else {
        echo "0 results";
    }
    $conn->close();
    ?>
</ul>
<?php include('../includes/footer.php'); ?>