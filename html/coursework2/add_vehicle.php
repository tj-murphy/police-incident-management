<?php
    session_start();
    include 'db_connect.php';
    include 'audit_log.php';
    include 'navbar.php';

    $ownership_message = '';
    $vehicle_message = '';
    $person_message = '';
    $ownership_message_class = '';
    $vehicle_message_class = '';
    $person_message_class = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // Retrieve data from POST
        $vehicle_type = $_POST['vehicle_make'] . ' ' . $_POST['vehicle_model'];
        $vehicle_colour = $_POST['vehicle_colour'];
        $vehicle_licence = $_POST["vehicle_licence"];
        $owner_name = $_POST["owner_name"];
        $owner_address = $_POST["owner_address"];
        $owner_licence = $_POST["owner_licence"];
        
        // Check if owner already exists
        $sql = "SELECT * FROM People WHERE People_name = '$owner_name' AND People_address = '$owner_address' AND People_licence = '$owner_licence'";
        $result = $conn -> query($sql);

        if ($result -> num_rows > 0)
        {
            // Owner exists, assign them to the vehicle
            $owner_id = $result -> fetch_assoc()["People_ID"];
        }
        else
        {
            // Owner does not exist, add them to database
            $sql = "INSERT INTO People (People_name, People_address, People_licence) VALUES ('$owner_name', '$owner_address', '$owner_licence')";
            $conn -> query($sql);
            $owner_id = $conn -> insert_id;

            // Log the action
            if (isset($_SESSION["user_id"]))
            {
                $log_message = logAction($_SESSION["user_id"], "create", "People", $owner_id, "Created a new person");
                if (strpos($log_message, "Error:") !== false)
                {
                    $person_message = $log_message;
                    $person_message_class = "error";
                }
                else
                {
                    $person_message = "New person added successfully" . $log_message;
                    $person_message_class = "success";
                }
            }
            else
            {
                $person_message = "Error: User is not logged in.";
                $person_message_class = "error";
            }
        }

        // Insert vehicle
        $sql = "INSERT INTO Vehicle (Vehicle_type, Vehicle_colour, Vehicle_licence) VALUES ('$vehicle_type', '$vehicle_colour', '$vehicle_licence')";
        $conn -> query($sql);
        $vehicle_id = $conn -> insert_id;

        // Log the action
        if (isset($_SESSION["user_id"]))
        {
            $log_message = logAction($_SESSION["user_id"], "create", "Vehicle", $vehicle_id, "Created a new vehicle");
            if (strpos($log_message, "Error:") !== false)
            {
                $vehicle_message = $log_message;
                $vehicle_message_class = "error";
            }
            else
            {
                $vehicle_message = "New vehicle added successfully" . $log_message;
                $vehicle_message_class = "success";
            }
        }
        else
        {
            $vehicle_message = "Error: User is not logged in.";
            $vehicle_message_class = "error";
        }

        // Insert ownership
        $sql = "INSERT INTO Ownership (People_ID, Vehicle_ID) VALUES ('$owner_id', '$vehicle_id')";

        if ($conn -> query($sql) === TRUE)
        {
            // Log the action
            if (isset($_SESSION["user_id"]))
            {
                $log_message = logAction($_SESSION["user_id"], "create", "Ownership", $conn -> insert_id, "Created a new ownership");
                if (strpos($log_message, "Error:") !== false)
                {
                    $ownership_message = $log_message;
                    $ownership_message_class = "error";
                }
                else
                {
                    $ownership_message = "New ownership added successfully" . $log_message;
                    $ownership_message_class = "success";
                }
            }
            else
            {
                $ownership_message = "Error: User is not logged in.";
                $ownership_message_class = "error";
            }
        }
        else
        {
            $ownership_message = "Error: " . $sql . "<br/>" . $conn -> error;
            $ownership_message_class = "error";
        }
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Add Vehicle</title>
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
            .form-container input[type="text"], .form-container input[type="submit"] {
                width: 100%;
                padding: 10px;
                margin-bottom: 10px;
                border: 1px solid #ddd;
            }
            .form-container input[type="submit"] {
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
    </head>

    <body>
        <div class="container">
            <div class="form-container">
                <h1>Add Vehicle</h1>
                <form action="add_vehicle.php" method="post">
                    <label for="vehicle_licence">Vehicle Reg:</label><br/>
                    <input type="text" id="vehicle_licence" name="vehicle_licence"><br/>
                    <label for="vehicle_make">Vehicle Make:</label><br/>
                    <input type="text" id="vehicle_make" name="vehicle_make"><br/>
                    <label for="vehicle_model">Vehicle Model:</label><br/>
                    <input type="text" id="vehicle_model" name="vehicle_model"><br/>
                    <label for="vehicle_colour">Vehicle Colour:</label><br/>
                    <input type="text" id="vehicle_colour" name="vehicle_colour"><br/><hr>

                    <label for="owner_name">Owner Name:</label><br/>
                    <input type="text" id="owner_name" name="owner_name"><br/>
                    <label for="owner_address">Owner Address:</label><br/>
                    <input type="text" id="owner_address" name="owner_address"><br/>
                    <label for="owner_licence">Owner Licence:</label><br/>
                    <input type="text" id="owner_licence" name="owner_licence"><br/>
                    
                    <input type="submit" value="Add Vehicle">
                </form>
                <?php if (!empty($person_message)): ?>
                    <div class="confirmation <?= $person_message_class; ?>">
                        <?= $person_message; ?>
                    </div>
                <?php endif; ?><br>
                <?php if (!empty($vehicle_message)): ?>
                    <div class="confirmation <?= $vehicle_message_class; ?>">
                        <?= $vehicle_message; ?>
                    </div>
                <?php endif; ?><br>
                <?php if (!empty($ownership_message)): ?>
                    <div class="confirmation <?= $ownership_message_class; ?>">
                        <?= $ownership_message; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>