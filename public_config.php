<?php
  require_once "config.php";

  echo json_encode([
    "title" => TIT1,
    "root" => ROOT_NAME,
    "transfer_protocol" => TRANSFER_PROTOCOL,
    "domain_name" => DOMAIN_NAME
  ]);
?>