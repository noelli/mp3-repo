<?php
$dbfile = "admin/getmp3.db";
$db = new SQLite3($dbfile);
$mp3_table = "MP3";
setlocale(LC_TIME, "de_DE");
/* === XML VERSION: ===
$xmlDoc=new DOMDocument();
$now   = time();
$xmlfile = "public/mp3.xml";
if ($now - filemtime($xmlfile) >= 60 * 5 ) { //5 Minutes
    require  __DIR__ . '/src/indexer.php';
}
$xmlDoc->load($xmlfile);

$mp3list=$xmlDoc->getElementsByTagName('item');
=== === === === === === */

//get the s parameter from URL
$s=$_GET["s"];

if ($s == "all") {
    $results = $db->query("SELECT * from $mp3_table ORDER BY date DESC");
    $hint="";
    while ($row = $results->fetchArray()) { //for($i=0; $i<($mp3list->length); $i++) {
        $date       = DateTime::createFromFormat('Ymd', $row['file_date'])->format('d.m.Y');    //$mp3list->item($i)->getElementsByTagName('album')->item(0);
        $artist   = $row['artist'];   //$mp3list->item($i)->getElementsByTagName('artist')->item(0);
        $title      = $row['title'];    //$mp3list->item($i)->getElementsByTagName('title')->item(0);
        $file       = $row['file_name'];     //$mp3list->item($i)->getElementsByTagName('file')->item(0);
        $hint = $hint . "<tr>".
                            "<td><a class='button' href='public/mp3/".$file."' download><i class='fas fa-download'></i></a></td>".
                            "<td>".$date."</td>".
                            "<td>".$artist."</td>".
                            "<td>".$title."</td>".
                        "</tr>";
    }
} else if ($s == "search") {
    $q=$_GET["q"]; //
    $results = $db->query("SELECT * from $mp3_table ORDER BY date DESC");
    $hint="";
    while ($row = $results->fetchArray()) { //for($i=0; $i<($mp3list->length); $i++) {
        $date       = $row['file_date'] ;// DateTime::createFromFormat('Y-m-d', $row['date'])->format('F j, Y');    //$mp3list->item($i)->getElementsByTagName('album')->item(0);
        $artist   = $row['artist'];   //$mp3list->item($i)->getElementsByTagName('artist')->item(0);
        $title      = $row['title'];    //$mp3list->item($i)->getElementsByTagName('title')->item(0);
        $file       = $row['file_name'];     //$mp3list->item($i)->getElementsByTagName('file')->item(0);
        if (stristr($date, $q) or stristr($artist, $q) or stristr($title, $q) ) {
            $hint = $hint . "<tr>".
                                "<td><a class='button' href='public/mp3/".$file."' download><i class='fas fa-download'></i></a></td>".
                                "<td>".$date."</td>".
                                "<td>".$artist."</td>".
                                "<td>".$title."</td>".
                            "</tr>";
        }
        
    }
} else if ($s = "filter") {
    $hint = "not implemented yet!";
} else {
    $hint = "Something messed up!";
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
            <th>Download</th><th>Date</th><th>Artist</th><th>Title</th>
        </tr>
    </thead>
    <tbody>');
    echo($hint);
    echo("</tbody>
</table>");
}
?>