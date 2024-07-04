Police Incident Management System
-
This repository contains the code, technical documentation, and user manual for the Police Incident Management System. This project was developed to manage police incidents, vehicles, and personnel information efficiently.

Project Overview:
-
The Police Incident Management System is a web-based application designed to streamline the management of police incidents, vehicles, and personnel.
It includes features such as secure user authentication, role-based access control, audit logging, and a user-friendly interface.

Repository Contents:
-
- "technical.pdf" : Technical manual with detailed information about the system architecture, database schema, and development process.
- "UserManual.pdf" : User manual with instructions on how to use the system.
- "Docker.zip" : Compressed folder containing Docker configuration files for running the application in a containerised environment.
- "html" directory : Contains all PHP scripts, SQL files, CSS, and resources required for the system.

HTML Directory Structure:
-
- "index.php" : Main entry point for the application.
- "css/mvp.css" : CSS file for styling the application.
- "coursework2" directory:
    - "add_vehicle.php" : Script to add a new vehicle to the system.
    - "associate_fines.php" : Script to associate fines with incidents.
    - "audit_log.php" : Script to log and display user actions.
    - "change_password.php" : Script to allow users to change their passwords.
    - "coursework2.sql" : SQL file describing the database schema and initial data.
    - "create_officer_account.php" : Script to create new officer accounts.
    - "dashboard.php" : Main dashboard page for users.
    - "db_connect.php" : Script to connect to database.
    - "display_audit.php" : Script to display audit logs.
    - "edit_incident.php" : Script to edit existing incidents.
    - "get_people_id.php" : Script to get people IDs via AJAX.
    - "get_vehicle_id.php" : Script to get vehicle IDs via AJAX.
    - "login.php" : Login page for the system.
    - "logout.php" : Logout script.
    - "navbar.php" : Navigation bar included in other pages.
    - "profilepic.png" : Placeholder profile picture.
    - "report_incident.php" : Script to report new incidents.
    - "search_incident.php" : Script to search for incidents.
    - "search_people.php" : Script to search for people.
    - "vehicle_lookup.php" : Script to look up vehicle information.
 
How to Use:
-
1. Clone the repository.
2. Set up the database - import "coursework2/coursework2.sql" file into your MySQL database.
3. Configure database connection - update "coursework2/db_connect.php" file with your database connection details.
4. Run application - start a local server and navigate to "http://localhost/html/coursework2/login.php" to access login page.

Dependencies:
- 
- Stylesheets: https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap (Roboto Font)
- JavaScript Libraries: https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js (jQuery library to make AJAX requests)

Documentation:
- 
Technical Manual: Detailed information on system architecture, database design, and development can be found in "technical.pdf"
User Manual: Instructions on how to use the system are available in "UserManual.pdf".
