<?php
$dbfile = "admin/getmp3.db";
$db = new SQLite3($dbfile);
$mp3_table = "MP3";
setlocale(LC_TIME, "de_DE");

//get the s parameter from URL
$s=$_GET["s"];
$limit=10;

if ($_GET["entries"] == "25" ) { $limit = 25; }
if ($_GET["entries"] == "50" ) { $limit = 50; }

if ($s == "all") {
    $results = $db->query("SELECT * from $mp3_table ORDER BY file_date DESC LIMIT $limit");
    $hint="";
    while ($row = $results->fetchArray()) {
        $date       = DateTime::createFromFormat('Ymd', $row['file_date'])->format('d.m.Y');
        $artist     = $row['artist'];   
        $title      = $row['title'];
        $file       = $row['file_name'];
        $hint = $hint . "<tr>".
                            "<td><a class='button' href='public/mp3/".$file."' download><i class='fas fa-download'></i></a></td>".
                            "<td>".$date."</td>".
                            "<td>".$artist."</td>".
                            "<td>".$title."</td>".
                        "</tr>";
    }
} else if ($s == "search") {
    $q=$_GET["q"];
    $results = $db->query("SELECT * from $mp3_table ORDER BY file_date DESC LIMIT $limit");
    $hint="";
    while ($row = $results->fetchArray()) { 
        $date       = $row['file_date'] ;
        $artist     = $row['artist'];
        $title      = $row['title'];
        $file       = $row['file_name'];
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
    $hint = "not implemented yet!"; //ToDo: Translate
} else {
    $hint = "Something messed up!"; //ToDo: Translate
}


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