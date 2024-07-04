<?php
    session_start();
    include 'navbar.php';
    include 'db_connect.php';
    include 'audit_log.php';

    $message = '';
    $messageClass = '';

    // Check if the user is an administrator
    if ($_SESSION["role"] != "admin")
    {
        die("Access denied: Only administrators can perform this action.");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $role = $_POST["role"];

        $sql = "INSERT INTO Users (username, password, role)
                VALUES ('$username', '$password', '$role')";
                
        if ($conn -> query($sql) === TRUE)
        {
            

            // Log the action
            $logMessage = logAction($_SESSION["user_id"], "create", "Users", $conn -> insert_id, "Created a new officer account");
            if (strpos($logMessage, "Error:") !== false)
            {
                $message = $logMessage;
                $messageClass = "error";
            }
            else
            {
                $message = "New police officer account created successfully. " . $logMessage;
                $messageClass = "success";
            }
        }
        else
        {
            $message =  "Error: " . $conn -> error;
            $messageClass = "error";
        }
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Create Officer Account</title>
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
            .form-container input[type="text"], .form-container input[type="password"] {
                width: 100%;
                padding: 10px;
                margin-bottom: 10px;
                border: 1px solid #ddd;
            }
            .form-container input[type="submit"] {
                padding: 10px, 20px;
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
                <h1>Create Officer Account</h1>
                <form method="POST" action="create_officer_account.php">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        <option value="police">Police</option>
                        <option value="admin">Admin</option>
                    </select>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <input type="submit" value="Create Account">
                </form>
                <?php if (!empty($message)): ?>
                    <div class="confirmation <?= $messageClass; ?>">
                        <?= $message; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>