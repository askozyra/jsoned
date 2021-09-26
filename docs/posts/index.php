<?php
  session_start();

  require("../../general_functions.php");
  require("../post_functions.php");
  require("../../api/connect.php");
  require("../../classes/users.php");
  require("../../classes/user_settings.php");
  
  $data = authentificationProcess($dbh);
  $user = $data["user"];
  $settings = $data["settings"];

  $COOKIE = json_decode(json_encode($_COOKIE));

  $page = 1;
  if(isset($_GET["page"])){
    $page = $_GET["page"];
  }
    
  $POSTS_PER_PAGE = $settings["count_of_docs"];
  $posts = getPostsByPage($dbh, $page, $POSTS_PER_PAGE);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php require("../../elems/head.php"); ?>
  <title>Posts<?php echo " - " . TIT1; ?></title>
</head>
<body>
  <header id="header_posts">
    <?php require("../../elems/menu.php"); ?>
    <?php require("../../elems/posts/header_themes.php"); ?>
  </header>

  <?php require("../../elems/posts/main.php"); ?>
  
  <?php require("../../elems/posts/footer_posts.php"); ?>

  <script>
    const SESSION_CARDS_LIST = <?php echo $_SESSION["cards_list"] ?? "null"; ?>;
    const USER_LOGIN = "<?php echo $_SESSION["login"]; ?>";
  </script>
  <?php require "../../elems/scripts_src.php"; ?>
  <script src="js/docs/posts.js" type="module"></script>
</body>
</html>