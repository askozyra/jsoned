<?php
  $directory = dirname(__DIR__);
  require_once $directory."\\vendor\\autoload.php";
  
  function isDocumentValid($document){
    $validator = new JsonSchema\Validator();

    $file = "schema.json";
    $validator->validate($document, (object)['$ref' => __DIR__ . "\\" . $file]);

    return $validator->isValid();
  }
?>