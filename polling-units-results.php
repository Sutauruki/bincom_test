<?php
require_once 'backend/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Polling Unit Results | INEC Delta State</title>
    <link rel="stylesheet" href="assets/styles/results.css">
    <style>
        
    </style>
</head>
<body>
    
    <?php include('assets/components/header.php') ?>
    <main class="container">
        <h2 class="page-title">Polling Unit Results</h2>
        
        <div class="content-wrapper">
            <div class="selection-panel">
                <h3>Select a Polling Unit</h3>
                <form method="GET">
                    <div class="form-group">
                        <label for="polling_unit_name">Polling Unit:</label>
                        <select id="polling_unit_name" name="polling_unit_name">
                            <?php
                            $result = $conn->query("SELECT DISTINCT polling_unit_name FROM polling_unit");
                            while ($row = $result->fetch_assoc()) {
                                $selected = (isset($_GET['polling_unit_name']) && $_GET['polling_unit_name'] == $row['polling_unit_name']) ? 'selected' : '';
                                echo "<option value='" . $row['polling_unit_name'] . "' $selected>" . $row['polling_unit_name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn">View Results</button>
                </form>
            </div>
            
            <div class="results-panel">
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

                    echo "<h3 class='results-title'>Results for: $pu_name</h3>";
                    
                    if ($results->num_rows > 0) {
                        echo "<table>
                                <thead>
                                    <tr>
                                        <th>Party</th>
                                        <th>Score</th>
                                    </tr>
                                </thead>
                                <tbody>";
                        while ($row = $results->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['party_abbreviation']}</td>
                                    <td>{$row['party_score']}</td>
                                  </tr>";
                        }
                        echo "</tbody></table>";
                    } else {
                        echo "<p>No results found for this polling unit.</p>";
                    }
                } else {
                    echo "<p>Please select a polling unit to view results.</p>";
                }
                ?>
            </div>
        </div>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; 2025 INEC Delta State</p>
        </div>
    </footer>
</body>
</html>