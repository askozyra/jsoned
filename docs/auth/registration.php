<?php
  session_start();

  require("../../general_functions.php");
  require_once("../../api/connect.php");

  if(isLogined($dbh))
    Redirect("me");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php require("../../elems/head.php"); ?>
  <title>Registration</title>
</head>
<body>
  <header></header>

  <main class="authentication">
    <form class="auth" method="POST" action="functions/register">
      <input name="csrf_token" value="<?php echo create_csrf_token(); ?>" type="hidden" required>
      <input class="flat_input" name="login" placeholder="login" type="text" required>
      <input class="flat_input" name="password" placeholder="password" type="password" autocomplete required>
      <input class="flat_input" value="Register" type="submit">
    </form>
    <span class="auth_offer">Already have account? <a href="login">Log In!</a></span>
  </main>
  
  <footer></footer>
  <script src="js/elems/messages.js"></script>
</body>
</html>