<main id="main_posts">
    <ul class="cards_list">
      <?php
        foreach($posts as $i => $post){
          $index = $i + 1 + ($POSTS_PER_PAGE * ($page - 1));
          $post["index"] = $index;
          $cards_list[$index] = $post;
          
          $cardItem = 
          "<li class=\"card_item\">
            <p><samp>№: " . $post["index"] . "</samp></p>
            <p><samp>title: " . $post["title"] . "</samp></p>
            <p><samp>author: " . $post["author"] . "</samp></p>
            <p><samp>edited: " . $post["modifyAt"] . "</samp></p>
            <p><samp>created: " . $post["createAt"] . "</samp></p>
          </li>";
          echo $cardItem;
        }
          
        if(!empty($cards_list))
          $_SESSION["cards_list"] = json_encode($cards_list);
        ?>
    </ul>
    <ul class="pages_list">
      <?php
        $countOfPages = getCountOfPages(getCountOfPublicPosts($dbh), $POSTS_PER_PAGE);
        
        if($countOfPages > 0){
          if($page != 1){
            echo "<li class=\"page_item\"><a class=\"link\" href=\"?page=" . $page - 1 . "\">‹</a></li>";
          } else {
            echo "<li class=\"page_item\"><a class=\"link\" href=\"#\">‹</a></li>";
          }

          for($i = 1; $i <= $countOfPages; $i++){
            if($i != $page){
              echo "<li class=\"page_item\"><a class=\"link\" href=\"?page=$i\">$i</a></li>";
            } else {
              echo "<li class=\"page_item\"><a class=\"link link-selected\" href=\"?page=$i\">$i</a></li>";
            }
          }
          
          if($page != $countOfPages){
            echo "<li class=\"page_item\"><a class=\"link\" href=\"?page=" . $page + 1 . "\">›</a></li>";
          } else {
            echo "<li class=\"page_item\"><a class=\"link\" href=\"#\">›</a></li>";
          }
        }
      ?>
    </ul>
  </main>
