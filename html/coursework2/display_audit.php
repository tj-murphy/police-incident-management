<?php
    session_start();
    include 'navbar.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Audit Log</title>
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
            tr:hover {background-color: #f5f5f5;}
        </style>
    </head>

    <body>
        <div class="container">
            <div class="form-container">
                <h1>Audit Log</h1>

                <?php
                    include 'audit_log.php';

                    // Check if user is admin
                    if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin")
                    {
                        die("Access denied: Only administrators can view this page.");
                    }
                    
                    displayAuditLog();
                ?>
            </div>
        </div>
    </body>
</html>