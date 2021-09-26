<meta charset="UTF-8">
<meta name="csrf-token" content="<?php $CSRF_TOKEN = create_csrf_token(); echo $CSRF_TOKEN; ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/<?php echo ROOT_NAME; ?>/css/style.css">
<link rel="icon" type="image/x-icon" href="/<?php echo ROOT_NAME; ?>/favicon.ico">
<?php
  if(isset($settings)) {
    if($settings["smoothness_of_anims"]) {
      echo "<link rel=\"stylesheet\" href=\"/" . ROOT_NAME . "/css/smoothness.css\">";
    }
  }
?>
