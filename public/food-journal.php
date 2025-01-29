<?php
include('../includes/db.php');
include('../includes/header.php');
?>
<h1>Food Journal</h1>
<ul>
    <?php
    $sql = "SELECT * FROM food_journal";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<li><strong>Food Item:</strong> " . $row["food_item"]. " <br><strong>Calories:</strong> " . $row["calories"]. " <br><strong>Meal Type:</strong> " . $row["meal_type"]. " <br><strong>Date:</strong> " . $row["food_date"]. "</li>";
        }
    } else {
        echo "0 results";
    }
    $conn->close();
    ?>
</ul>
<?php include('../includes/footer.php'); ?>