<?php
  session_start();
  echo json_encode($_SESSION["status"]);
  unset($_SESSION["status"]);
?>