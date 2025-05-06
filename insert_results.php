<?php
include 'backend/db.php'; // connect to DB

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $polling_unit = $_POST['polling_unit'];
    $party = $_POST['party'];
    $score = intval($_POST['score']);
    $user = 'Sutauruki'; // could be from session
    $ip = $_SERVER['REMOTE_ADDR'];
    $date = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("
        INSERT INTO announced_pu_results 
        (polling_unit_uniqueid, party_abbreviation, party_score, entered_by_user, date_entered, user_ip_address)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("isisss", $polling_unit, $party, $score, $user, $date, $ip);

    if ($stmt->execute()) {
        echo "<p>✅ Result inserted successfully.</p>";
    } else {
        echo "<p>❌ Error: " . $stmt->error . "</p>";
    }
}
?>

<h2>Insert Polling Unit Result</h2>
<form method="POST">
    <label>Polling Unit:</label>
    <select name="polling_unit" required>
        <?php
        $res = $conn->query("SELECT uniqueid, polling_unit_name FROM polling_unit");
        while ($row = $res->fetch_assoc()) {
            echo "<option value='{$row['uniqueid']}'>{$row['polling_unit_name']}</option>";
        }
        ?>
    </select><br><br>

    <label>Party:</label>
    <select name="party" id="">
    <?php
        $res = $conn->query("SELECT partyid, partyname FROM party");
        while ($row = $res->fetch_assoc()) {
            echo "<option value='{$row['partyid']}'>{$row['partyname']}</option>";
        }
        ?>
    </select><br><br>

    <label>Score:</label>
    <input type="number" name="score" required><br><br>

    <button type="submit">Insert Result</button>
</form>
