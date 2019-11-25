<?php

require_once('connection.php');

try {
  $results = $db->query('SELECT * FROM entries ORDER BY DATE DESC');
  $entries = $results->fetchAll(PDO::FETCH_ASSOC);
  //print_r($entries);
  //die();
} catch (Exception $e){
  echo $e->getMessage();
  die();
}
include('./inc/header.php');
?>


<section>
  <div class="container">
    <div class="entry-list">
      <?php foreach($entries as $entry) {
      $vardate = explode('-',$entry['date']);
      $year = intval($vardate[0]);
      $month = date('F',mktime(0,0,0,intval($vardate[1])));
      $date = intval($vardate[2]);
      ?>
      <article>
        <h2><a href="detail.php?id=<?= $entry['id']; ?>"><?= $entry['title']; ?></a></h2>
        <time datetime="<?= $entry['date'] ?>"><?= $month; ?> <?= $date ?>, <?= $year; ?></time>
      </article>
      <?php } ?>
    </div>
  </div>
</section>


<?php
include('./inc/footer.php');
?>
