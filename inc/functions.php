<?php
//function to check if input was empty
function isFilled($input){
  if ($input==""){
    return false;
  } else {
    return true;
  }
}

//function to return index.php article content
function get_entries_list($tag=null){
  include("connection.php");
  $results = [];
  try {
    $sql = "SELECT * FROM entries";
    if (isset($tag)){
      $sql .= " WHERE tags LIKE '%$tag%'";
    }
    $sql .= " ORDER BY date DESC";
    $results = $db->prepare($sql);

    $results->execute();
    $entries = $results->fetchAll(PDO::FETCH_ASSOC);
    //print_r($entries);
    //die();
    return $entries;
  } catch (Exception $e){
    echo $e->getMessage();
    die();
  }
}
