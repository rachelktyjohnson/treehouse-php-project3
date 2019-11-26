<?php

if($_SERVER['REQUEST_METHOD']=="POST"){
  echo "posted!";
  $title = $date = $time_spent = $learned = $resources = $error_message ="";
  $title = filter_input(INPUT_POST,'title',FILTER_SANITIZE_STRING);
  $date = filter_input(INPUT_POST,'date',FILTER_SANITIZE_STRING);
  $time_spent = filter_input(INPUT_POST,'timeSpent',FILTER_SANITIZE_STRING);
  $learned = filter_input(INPUT_POST,'whatILearned',FILTER_SANITIZE_STRING);
  $resources = filter_input(INPUT_POST,'ResourcesToRemember',FILTER_SANITIZE_STRING);

  //function to check if input was empty
  function isFilled($input){
    if ($input==""){
      return false;
    } else {
      return true;
    }
  }

  //if all inputs are filled and pass validation
  if ( isFilled($title) && isFilled($date) && isFilled($time_spent) && isFilled($learned) ){

    //add entry
    include('./connection.php');
    try{
      $results = $db->prepare("INSERT INTO entries(title,date,time_spent,learned,resources) VALUES(?,?,?,?,?)");
      $results->bindParam(1,$title);
      $results->bindParam(2,$date);
      $results->bindParam(3,$time_spent);
      $results->bindParam(4,$learned);
      $results->bindParam(5,$resources);
      $results->execute();

      //get latest ID to help redirect
      $grabredirect = $db->query("SELECT id FROM entries ORDER BY id DESC LIMIT 1");
      $redirect = $grabredirect->fetch(PDO::FETCH_ASSOC);
      header("location:detail.php?id=" . $redirect['id']);
    } catch (Exception $e){
      echo $e-getMessage();
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
      <h2>New Entry</h2>
      <div class="error_message">
        <p><?php echo $error_message; ?></p>
      </div>
      <form method="post">
        <label for="title"> Title</label>
        <input id="title" type="text" name="title" value="<?= $title; ?>"><br>
        <label for="date">Date</label>
        <input id="date" type="date" name="date" value="<?= $date; ?>"><br>
        <label for="time-spent"> Time Spent</label>
        <input id="time-spent" type="text" name="timeSpent" value="<?= $time_spent; ?>"><br>
        <label for="what-i-learned">What I Learned</label>
        <textarea id="what-i-learned" rows="5" name="whatILearned"><?= $learned; ?></textarea>
        <label for="resources-to-remember">Resources to Remember</label>
        <textarea id="resources-to-remember" rows="5" name="ResourcesToRemember"><?= $resources; ?></textarea>
        <input type="submit" value="Publish Entry" class="button">
        <a href="#" class="button button-secondary">Cancel</a>
      </form>
    </div>
  </div>
</section>
<?php
include('./inc/footer.php');
?>
