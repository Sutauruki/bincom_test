<?php
require_once 'backend/db.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>LGA Polling Results</title>
</head>
<body>
    <h2>Select a Local Government Area</h2>
    <form method="GET">
        <select name="lga_id">
            <?php
            $lga_result = $conn->query("SELECT * FROM lga");
            while ($row = $lga_result->fetch_assoc()) {
                echo "<option value='" . $row['lga_id'] . "'>" . $row['lga_name'] . "</option>";
            }
            ?>
        </select>
        <button type="submit">View Results</button>
    </form>

    <?php
    if (isset($_GET['lga_id'])) {
        $lga_id = intval($_GET['lga_id']);

        // Get all polling unit IDs under the LGA
        $pu_result = $conn->query("SELECT uniqueid FROM polling_unit WHERE lga_id = $lga_id");

        $pu_ids = [];
        while ($row = $pu_result->fetch_assoc()) {
            $pu_ids[] = $row['uniqueid'];
        }

        if (count($pu_ids) > 0) {
            $pu_list = implode(",", $pu_ids);

            $sql = "
                SELECT party_abbreviation, SUM(party_score) as total_score
                FROM announced_pu_results
                WHERE polling_unit_uniqueid IN ($pu_list)
                GROUP BY party_abbreviation
            ";

            $results = $conn->query($sql);

            echo "<h3>Results for selected LGA</h3>";
            echo "<table border='1'><tr><th>Party</th><th>Total Score</th></tr>";
            while ($row = $results->fetch_assoc()) {
                echo "<tr><td>{$row['party_abbreviation']}</td><td>{$row['total_score']}</td></tr>";
            }
            echo "</table>";
        } else {
            echo "No polling units found for selected LGA.";
        }
    }
    ?>
</body>
</html>
 