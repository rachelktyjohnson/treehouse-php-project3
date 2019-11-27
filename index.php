<?php
$metaTitle = "Personal Learning Journal";
require_once('./inc/connection.php');
include('./inc/functions.php');

$tag=null;

$pageTitle="All Entries";
if(isset($_GET['tag'])){
  $tag = filter_input(INPUT_GET,'tag',FILTER_SANITIZE_STRING);
  $pageTitle = "Entries tagged '$tag'";
  $metaTitle = "Entries tagged: " . $tag;
}
include('./inc/header.php');
?>


<section>
  <div class="container">
    <h1><?= $pageTitle; ?></h1>
    <hr />
    <div class="entry-list">
      <?php foreach(get_entries_list($tag) as $entry) {
      $vardate = explode('-',$entry['date']);
      $year = intval($vardate[0]);
      $month = date('F',mktime(0,0,0,intval($vardate[1])));
      $date = intval($vardate[2]);
      $tags = get_tags($entry);
      ?>
      <article>
        <h2><a href="detail.php?id=<?= $entry['id']; ?>"><?= $entry['title']; ?></a></h2>
        <time datetime="<?= $entry['date'] ?>"><?= $month; ?> <?= $date ?>, <?= $year; ?></time>
        -
        <span>
          <?php
          $tags_output = [];
            foreach($tags as $tag){
              $tags_output[] = "<a href='index.php?tag=$tag'>$tag</a>";
            }
          echo implode(", ",$tags_output);;
          ?>
        </span>
      </article>
      <?php } ?>
      <div class="pagination">
        <a href="#">&laquo;</a>
        <a href="#" class="active">1</a>
        <a href="#">2</a>
        <a href="#">3</a>
        <a href="#">4</a>
        <a href="#">5</a>
        <a href="#">6</a>
        <a href="#">&raquo;</a>
      </div>
    </div>

  </div>


</section>


<?php
include('./inc/footer.php');
?>
