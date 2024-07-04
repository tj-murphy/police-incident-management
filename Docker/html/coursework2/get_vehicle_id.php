<?php
    include 'db_connect.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $vehicle_licence = $_POST["vehicle_licence"];
        $sql = "SELECT Vehicle_ID, Vehicle_licence FROM Vehicle WHERE Vehicle_licence LIKE '$vehicle_licence%'";
        $result = $conn -> query($sql);

        if ($result -> num_rows > 0)
        {
            while ($row = $result -> fetch_assoc())
            {
                echo "<option value='" . $row['Vehicle_ID'] . "'>" . $row['Vehicle_licence'] . "</option>";
            }
        }
        else
        {
            echo "<option>No results found</option>";
        }
    }
?>