<?php
    include 'db_connect.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $people_name = $_POST["people_name"];
        $sql = "SELECT People_ID, People_name, People_licence FROM People WHERE People_name LIKE '$people_name%';";
        $result = $conn -> query($sql);

        if ($result -> num_rows > 0)
        {
            while ($row = $result -> fetch_assoc())
            {
                echo "<option value='" . $row['People_ID'] . "'>" . $row['People_name'] ." - (".$row['People_licence'].")</option>";
            }
        }
        else
        {
            echo "<option>No results found</option>";
        }
    }
?>