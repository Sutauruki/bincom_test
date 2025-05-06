<?php
include 'backend/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Polling Unit Result | INEC Delta State</title>
    <style>
        :root {
            --primary-color: #008752; /* INEC Green */
            --secondary-color: #fff;
            --accent-color: #2D71B6; /* Blue */
            --dark-color: #333;
            --light-color: #f8f8f8;
            --border-color: #ddd;
            --success-color: #4CAF50;
            --error-color: #f44336;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        html, body {
            height: 100%;
        }
        
        body {
            background-color: var(--light-color);
            color: var(--dark-color);
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }        main {
            flex: 1;
            padding: 30px 0;
        }
        
        .page-title {
            font-size: 24px;
            margin-bottom: 20px;
            color: var(--primary-color);
            text-align: center;
        }
        
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: var(--secondary-color);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark-color);
        }
        
        select, input {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        select:focus, input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(0, 135, 82, 0.2);
        }
        
        .btn {
            width: 100%;
            padding: 14px;
            background-color: var(--primary-color);
            color: var(--secondary-color);
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            background-color: #006b40;
        }
        
        .result-message {
            margin-top: 20px;
            padding: 15px;
            border-radius: 4px;
            font-weight: 500;
        }
        
        .success {
            background-color: rgba(76, 175, 80, 0.1);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }
        
        .error {
            background-color: rgba(244, 67, 54, 0.1);
            color: var(--error-color);
            border-left: 4px solid var(--error-color);
        }
        
        footer {
            background-color: var(--primary-color);
            color: var(--secondary-color);
            text-align: center;
            padding: 15px 0;
            font-size: 14px;
            margin-top: auto;
        }
        
        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
            }
            
            .logo-container {
                justify-content: center;
                margin-bottom: 10px;
            }
            
            nav ul {
                justify-content: center;
                margin-top: 10px;
            }
            
            nav ul li {
                margin: 0 10px;
            }
            
            .form-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
<?php include('assets/components/header.php') ?>        
    
    <main class="container">
        <h2 class="page-title">Insert Polling Unit Result</h2>
        
        <div class="form-container">
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $polling_unit = $_POST['polling_unit'];
                $party = $_POST['party'];
                $score = intval($_POST['score']);
                $user = $_POST['agent_name']; 
                $ip = $_SERVER['REMOTE_ADDR'];
                $date = date('Y-m-d H:i:s');

                $stmt = $conn->prepare("
                    INSERT INTO announced_pu_results 
                    (polling_unit_uniqueid, party_abbreviation, party_score, entered_by_user, date_entered, user_ip_address)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $stmt->bind_param("isisss", $polling_unit, $party, $score, $user, $date, $ip);

                if ($stmt->execute()) {
                    echo "<div class='result-message success'><p>✅ Result inserted successfully.</p></div>";
                } else {
                    echo "<div class='result-message error'><p>❌ Error: " . $stmt->error . "</p></div>";
                }
            }
            ?>

            <form method="POST">
                <div class="form-group">
                    <label for="polling_unit">Polling Unit:</label>
                    <select id="polling_unit" name="polling_unit" required>
                        <option value="">-- Select Polling Unit --</option>
                        <?php
                        $res = $conn->query("SELECT uniqueid, polling_unit_name FROM polling_unit");
                        while ($row = $res->fetch_assoc()) {
                            echo "<option value='{$row['uniqueid']}'>{$row['polling_unit_name']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="party">Party:</label>
                    <select id="party" name="party" required>
                        <option value="">-- Select Party --</option>
                        <?php
                        $res = $conn->query("SELECT partyid, partyname FROM party");
                        while ($row = $res->fetch_assoc()) {
                            echo "<option value='{$row['partyid']}'>{$row['partyname']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="agent_name">Agent Name:</label>
                    <input type="text" id="agent_name" name="agent_name" placeholder="Enter agent's full name" required>
                </div>

                <div class="form-group">
                    <label for="score">Score:</label>
                    <input type="number" id="score" name="score" min="0" placeholder="Enter party score" required>
                </div>

                <button type="submit" class="btn">Submit Result</button>
            </form>
        </div>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; 2025 INEC Delta State</p>
        </div>
    </footer>
</body>
</html>