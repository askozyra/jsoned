<?php
  session_start();
  
  require("../../general_functions.php");
  require_once("../../api/connect.php");
  require("../../classes/users.php");
  require("../../classes/documents.php");
  require("../../classes/user_settings.php");

  $data = authentificationProcess($dbh);
  $user = $data["user"];
  $settings = $data["settings"];

  $COOKIE = json_decode(json_encode($_COOKIE));
  $CARD_INDEX = $_GET["index"];

  $user = Users\getUser($dbh, $_SESSION["login"]);
  $post = Docs\getDocument($dbh, $CARD_INDEX);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php require("../../elems/head.php"); ?>
  <title>Edit<?php echo " - " . $post["title"]; ?></title>
</head>
<body>
  <header id="header_editor">
    <?php require("../../elems/menu.php"); ?>
    <?php require("../../elems/posts/header_themes.php"); ?>
  </header>
  <div id="rope" class="rope-hidden"></div>

  <div class="background_mat"></div>

  <?php
    if(empty($post)) {
      setStatus("Resource not found!", "error");
      Redirect("drafts");
    }

    require("../../elems/editor/main.php");
  ?>
  
  <footer id="footer_editor">
    <button id="save-btn" class="relief-btn">S A V E</button>
  </footer>
  
  <script>
    const SELECTED_CARD = <?php echo json_encode($post); ?>;
    const USER_LOGIN = "<?php echo $_SESSION["login"]; ?>";
  </script>
  <?php require "../../elems/scripts_src.php"; ?>
  <script src="../js/docs/editor.js" type="module"></script>
</body>
</html>