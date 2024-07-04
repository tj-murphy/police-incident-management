<?php
    session_start();
    include 'navbar.php';
    include 'db_connect.php';
    include 'audit_log.php';

    $message = '';
    $message_class = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $incident_date = $_POST["incident_date"];
        $incident_report = $_POST["incident_report"];
        $offence_id = $_POST["offence_id"];

        $vehicle_id = $_POST["vehicle_id"];
        $people_id = $_POST["people_id"];

        // Check if person and vehicle exist in the database
        if ($vehicle_id === null || $people_id === null)
        {
            $message = "Error: Person or Vehicle does not exist in the database.";
            $message_class = "error";
            return;
        }

        $sql = "INSERT INTO Incident (Vehicle_ID, People_ID, Incident_Date, Incident_Report, Offence_ID) 
                VALUES ('$vehicle_id', '$people_id', '$incident_date', '$incident_report', '$offence_id')";
        
        if ($conn -> query($sql) === TRUE)
        {
            // Log the action
            $log_message = logAction($_SESSION["user_id"], "create", "Incident", $conn -> insert_id, "Reported a new incident");
            if (strpos($log_message, "Error:") !== false)
            {
                $message = $log_message;
                $message_class = "error";
            }
            else
            {
                $message = "Incident reported successfully. " . $log_message;
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
        <title>Report Incident</title>
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
                <h1>Report Incident</h1>
                <form method="POST" action="report_incident.php">
                    <label for="incident_date">Incident Date:</label><br>
                    <input type="datetime-local" id="incident_date" name="incident_date" required><br>

                    <label for="vehicle_id">Vehicle:</label><br>
                    <input type="text" id="vehicle_licence" name="vehicle_licence"><br>
                    <select id="vehicle_id" name="vehicle_id"></select><br>

                    <label for="people_id">Person:</label><br>
                    <input type="text" id="people_name" name="people_name"><br>
                    <select id="people_id" name="people_id"></select><br>

                    <label for="incident_report">Incident Report:</label><br>
                    <textarea id="incident_report" name="incident_report" required></textarea><br>

                    <label for="offence_id">Offence:</label><br>
                    <select id="offence_id" name="offence_id">
                        <?php
                            include 'db_connect.php';
                            $sql = "SELECT Offence_ID, Offence_description FROM Offence";
                            $result = $conn -> query($sql);
                            while ($row = $result -> fetch_assoc())
                            {
                                echo "<option value='" . $row['Offence_ID'] . "'>" . $row['Offence_description'] . "</option>";
                            }
                        ?>
                    </select><br>

                    <input type="submit" value="Report Incident">
                </form>
                <?php if (!empty($message)): ?>
                    <div class="confirmation <?= $message_class; ?>">
                        <?= $message; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <script>
            $(document).ready(function(){
                $('#vehicle_licence').on('input', function(){
                    var vehicle_licence = $(this).val();
                    $.ajax({
                        url: 'get_vehicle_id.php',
                        type: 'post',
                        data: {vehicle_licence: vehicle_licence},
                        success: function(response){
                            $('#vehicle_id').html(response);
                        }
                    });
                });

                $('#people_name').on('input', function(){
                    var people_name = $(this).val();
                    $.ajax({
                        url: 'get_people_id.php',
                        type: 'post',
                        data: {people_name: people_name},
                        success: function(response){
                            $('#people_id').html(response);
                        }
                    });
                });
            });
        </script>
    </body>
</html>