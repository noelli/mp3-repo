<?php
$dbfile = "admin/getmp3.db";
$db = new SQLite3($dbfile);
$mp3_table = "MP3";
setlocale(LC_TIME, "de_DE");

// get the search parameter from URL
$s=$_GET["s"];

// get the number of entries to display from URL
$limit=10;
if ($_GET["entries"] == "25" ) { $limit = 25; }
if ($_GET["entries"] == "50" ) { $limit = 50; }

if (isset($_GET["page"])) {
    $pageno = $_GET['page'];
    if ($pageno > 1) {
        $limit_start = ($pageno-1) * $limit;
        $limit_end = $limit_start + $limit;
    } else {
        $pageno = 1;
        $limit_start = 0;
        $limit_end = $limit;
    }
} else {
    $pageno = 1;
    $limit_start = 0;
    $limit_end = $limit;
}
$pagination = "";

$results = $db->query("SELECT * from $mp3_table ORDER BY file_date DESC LIMIT $limit_start, $limit_end");
$result_number = $db->querySingle("SELECT count(*) from $mp3_table");
echo($result_number);
$number_of_pages = ceil($result_number / $limit);
if ($number_of_pages > 1){
    if ($pageno > 1) {
        $pagination = $pagination . '<a onclick="paginate(1)">&laquo;</a>';
        $pagination = $pagination . '<a onclick="paginate(' . ($pageno - 1) . ')">' . ($pageno - 1) . '</a>';
    }
    $pagination = $pagination . '<a class="active">'. $pageno .'</a>';
    if ($pageno < $number_of_pages) {
        $pagination = $pagination . '<a onclick="paginate(' . ($pageno + 1) . ')">' . ($pageno + 1) . '</a>';
        $pagination = $pagination . '<a onclick="paginate(' . $number_of_pages . ')">&raquo;</a>';
    }
}



// get results if nothing was searched yet
if ($s == "all") {
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
    // get results of search term
} else if ($s == "search") {
    $q=$_GET["q"];
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
    // ToDo: Implement filters for certain timespans etc.
} else if ($s = "filter") {
    $hint = "error"; //ToDo: Translate
// Error?
} else {
    $hint = "error"; //ToDo: Translate
}


if ($hint=="error") {
    echo("Something messed up!");
} else {
    echo('<table class="u-full-width">
    <thead>
        <tr>
            <th>Download</th><th>Date</th><th>Artist</th><th>Title</th>
        </tr>
    </thead>
    <tbody>');
    echo($hint);
    echo('</tbody>
</table>
<div class="row">
    <div class="pagination">');
    echo($pagination);
    echo('</div>
</div>');
}
?>