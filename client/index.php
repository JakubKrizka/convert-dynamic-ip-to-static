<?php
  // create random hash to secure
  $hash_pin = hash("sha512", "Some text to hash");
  // echo $hash_pin;
  // define url to server index.php
  $url = '../server/index.php';
  // set hash to array
  $data = array('pin' => $hash_pin);
  // set up some HTTP variable in header
  $options = array(
    'http' => array(
      'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
      'method'  => 'POST',
      'content' => http_build_query($data),
    )
  );
  // send request with hash 
  $context  = stream_context_create($options);
  // save the result of server output info as $result variable
  $result = file_get_contents($url, false, $context);
  // print $result
  echo $result;
?>