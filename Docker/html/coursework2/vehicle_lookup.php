<?php
    session_start();
    include 'navbar.php';

    $message = '';
    $message_class = '';

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["vehicle_licence"]))
    {
        include 'db_connect.php';

        $vehicle_licence = $_GET["vehicle_licence"];

        $sql = "SELECT Vehicle.Vehicle_type, Vehicle.Vehicle_colour, Vehicle.Vehicle_licence, People.People_name, People.People_licence
                FROM Vehicle
                LEFT JOIN Ownership ON Vehicle.Vehicle_ID = Ownership.Vehicle_ID
                LEFT JOIN People ON Ownership.People_ID = People.People_ID
                WHERE Vehicle.Vehicle_licence = '$vehicle_licence'";
        
        $result = $conn -> query($sql);

        if ($row = $result -> fetch_assoc())
        {
            $owner_name = !empty($row["People_name"]) ? $row["People_name"] : "Unknown";
            $owner_licence = !empty($row["People_licence"]) ? $row["People_licence"] : "Unknown";
            $vehicle_info = array("Vehicle_type" => $row["Vehicle_type"], "Vehicle_colour" => $row["Vehicle_colour"], "Vehicle_licence" => $row["Vehicle_licence"], "Owner_name" => $owner_name, "Owner_licence" => $owner_licence);
        }
        else
        {
            $message = "No vehicle found.";
            $message_class = "error";
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Vehicle Lookup</title>
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
                <h1>Vehicle Lookup</h1>
                <form action="vehicle_lookup.php" method="get">
                    <label for="vehicle_licence">Vehicle Licence:</label><br/>
                    <input type="text" id="vehicle_licence" name="vehicle_licence" required><br/>
                    <input type="submit" value="Lookup">
                </form>
            </div>
            <?php if (!empty($message)): ?>
                <div class="confirmation <?= $message_class ?>">
                    <?= $message; ?>
                </div>
            <?php endif; ?>
            <?php
                if (isset($vehicle_info))
                {
                    echo "<table><tr><th>Vehicle Type</th><th>Vehicle Colour</th><th>Vehicle Plate</th><th>Owner's Name</th><th>Owner's Licence Number</th></tr>";
                    echo "<tr><td>" . $vehicle_info["Vehicle_type"] . "</td><td>" . $vehicle_info["Vehicle_colour"] . "</td><td>" . $vehicle_info["Vehicle_licence"] . "</td><td>" . $vehicle_info["Owner_name"] . "</td><td>" . $vehicle_info["Owner_licence"] . "</td></tr>";
                    echo "</table>";
                }
            ?>
        </div>
    </body>
</html>