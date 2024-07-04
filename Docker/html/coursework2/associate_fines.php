<?php
    session_start();
    include 'navbar.php';
    include 'db_connect.php';
    include 'audit_log.php';

    $message = '';
    $message_class = '';

    // Check if user is an administrator
    if ($_SESSION["role"] != "admin")
    {
        die("Access denied: Only administrators can perform this action.");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $incident_id = $_POST["incident_id"];
        $fine_amount = $_POST["fine_amount"];
        $fine_points = $_POST["fine_points"];

        // Check if incident ID exists
        $sql = "SELECT Incident_ID FROM Incident WHERE Incident_ID = '$incident_id';";
        $result = $conn -> query($sql);

        if ($result -> num_rows == 0)
        {
            $message = "Error: Incident ID not found.";
            $message_class = "error";
        }
        else
        {
            $sql = "INSERT INTO Fines (Fine_Amount, Fine_Points, Incident_ID)
            VALUES ('$fine_amount', '$fine_points', '$incident_id')";

            if ($conn -> query($sql) === TRUE)
            {
                // Log the action
                $log_message = logAction($_SESSION["user_id"], "create", "Fines", $conn -> insert_id, "Associated a fine with an incident");
                if (strpos($log_message, "Error:") !== false)
                {
                    $message = $log_message;
                    $message_class = "error";
                }
                else
                {
                    $message = "Fine associated with the incident successfully.";
                    $message_class = "success";
                }
            }
            else
            {
                $message = "Error: " . $conn -> error;
                $message_class = "error";
            }
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Associate Fines</title>
        <style>
            body {
                font-family:Arial, sans-serif;
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
            .form-container input[type="text"], .form-container input[type="number"] {
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
    </head>

    <body>
        <div class="container">
            <div class="form-container">
                <h1>Associate Fines</h1>
                <form method="POST" action="associate_fines.php">
                    <label for="incident_id">Incident ID:</label>
                    <input type="text" id="incident_id" name="incident_id" required>
                    <label for="fine_amount">Fine Amount:</label>
                    <input type="numer" id="fine_amount" name="fine_amount" required>
                    <label for="fine_points">Fine Points:</label>
                    <input type="number" id="fine_points" name="fine_points" required>
                    <input type="submit" value="Associate Fine">
                </form>
                <?php if (!empty($message)): ?>
                    <div class="confirmation <?= $message_class ?>">
                        <?= $message; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>