<?php

require_once('./inc/connection.php');
include('./inc/functions.php');

if (isset($_POST['delete'])){ //this contains the ID to be deleted
  try {
    $idtodelete = $_POST['delete'];
    $result = $db->prepare('DELETE FROM entries WHERE id=?');
    $result->bindParam(1,$idtodelete,PDO::PARAM_INT);

    $result->execute();
    header("Location:index.php");
  } catch (Exception $e){
    echo $e->getMessage();
  }
}

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
        $tags = get_tags($entry);
        ?>
        <time datetime="<?= $entry['date'] ?>"><?= $month; ?> <?= $date ?>, <?= $year; ?></time>
        -
        <span>
          <?php
          $tags = get_tags($entry);
          $tags_output = [];
            foreach($tags as $tag){
              $tags_output[] = "<a href='index.php?tag=$tag'>$tag</a>";
            }
          echo implode(", ",$tags_output);;
          ?>
        </span>
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
    <p><a href="entry.php?id=<?= $id ?>">Edit Entry</a></p>
    <form method="post" onsubmit="return confirm('Do you really want to delete this entry?');">
      <input type="hidden" value="<?= $id;?>" name="delete" />
      <input id="delete-entry" type="submit" value="delete entry" />
    </form>
  </div>

</section>

<?php
include('./inc/footer.php');
?>
