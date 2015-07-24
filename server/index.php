<?php
// receive pin from POST request
$receive_pin=$_POST["pin"];
// define hash of pin from client, this is stable variable, check if is same, then continue, you can view your hash by:
// $hash_pin = hash("sha512", "Some text to hash");
// echo $hash_pin;
$pin = '96fa772f72678c85bbd5d23b66d51d50f8f9824a0aba0ded624ab61fe8b602bf4e3611075fe13595d3e74c63c59f7d79241acc97888e9a7a5c791159c85c3ccd';
// check if $receive_pin is same as $pin
if ($receive_pin == $pin) {
  // write ip adress of client to variable
  $ip_of_home =  $_SERVER["REMOTE_ADDR"];
  // set database connect info
  $sql_servername = "hostname";
  $sql_username = "user";
  $sql_password = "pass";
  $sql_dbname = "database";
  $sql_table = "table";
  // create sql connection
  $conn = new mysqli($sql_servername, $sql_username, $sql_password, $sql_dbname);
  // check connection, if error show me log
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error . "\n");
  }
  // set utf8, you can define your language
  if (!$conn->set_charset("utf8")) {
      printf("Error loading character set utf8: %s\n", $conn->error . "\n");
  }
  // load last row for check if change
  $sql = "SELECT * FROM $sql_table order by id desc limit 1";
  // execute read sql command
  $result = $conn->query($sql);
  // fetch row
  $row = $result->fetch_row();
  // define last ip address
  $ip_address_last = $row[1];
  // check if is same
  if ($ip_address_last == $ip_of_home) {
    echo "IP address don't change, latest IP: " . $ip_of_home;
  } else {
    // define sql write command
    $sql_write = "INSERT INTO $sql_table (ip_address) VALUES ('$ip_of_home')";
    // write sql to the database
    if ($conn->query($sql_write) === TRUE) {
      // set up now date
      $date_now = date('Y-m-d H:i:s');
      // print ok for log in crontab
      echo "OK | address: " . $ip_of_home . " | time: " . $date_now . "\n";
      // set up email variables
      $email_from = "ip_change";
      $email_to = "send@email.here";
      // define hostname
      $hostname = gethostbyaddr($ip_of_home);
      // define header to email, show mess like html page
      $headers .= "MIME-Version: 1.0\r\n";
      $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
      // subject
      $subject = "Automatic email, when IP is changed";
      // set up template of message
      $mess = "<html><body>";
      $mess .= "<h1>NEW IP: $ip_of_home</h1>";
      $mess .= "<h2>Time: $date_now</h2>";
      $mess .= "<p>HostName: $hostname</p>";
      $mess .= "<hr>";
      $mess .= '</body></html>';
      // send email
      $send = mail ($email_to, $subject, $mess, $headers);
      // check if send ok
      if($send) {
          echo "email send\n";
      }
      // if send failed
      else {
          echo "email failed\n";
      }
    } else {
      // write info about error
      echo "FAIL | " . $sql_write . ":" . $conn->error . "\n";
    }
  }
  // close connection
  $conn->close();
} else {
  // if pin isn't correct 
  ?>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
    <style>
      body {
        text-align: center;
        font-size: 33px;
        color: red;
      }
    </style>
  </head>
  <body>
    <h1>PIN don't match with HASH</h1>
  </body>
</html>
    <?php
  }
?>