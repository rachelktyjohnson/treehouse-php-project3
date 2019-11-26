<?php

require_once('connection.php');

if (isset($_GET['id'])){
  $id=filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
}

try {
  $results = $db->prepare('SELECT * FROM entries WHERE id=?');
  $results->bindParam(1,$id,PDO::PARAM_INT);
  $results->execute();

  $entry = $results->fetch(PDO::FETCH_ASSOC);
  if (empty($entry)){
    header("location:index.php");
  }
  //print_r($entry);
  //die();
} catch (Exception $e){
  echo $e->getMessage();
  die();
}
include('./inc/header.php');

?>

<section>
  <div class="container">
    <div class="entry-list single">
      <article>
        <h1><?= $entry['title']; ?></h1>
        <?php
        $vardate = explode('-',$entry['date']);
        $year = intval($vardate[0]);
        $month = date('F',mktime(0,0,0,intval($vardate[1])));
        $date = intval($vardate[2]);
        ?>
        <time datetime="<?= $entry['date'] ?>"><?= $month; ?> <?= $date ?>, <?= $year; ?></time>
        <div class="entry">
          <h3>Time Spent: </h3>
          <p><?= $entry['time_spent'] ?></p>
        </div>
        <div class="entry">
          <h3>What I Learned:</h3>
          <p><?= $entry['learned'] ?></p>
        </div>
        <div class="entry">
          <h3>Resources to Remember:</h3>
          <p>
          <?php
          if (!empty($entry['resources'])){
            echo $entry['resources'];
          } else {
            echo "None";
          }
          ?>
          </p>
        </div>
      </article>
    </div>
  </div>
  <div class="edit">
    <p><a href="edit.php?id=<?= $id ?>">Edit Entry</a></p>
    <p><a>Delete Entry</a></p>
  </div>
</section>

<?php
include('./inc/footer.php');
?>
