<?php
    session_start();
    include 'db_connect.php';

    $username = isset($_SESSION["username"]) ? $_SESSION["username"] : "Guest";  // Check if username is set in session

    // Get recent incidents
    $sql = "SELECT * FROM Incident ORDER BY Incident_Date DESC LIMIT 3";
    $result = $conn -> query($sql);

    $incidents = [];
    if ($result -> num_rows > 0) 
    {
        while ($row = $result -> fetch_assoc()) 
        {
            $incidents[] = $row;
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Police Dashboard</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap">
        <style>
            body {
                font-family: "Roboto", sans-serif;
                margin: 0;
                padding: 0;
                background: #f4f4f4;
            }
            header {
                background: #005a87;
                color: #fff;
                padding: 1rem 0;
                text-align: center;
            }
            .navbar {
                display: flex;
                justify-content: flex-end;
                background-color: #0073b1;
                padding: 0 1rem;
            }
            .navbar a {
                color: #fff;
                padding: 1rem;
                text-decoration: none;
            }
            .navbar a:hover {
                background: #005a87;
            }
            .navbar .profile {
                display: flex;
                align-items: center;
            }
            .navbar img {
                border-radius: 50%;
                margin-right: 0.5rem;
            }
            nav {
                display: flex;
                justify-content: center;
            }
            nav ul{
                display: flex;
                justify-content: space-between;
                width: 100%;
                max-width: 800px;
                list-style: none;
            }
            nav ul li {
                text-align: center;
            }
            main {
                padding: 1rem;
            }
            .card {
                background: #fff;
                border-radius: 8px;
                padding: 1rem;
                margin-bottom: 1rem;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .dropdown {
                margin-left: auto;
                float: right;
                position: relative;
                display: inline-block;
            }

            .dropdown-content {
                display: none;
                position: absolute;
                right: 0;
                top: 100%;
                background-color: #f9f9f9;
                min-width: 160px;
                box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
                z-index: 1;
            }

            .dropdown-content a {
                color: black;
                padding: 12px 16px;
                text-decoration: none;
                display: block;
            }

            .dropdown-content a:hover {background-color: #f1f1f1;}

            .dropdown:hover .dropdown-content {
                display: block;
            }
            .dropbtn {
                display: flex;
                align-items: center;
                color: black;
                text-align: center;
                padding: 5px 16px;
                text-decoration: none;
            }
            .dropbtn img, .dropbtn .username {
                margin: 0;
                padding: 0;
                vertical-align: middle;
            }
        </style>
    </head>

    <body>
        <header>
            <h1>Welcome to the Police Dashboard</h1>
        </header>
        <div class="navbar">
            <nav>
                <ul>
                    <li><a href="search_people.php">Search People</a></li>
                    <li><a href="vehicle_lookup.php">Vehicle Lookup</a></li>
                    <li><a href="add_vehicle.php">Add Vehicle</a></li>
                    <li><a href="report_incident.php">Report Incident</a></li>
                    <li><a href="search_incident.php">Search Incidents</a></li>
                </ul>
            </nav>
            <div class="dropdown">
                <button class="dropbtn">
                    <img src="profilepic.png" alt="Profile Picture" style="height: 40px; width: 40px;">
                    <span><?php echo $username; ?></span>
                </button>
                <div class="dropdown-content">
                    <a href="change_password.php">Change Password</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>
        <main>
            <section class="card">
                <h2>Recent Incidents</h2>
                <?php foreach ($incidents as $incident): ?>
                    <p>
                        <strong>Date:</strong> <?php echo $incident["Incident_Date"]; ?><br>
                        <strong>Incident:</strong> <?php echo $incident["Incident_Report"]; ?>
                    </p>
                <?php endforeach; ?>
            </section>

            <?php
                // Check if the user is an admin
                if ($_SESSION["role"] == "admin")
                {
                    // If user is admin, display links below
                    echo '<section class="card">';
                    echo '<h2>Admin Actions</h2>';
                    echo '<li><a href="create_officer_account.php">Create Officer Account</a></li>';
                    echo '<li><a href="associate_fines.php">Associate Fine to Incident</a></li>';
                    echo '<li><a href="display_audit.php">Audit Trail</a></li>';
                    echo '</section>';
                }
            ?>
            </ul>
        </main>

        <script>
            // JavaScript for dropdown button
            document.addEventListener('DOMContentLoaded', function() {
                // When user clicks on button, toggle between hiding and showing content
                document.querySelector('.dropbtn').onclick = function() {
                    document.querySelector('.dropdown-content').classList.toggle('show');
                }
                
                // Close dropdown if click outside it
                window.onclick = function(event) {
                    if (!event.target.matches('.dropbtn, .dropbtn *')) {
                        var dropdowns = document.getElementsByClashName('dropdown-content');
                        for (var i=0; i < dropdowns.length; i++) {
                            var openDropdown = dropdowns[i];
                            if (openDropdown.classList.contains('show')) {
                                openDropdown.classList.remove('show');
                            }
                        }
                    }
                }
            });
        </script>
    </body>
</html>