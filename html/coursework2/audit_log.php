<?php
    include 'db_connect.php';

    function logAction($user_id, $action_type, $table_name, $record_id, $description)
    {
        global $conn;

        $stmt = $conn -> prepare("INSERT INTO Audit_Log (user_id, action_type, table_name, record_id, description)
                                  VALUES (?, ?, ?, ?, ?)");
        $stmt -> bind_param("issss", $user_id, $action_type, $table_name, $record_id, $description);

        if ($stmt -> execute() === TRUE)
        {
            return "New record created successfully in Audit Log.";
        }
        else
        {
            return "Error: " . $stmt . "<br>" . $conn -> error;
        }
    }

    function displayAuditLog()
    {
        global $conn;

        $sql = "SELECT * FROM Audit_Log ORDER BY timestamp DESC";
        $result = $conn -> query($sql);

        if ($result -> num_rows > 0)
        {
            echo "<table>";
            echo "<tr><th>User ID</th><th>Action Type</th><th>Table Name</th><th>Record ID</th><th>Description</th><th>Timestamp</th></tr>";

            // Output data of each row
            while ($row = $result -> fetch_assoc())
            {
                echo "<tr><td>" . $row["user_id"] . "</td><td>" . $row["action_type"] . "</td><td>" . $row["table_name"] . "</td><td>" . $row["record_id"] . "</td><td>" . $row["description"] . "</td><td>" . $row["timestamp"] . "</td></tr>";
            }
            echo "</table>";
        }
        else
        {
            echo "No actions found in the audit log.";
        }
    }
?>