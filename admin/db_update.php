<?php

require_once __DIR__ . '/src/init_db.php';

require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$phase = $_GET['phase'];

$inputFileName = 'uploads/imported.xslx';

echo("Which Phase is it: $phase .");
if (file_exists($inputFileName) && $phase == "match") {
  echo("Match the columns of the Spreadsheet to the Database Columns:");

  $spreadsheet = IOFactory::load($inputFileName);
  $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
  echo('<table class="u-full-width">');
  echo("<tr>
        <th>Excel:");
  foreach ($sheetData[1] as $key2 => $value2) {
    echo("<th>" . $value2 . "</th>");
  }
  echo("</tr><tr><td>Database:</th>");
  foreach ($sheetData[1] as $key2 => $value2) {
    echo("<td><select id=". $key2 . ">");
    foreach ($database_columns as $db_value) {
      echo("<option>" . $db_value . "</option>");
    }
    echo("</select></td>");
  }
  echo("</tr>");
  echo("</table>");
  echo("<button onclick=\"showResult('matched')\">Matched!</button>");
} elseif ($phase == "matched") {
  echo($_GET["query"]);
}

// header("Location: index.html");
// die();
?>
