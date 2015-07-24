<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            text-align: center;
        }
        input, button {
            font-size: 21px;
        }
        h1 {
            color: red;
        }
        th {padding: 3px; }
        td {border: 1px solid; padding: 3px; }
        .center {width:100%; text-align: center }
        table {margin: 0 auto;}
    </style>
</head>
<body>
<?php
if (isset($_POST["database_ip"]) & isset($_POST["database_user"]) & isset($_POST["database_pass"]) & isset($_POST["database_database"])) {
    $database_ip = $_POST["database_ip"];
    $database_user = $_POST["database_user"];
    $database_pass = $_POST["database_pass"];
    $database_database = $_POST["database_database"];
    $database_table = $_POST["database_table"];
    // create sql connection
    $conn = new mysqli($database_ip, $database_user, $database_pass, $database_database);
    // check connection, if error show me log
    if ($conn->connect_error) {
        printf("<h1>Connection failed: </h1><h3>" . $conn->connect_error . "</h3><hr>");
    } else {
        // set utf8, you can define your language
        if (!$conn->set_charset("utf8")) {
            printf("<h1>Error loading character set utf8: %s</h1><h3>", $conn->error . "</h3><hr>");
        } else {
            // load last row for check if change
            $sql_write = "CREATE TABLE $database_database.$database_table ( `id` INT NOT NULL AUTO_INCREMENT , `ip_address` TEXT NOT NULL , `date` TIMESTAMP NOT NULL , PRIMARY KEY (`id`) ) ENGINE = InnoDB;";
            // write sql to the database
            if ($conn->query($sql_write) === TRUE) {
                // print ok and table of info
                echo "<h1 style='color: green;'>Table was correctly created.</h1><hr>";
                echo "Copy this information into the app in index.php<br><br>";
                echo "<div class='center'><table><thead><tr>";
                echo "<th>variable_name</th><th>value</th></tr></thead>";
                echo "<tbody>";
                echo "<tr><td><b>sql_servername=</b></td><td>{$database_ip}</td></tr>";
                echo "<tr><td><b>sql_username=</b></td><td>{$database_user}</td></tr>";
                echo "<tr><td><b>sql_password=</b></td><td>{$database_pass}</td></tr>";
                echo "<tr><td><b>sql_dbname=</b></td><td>{$database_database}</td></tr>";
                echo "<tr><td><b>sql_table=</b></td><td>{$database_table}</td></tr>";
                echo "</tbody></table></div><hr>";
            } else {
                // write info about error
                echo $sql_write . "<h1>" . $conn->error . "</h1><hr>";
            }
            // close connection
            $conn->close();
        }
    }
}
?>
<form method="post">
    <input type="text" name="database_ip" placeholder="IP address" required/><br>
    <input type="text" name="database_user" placeholder="User name" required/><br>
    <input type="password" name="database_pass" placeholder="Password"/><br>
    <input type="text" name="database_database" placeholder="Database name" required/><br>
    <input type="text" name="database_table" placeholder="Table name" required/><br>
    <button type="send">Create</button>
</form>
</body>
</html>