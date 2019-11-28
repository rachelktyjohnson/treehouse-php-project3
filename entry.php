<?php
$metaTitle = "Add new entry";
include('./inc/connection.php');
include('./inc/functions.php');
$id = $title = $date = $time_spent = $learned = $resources = $tags = $error_message ="";

//if this is an update
if (isset($_GET['id'])){
  $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
  $results = $db->prepare('SELECT * FROM entries WHERE id=?');
  $results->bindParam(1, $id,PDO::PARAM_INT);
  $results->execute();

  $entry = $results->fetch(PDO::FETCH_ASSOC);
  //print_r($entry);
  $title=$entry['title'];
  $date=$entry['date'];
  $time_spent=$entry['time_spent'];
  $learned=$entry['learned'];
  $tags=$entry['tags'];
  $resources=$entry['resources'];
  $metaTitle = "Updating entry: " . $entry['title'];
}

if($_SERVER['REQUEST_METHOD']=="POST"){
  $title = filter_input(INPUT_POST,'title',FILTER_SANITIZE_STRING);
  $date = filter_input(INPUT_POST,'date',FILTER_SANITIZE_STRING);
  $time_spent = filter_input(INPUT_POST,'timeSpent',FILTER_SANITIZE_STRING);
  $learned = filter_input(INPUT_POST,'whatILearned',FILTER_SANITIZE_STRING);
  $tags = filter_input(INPUT_POST,'tags',FILTER_SANITIZE_STRING);
  $resources = filter_input(INPUT_POST,'ResourcesToRemember',FILTER_SANITIZE_STRING);

  //if all inputs are filled and pass validation
  if ( isFilled($title) && isFilled($date) && isFilled($time_spent) && isFilled($learned) && isFilled($tags)){

    //add entry
    try{
      if (empty($id)){
        $sql = "INSERT INTO entries(title,date,time_spent,learned,resources, tags) VALUES(?,?,?,?,?,?)";
      } else {
        $sql = "UPDATE entries SET title=?, date=?, time_spent=?, learned=?, resources=?, tags=? WHERE id=?";
      }

      $results = $db->prepare($sql);
      $results->bindParam(1,$title,PDO::PARAM_STR);
      $results->bindParam(2,$date,PDO::PARAM_STR);
      $results->bindParam(3,$time_spent,PDO::PARAM_STR);
      $results->bindParam(4,$learned,PDO::PARAM_STR);
      $results->bindParam(5,$resources,PDO::PARAM_STR);
      $results->bindParam(6,$tags,PDO::PARAM_STR);
      if (!empty($id)){
        $results->bindParam(7,$id,PDO::PARAM_INT);
      }
      $results->execute();

      if (empty($id)){
        //get latest ID to help redirect
        $grabredirect = $db->query("SELECT id FROM entries ORDER BY id DESC LIMIT 1");
        $redirect = $grabredirect->fetch(PDO::FETCH_ASSOC);
        header("location:detail.php?id=" . $redirect['id']);
      } else {
        header("location:detail.php?id=$id");
      }

    } catch (Exception $e){
      echo $e->getMessage();
    }
  } else {
    $error_message = "Please fill out all required fields";
  }

}

include('./inc/header.php');
?>
<section>
  <div class="container">
    <div class="entry">
      <h2><?php if(empty($id)){echo "New";} else {echo "Edit";} ?> Entry</h2>
      <div class="error_message">
        <p><?php echo $error_message; ?></p>
      </div>
      <form method="post">
        <label for="title"> Title <em>(Required)</em></label>
        <input id="title" type="text" name="title" value="<?= $title; ?>"><br>
        <label for="date">Date <em>(Required)</em></label>
        <input id="date" type="date" name="date" value="<?= $date; ?>"><br>
        <label for="time-spent">Time Spent <em>(Required)</em></label>
        <input id="time-spent" type="text" name="timeSpent" value="<?= $time_spent; ?>"><br>
        <label for="what-i-learned">What I Learned <em>(Required)</em></label>
        <textarea id="what-i-learned" rows="5" name="whatILearned"><?= $learned; ?></textarea>
        <label for="tags">Tags <em>(Required. Seperated by a comma)</em></label>
        <input id="tags" type="text" name="tags" value="<?= $tags; ?>"><br>
        <label for="resources-to-remember">Resources to Remember <em>(Optional, but suggested!)</em></label>
        <textarea id="resources-to-remember" rows="5" name="ResourcesToRemember"><?= $resources; ?></textarea>
        <input type="hidden" name="id" value="<?= $id?>" />
        <input type="submit" value="<?php if(empty($id)){echo "Publish";} else {echo "Save";} ?> Entry" class="button">
        <a href="#" class="button button-secondary">Cancel</a>
      </form>
    </div>
  </div>
</section>
<?php
include('./inc/footer.php');
?>
