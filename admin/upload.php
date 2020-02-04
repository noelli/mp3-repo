<?php

// ToDo: let the user define which column is to compare to which database-column
// ToDo: Database transactions
// ToDo: update to fit the overall architecture and design (though html and ajax)

require_once __DIR__ . '/../vendor/autoload.php';

if (isset($_POST["import"]))
{     
  $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
  
  if(in_array($_FILES["file"]["type"], $allowedFileType)){

    $inputFileName = 'uploads/imported.xslx';
    move_uploaded_file($_FILES['file']['tmp_name'], $inputFileName);
  }
}

header("Location: index.html");
die();

// $reader->setReadDataOnly(true);