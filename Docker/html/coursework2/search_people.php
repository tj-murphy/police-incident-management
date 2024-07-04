<?php 
    session_start();
    include 'navbar.php';

    $message = '';
    $message_class = '';

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["search"]) && $_GET["search"] != "")
    {
        include 'db_connect.php';

        $search = $_GET["search"];

        $sql = "SELECT * FROM People WHERE People_name LIKE '%$search%' OR People_licence LIKE '%$search%'";
        $result = $conn -> query($sql);

        if ($result -> num_rows > 0)
        {
            $people = array();
            while($row = $result -> fetch_assoc())
            {
                $people[] = $row;
            }
        }   
        else
        {
            $message = "No person found with the given name or licence.";
            $message_class = "error";
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Search People</title>
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

                <h1>Search for People</h1>
                <form action="search_people.php" method="get">
                    <label for="search">Name or Licence Number:</label><br/>
                    <input type="text" id="search" name="search" required><br/>
                    <input type="submit" value="Search">
                </form>
            </div>
            <?php if (!empty($message)): ?>
                    <div class="confirmation <?= $message_class ?>">
                        <?= $message; ?>
                    </div>
                <?php endif; ?>
            <?php
                if (isset($people))
                {
                    echo "<table><tr><th>Name</th><th>Licence</th><th>Address</th></tr>";
                    foreach ($people as $person)
                    {
                        echo "<tr><td>" . $person["People_name"] . "</td><td>" . $person["People_licence"] . "</td><td>" . $person["People_address"] . "</td></tr>";
                    }
                    echo "</table>";
                }
            ?>
        </div>
    </body>
</html>

