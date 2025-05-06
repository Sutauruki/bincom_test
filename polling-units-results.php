<?php
require_once 'backend/db.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Polling Unit Results</title>
</head>
<body>
    <h2>Select a Polling Unit</h2>
    <form method="GET">
        <select name="polling_unit_name">
            <?php
            $result = $conn->query("SELECT DISTINCT polling_unit_name FROM polling_unit");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['polling_unit_name'] . "'>" . $row['polling_unit_name'] . "</option>";
            }
            ?>
        </select>
        <button type="submit">View Results</button>
    </form>

    <?php
    if (isset($_GET['polling_unit_name'])) {
        $pu_name = $conn->real_escape_string($_GET['polling_unit_name']);
        $sql = "
            SELECT apr.party_abbreviation, apr.party_score
            FROM announced_pu_results apr
            JOIN polling_unit pu ON apr.polling_unit_uniqueid = pu.uniqueid
            WHERE pu.polling_unit_name = '$pu_name'
        ";
        $results = $conn->query($sql);

        echo "<h3>Results for: $pu_name</h3>";
        echo "<table border='1'><tr><th>Party</th><th>Score</th></tr>";
        while ($row = $results->fetch_assoc()) {
            echo "<tr><td>{$row['party_abbreviation']}</td><td>{$row['party_score']}</td></tr>";
        }
        echo "</table>";
    }
    ?>
</body>
</html>
