<ul class="color_themes_list">
  <?php
    if(isset($COOKIE->theme_index)){
      for($i = 0; $i < 21; $i++){
        $tmpItem = "<li class=\"color_theme_item";
        
        if($i == $COOKIE->theme_index)
          $tmpItem .= " color_theme_item-active";
        
        $tmpItem .= "\"></li>";
        
        echo $tmpItem;
      }
    } else {
      for($i = 0; $i < 20; $i++){
        $tmpItem = "<li class=\"color_theme_item\"></li>";
        
        echo $tmpItem;
      }
      echo "<li class=\"color_theme_item color_theme_item-active\"></li>";
    }
  ?>
</ul>
