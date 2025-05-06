<?php
require_once 'backend/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LGA Polling Results | INEC Delta State</title>
    <link rel="stylesheet" href="assets/styles/results.css">
    <style>
        
    </style>
</head>
<body>
    <?php include('assets/components/header.php') ?>
    
    <main class="container">
        <h2 class="page-title">LGA Polling Results</h2>
        
        <div class="content-wrapper">
            <div class="selection-panel">
                <h3>Select a Local Government Area</h3>
                <form method="GET">
                    <div class="form-group">
                        <label for="lga_id">Local Government Area:</label>
                        <select id="lga_id" name="lga_id">
                            <?php
                            $lga_result = $conn->query("SELECT * FROM lga");
                            while ($row = $lga_result->fetch_assoc()) {
                                $selected = (isset($_GET['lga_id']) && $_GET['lga_id'] == $row['lga_id']) ? 'selected' : '';
                                echo "<option value='" . $row['lga_id'] . "' $selected>" . $row['lga_name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn">View Results</button>
                </form>
            </div>
            
            <div class="results-panel">
                <?php
                if (isset($_GET['lga_id'])) {
                    $lga_id = intval($_GET['lga_id']);
                    
                    // Get LGA name
                    $lga_name_query = $conn->query("SELECT lga_name FROM lga WHERE lga_id = $lga_id");
                    $lga_name_row = $lga_name_query->fetch_assoc();
                    $lga_name = $lga_name_row ? $lga_name_row['lga_name'] : "Selected LGA";

                    // Get all polling unit IDs under the LGA
                    $pu_result = $conn->query("SELECT uniqueid FROM polling_unit WHERE lga_id = $lga_id");

                    $pu_ids = [];
                    while ($row = $pu_result->fetch_assoc()) {
                        $pu_ids[] = $row['uniqueid'];
                    }

                    echo "<h3 class='results-title'>Results for: $lga_name</h3>";
                    
                    if (count($pu_ids) > 0) {
                        $pu_list = implode(",", $pu_ids);

                        $sql = "
                            SELECT party_abbreviation, SUM(party_score) as total_score
                            FROM announced_pu_results
                            WHERE polling_unit_uniqueid IN ($pu_list)
                            GROUP BY party_abbreviation
                        ";

                        $results = $conn->query($sql);
                        
                        if ($results->num_rows > 0) {
                            echo "<table>
                                    <thead>
                                        <tr>
                                            <th>Party</th>
                                            <th>Total Score</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
                            while ($row = $results->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$row['party_abbreviation']}</td>
                                        <td>{$row['total_score']}</td>
                                      </tr>";
                            }
                            echo "</tbody></table>";
                        } else {
                            echo "<p>No results found for this LGA.</p>";
                        }
                    } else {
                        echo "<p>No polling units found for selected LGA.</p>";
                    }
                } else {
                    echo "<p>Please select a Local Government Area to view results.</p>";
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