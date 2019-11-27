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

//pagination stuff
if (isset($_GET['pg'])){
  $page = filter_input(INPUT_GET,'pg',FILTER_SANITIZE_NUMBER_INT);
} else {
  $page = 1;
}

$limit = 5;
$offset = ($page-1)*5;
$totalPages = ceil(count(get_all_entries()) / $limit);

include('./inc/header.php');
?>


<section>
  <div class="container">
    <h1><?= $pageTitle; ?></h1>
    <hr />
    <div class="entry-list">
      <?php

      foreach(get_entries_list($tag, $limit, $offset) as $entry) {
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
        <?php
          for($i=1; $i<=$totalPages; $i++){
            echo "<a href='index.php?pg=$i'";
              if ($i==$page){
                echo " class='active' ";
              }
            echo ">$i</a>";
          }
        ?>
      </div>
    </div>

  </div>


</section>


<?php
include('./inc/footer.php');
?>
