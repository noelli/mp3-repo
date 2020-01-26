<?php
$dbfile = "getmp3.db";
$db = new SQLite3($dbfile);
$mp3_table = "MP3";
$ignore_table= "ignored";

require_once __DIR__ . '/src/init_db.php';

$xmlDoc=new DOMDocument();
$now   = time();
$xmlfile = "../public/mp3.xml";
if ($now - filemtime($xmlfile) >= 60 * 5 ) { //5 Minutes
    require  __DIR__ . '/../src/indexer.php';
}
$xmlDoc->load($xmlfile);

$mp3list=$xmlDoc->getElementsByTagName('item');

$mp3_result = [];
$query = $db->query("SELECT file_name from $mp3_table");
while ($row = $query->fetchArray()){
    array_push($mp3_result, $row['file']);
}
$ignore_result = [];
$query = $db->query("SELECT file_name from $ignore_table");
while ($row = $query->fetchArray()){
    array_push($ignore_result, $row['file']);
}
$hint="";
for($i=0; $i<($mp3list->length); $i++) {
    $album = $mp3list->item($i)->getElementsByTagName('album')->item(0)->nodeValue;
    $artist= $mp3list->item($i)->getElementsByTagName('artist')->item(0)->nodeValue;
    $title = $mp3list->item($i)->getElementsByTagName('title')->item(0)->nodeValue;
    $file  = $mp3list->item($i)->getElementsByTagName('file')->item(0)->nodeValue;
    if (!in_array($file, $mp3_result) && !in_array($file, $ignore_result)){
        $hint = $hint . "<tr>".
                        "<td><input type='checkbox' name='files[]' value='".$file."'></td>".
                        "<td>".$file."</td>".
                        "<td>".$album."</td>".
                        "<td>".$artist."</td>".
                        "<td>".$title."</td>".
                    "</tr>";
    }
}


// Set output to "no suggestion" if no hint was found
// or to the correct values
// Response
if ($hint=="") {
    echo("No new Files");
} else {
    echo('<table class="u-full-width">
    <thead>
        <tr>
            <th>#</th><th>filename</th><th>date</th><th>artist</th><th>title / Text</th>
        </tr>
    </thead>
    <tbody>');
    echo($hint);
    echo("</tbody>
</table>");
}
echo("<p>Generated: ".date("d.m.Y H:i:s", filemtime($xmlfile)."</p>"));
?>
