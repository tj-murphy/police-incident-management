<?php
    $username = isset($_SESSION["username"]) ? $_SESSION["username"] : "Guest";  // Check if username is set in session
?>
<!DOCTYPE html>
<html>
    <head>
        <style>
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
        <div class="navbar">
            <nav>
                <ul>
                    <li><a href="dashboard.php">Home</a></li>
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
    </body>
</html>