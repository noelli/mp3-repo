<?php

// ToDo: let the user define which column is to compare to which database-column
// ToDo: Database transactions
// Todo: update to fit the overall architecture and design (though html and ajax)

require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_POST["import"]))
{     
  $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
  
  if(in_array($_FILES["file"]["type"], $allowedFileType)){

    $inputFileName = 'uploads/imported.xslx';
    move_uploaded_file($_FILES['file']['tmp_name'], $inputFileName);

    $spreadsheet = IOFactory::load($inputFileName);
    $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
    echo("<table>");
    foreach ($sheetData as $key => $value) {
      echo("<tr>");
      foreach ($value as $key2 => $value2) {
        echo("<td>" . $value2. "</td>");
      }
      echo("</tr>");
    }
    echo("</table>");
  }
}

// $reader->setReadDataOnly(true);