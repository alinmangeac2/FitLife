<?php
include('../includes/db.php');
include('../includes/header.php');
?>
<h1>Water Reports</h1>
<ul>
    <?php
    $sql = "SELECT * FROM water_reports";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<li><strong>Water Intake:</strong> " . $row["water_intake"]. " ml <br><strong>Date:</strong> " . $row["intake_date"]. "</li>";
        }
    } else {
        echo "0 results";
    }
    $conn->close();
    ?>
</ul>
<?php include('../includes/footer.php'); ?>