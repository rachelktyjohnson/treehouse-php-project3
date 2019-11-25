<?php
//enable error reporting. get rid of this before flight
ini_set( 'display_errors', 'On' );


//try to create PDO object
try {
  $db = new PDO( 'sqlite:./database.db' );
  $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
  var_dump($db);
  die();

} catch (Exception $e) {
  echo "Sorry, the connection was not successful <br>";
  echo $e->getMessage();
  die();
}
