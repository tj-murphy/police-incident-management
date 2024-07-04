<?php
    session_start();
    include 'navbar.php';

    $message = '';
    $message_class = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["incident_id"]) && $_POST["incident_id"] != "")
    {
        include 'db_connect.php';

        $incident_id = $_POST["incident_id"];
        $sql = "SELECT Incident.Incident_ID, Vehicle.Vehicle_licence, People.People_licence, Incident.Incident_Date, Incident.Incident_Report, Offence.Offence_description, Fines.Fine_Amount, Fines.Fine_Points
                FROM Incident
                INNER JOIN Vehicle ON Incident.Vehicle_ID = Vehicle.Vehicle_ID
                INNER JOIN People ON Incident.People_ID = People.People_ID
                INNER JOIN Offence ON Incident.Offence_ID = Offence.Offence_ID
                LEFT JOIN Fines ON Incident.Incident_ID = Fines.Incident_ID
                WHERE Incident.Incident_ID = '$incident_id';";
        $result = $conn -> query($sql);

        if ($result -> num_rows > 0)
        {
            while ($row = $result -> fetch_assoc())
            {
                $incidents[] = $row;
            }
        }
        else
        {
            $message = "No incident found with the given ID.";
            $message_class = "error";
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Search Incident</title>
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
            .form-container label{
                display: block;
                margin-bottom: 5px;
            }
            .form-container input[type="text"] {
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
            .confirmation.error {
                background-color: #f44336;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            th, td {
                border: 1px solid #ddd;
                padding: 10px;
                text-align: left;
            }
            th {
                background-color: #007BFF;
                color: #fff;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="form-container">
                <h1>Search Incident</h1>
                <form method="POST" action="">
                    <label for="incident_id">Incident ID:</label><br>
                    <input type="text" id="incident_id" name="incident_id" required><br>
                    <input type="submit" value="Search">
                </form>
            </div>
            <?php if (!empty($message)): ?>
                <div class="confirmation <?= $message_class ?>">
                    <?= $message; ?>
                </div>
            <?php endif; ?>
            <?php
                if (isset($incidents))
                {
                    echo "<table><tr><th>Incident ID</th><th>Vehicle Reg</th><th>Driver Licence</th><th>Incident Date</th><th>Incident Report</th><th>Offence Description</th><th>Fine Amount</th><th>Fine Points</th></tr>";
                    foreach ($incidents as $incident)
                    {
                        $fine_amount = $incident['Fine_Amount'] !== NULL ? $incident['Fine_Amount'] : "None";
                        $fine_points = $incident['Fine_Points'] !== NULL ? $incident['Fine_Points'] : "None";
                        echo "<tr><td>" . $incident['Incident_ID'] . "</td><td>" . $incident['Vehicle_licence'] . "</td><td>" . $incident['People_licence'] . "</td><td>" . $incident['Incident_Date'] . "</td><td>" . $incident['Incident_Report'] . "</td><td>" . $incident['Offence_description'] . "</td><td>" . $fine_amount . "</td><td>" . $fine_points . "</td><td><a href='edit_incident.php?id=" . $incident['Incident_ID'] . "'>Edit</a></td></tr>";
                    }
                    echo "</table>";
                }
            ?>
        </div>
    </body>
</html>


