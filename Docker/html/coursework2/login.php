<?php
    session_start();
    include 'db_connect.php';

    $message = '';
    $message_class = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $username = $_POST["username"];
        $password = $_POST["password"];

        $sql = "SELECT * FROM Users WHERE username = '$username' AND password = '$password'";
        $result = $conn -> query($sql);

        if ($result -> num_rows > 0)
        {
            // Login success
            $user = $result -> fetch_assoc();
            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["role"] = $user["role"];
            $_SESSION["username"] = $username;  // Store username in session for dashboard

            // Redirect to another page based on role
            header("Location: dashboard.php");
            exit;
        } 
        else 
        {
            $message =  "Invalid username or password";
            $message_class = "error";
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
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
            .form-container input[type="text"], input[type="password"] {
                width 100%;
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
        </style>
    </head>

    <body>
        <div class="container">
            <div class="form-container">
                <h1>Login</h1>
                <form action="login.php" method="post">
                    <label for="username">Username:</label><br/>
                    <input type="text" id="username" name="username" required><br/>
                    <label for="password">Password:</label><br/>
                    <input type="password" id="password" name="password" required><br/>
                    <input type="submit" value="Login">
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