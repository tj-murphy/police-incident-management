<?php
    session_start();
    include 'navbar.php';
    include 'db_connect.php';
    include 'audit_log.php';

    $message = '';
    $message_class = '';

    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        $incident_id = $_GET["id"];
        $sql = "SELECT Incident.Incident_ID, Vehicle.Vehicle_licence, People.People_licence, Incident.Incident_Date, Incident.Incident_Report, Offence.Offence_description
                FROM Incident
                INNER JOIN Vehicle ON Incident.Vehicle_ID = Vehicle.Vehicle_ID
                INNER JOIN People ON Incident.People_ID = People.People_ID
                INNER JOIN Offence ON Incident.Offence_ID = Offence.Offence_ID
                WHERE Incident.Incident_ID = '$incident_id';";

        $result = $conn -> query ($sql);
        $row = $result -> fetch_assoc();

        $sql = "SELECT Offence_ID, Offence_description FROM Offence";
        $offences = $conn -> query($sql);
    }
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $incident_id = $_POST["incident_id"];
        $incident_report = $_POST["incident_report"];
        $vehicle_licence = $_POST["vehicle_licence"];
        $people_licence = $_POST["people_licence"];
        $incident_date = $_POST["incident_date"];
        $offence_id = $_POST["offence_id"];

        $sql = "UPDATE Incident 
                INNER JOIN Vehicle ON Incident.Vehicle_ID = Vehicle.Vehicle_ID
                INNER JOIN People ON Incident.People_ID = People.People_ID
                INNER JOIN Offence ON Incident.Offence_ID = Offence.Offence_ID
                SET Vehicle.Vehicle_licence = '$vehicle_licence', People.People_licence = '$people_licence', Incident.Incident_Date = '$incident_date', Incident.Incident_Report = '$incident_report', Incident.Offence_ID = '$offence_id'
                WHERE Incident.Incident_ID = '$incident_id';";

        if ($conn -> query($sql) === TRUE)
        {
            // Log the action
            $log_message = logAction($_SESSION["user_id"], "update", "Incident", $incident_id, "Updated an incident");
            if (strpos($log_message, "Error:") !== false)
            {
                $message = $log_message;
                $message_class = "error";
            }
            else
            {
                $message = "Changes saved successfully. " . $log_message;
                $message_class = "success";
            }
        }
        else
        {
            $message = "Error: " . $sql . "<br/>" . $conn -> error;
            $message_class = "error";
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Edit Incident</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f0f0f0;
            }
            .container {
                width: 80%;
                margin: 0 auto;
            }
            .form-container {
                background-color: #fff;
                padding: 20px;
                margin-top: 20px;
                border: 1px solid #ddd;
                border-radius: 5px;
            }
            .form-container h1 {
                text-align: center;
                color: #333;
            }
            .form-container label {
                display: block;
                margin-bottom: 5px
            }
            .form-container input[type="text"], input[type="datetime-local"], select, textarea {
                width: 100%;
                padding: 10px;
                margin-bottom: 10px;
                border: 1px solid #ddd;
            }
            .form-container input[type="submit"] {
                padding: 10px 20px;
                background-color: #007BFF;
                color: #fff;
                border: none;
                cursor: pointer;
            }
            .form-container input[type="submit"]:hover {
                background-color: #0056b3;
            }
            .confirmation {
                padding: 10px;
                margin: 10px 0;
                border-radius: 5px;
                color: #fff;
            }
            .confirmation.success {
                background-color: #4CAF50;
            }
            .confirmation.error {
                background-color: #f44336;
            }
        </style>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>

    <body>
        <div class="container">
            <div class="form-container">
                <h1>Edit Incident</h1>
                <form method="POST" action="">
                    <label for="incident_date">Incident Date:</label><br>
                    <input type="datetime-local" id="incident_date" name="incident_date" value="<?php echo isset($row) ? $row['Incident_Date'] : ''; ?>" required><br>
                    <label for="vehicle_licence">Vehicle Licence:</label><br>
                    <input type="text" id="vehicle_licence" name="vehicle_licence" value="<?php echo isset($row) ? $row['Vehicle_licence'] : ''; ?>" required><br>
                    <label for="people_licence">People Licence:</label><br>
                    <input type="text" id="people_licence" name="people_licence" value="<?php echo isset($row) ? $row['People_licence'] : ''; ?>" required><br>
                    <label for="incident_report">Incident Report:</label><br>
                    <textarea id="incident_report" name="incident_report" required><?php echo isset($row) ? $row['Incident_Report'] : ''; ?></textarea><br>
                    <label for="offence_description">Offence Description:</label><br>
                    <select id="offence_id" name="offence_id" required>
                        <?php
                        while ($offence = $offences -> fetch_assoc())
                        {
                            echo "<option value='" . $offence['Offence_ID'] . "'" . ($offence['Offence_description'] == $row['Offence_description'] ? " selected" : "") . ">" . $offence['Offence_description'] . "</option>";
                        }
                        ?>
                    </select><br>
                    <input type="hidden" name="incident_id" value="<?php echo $row['Incident_ID']; ?>">
                    <input type="submit" value="Save Changes">
                </form>
                <?php if (!empty($message)): ?>
                    <div class="confirmation <?= $message_class; ?>">
                        <?= $message; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>