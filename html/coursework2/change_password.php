<?php
    include 'db_connect.php';
    include 'audit_log.php';
    session_start();
    include 'navbar.php';

    $message = '';
    $message_class = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $old_password = $_POST["old_password"];
        $new_password = $_POST["new_password"];
        $username = $_SESSION["username"];

        // Query to get the current password from the database
        $sql = "SELECT password, user_id FROM Users WHERE username='$username'";
        $result = $conn -> query($sql);

        if ($result -> num_rows > 0)
        {
            $row = $result -> fetch_assoc();
            if ($old_password == $row["password"])
            {
                // If old password is correct, update password
                $sql = "UPDATE Users SET password='$new_password' WHERE username='$username'";

                if ($conn -> query($sql) === TRUE)
                {
                    // Log the action
                    $logMessage = logAction($_SESSION["user_id"], "update", "Users", $row["user_id"], "Updated password");
                    if (strpos($logMessage, "Error:") !== false)
                    {
                        $message = $logMessage;
                        $message_class = "error";
                    }
                    else
                    {
                        $message = "Password updated successfully. " . $logMessage;
                        $message_class = "success";
                    }
                }
                else
                {
                    $message = "Error updating password: " . $conn -> error;
                    $message_class = "error";
                }
            }
            else
            {
                $message = "Old password is incorrect.";
                $message_class = "error";
            }
        }
        else
        {
            $message = "No user found.";
            $message_class = "error";
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Change Password</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f0f0f0;
            }
            .container {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            .form-container {
                width: 50%;
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
            .form-container input[type="password"] {
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
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    Old Password: <input type="password" name="old_password"><br>
                    New Password: <input type="password" name="new_password"><br>
                    <input type="submit">
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