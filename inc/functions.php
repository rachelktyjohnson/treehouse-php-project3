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
    return array();
  }
}

//function to get and array-ify tags
function get_tags($entry){
  $tags = [];
  $raw_tags = $entry['tags'];
  $exploded_tags = explode(',',$raw_tags);
  foreach ($exploded_tags as $exploded_tag){
    $tags[] = trim($exploded_tag);
  }
  return $tags;
}
