<?php
$dbfile = "getmp3.db";
$db = new SQLite3($dbfile);
$mp3_table = "MP3";
$ignore_table= "ignored";

require_once __DIR__ . '/src/init_db.php';

$xmlDoc=new DOMDocument();
$xmlfile = "../public/mp3.xml";
$xmlDoc->load($xmlfile);
$mp3list=$xmlDoc->getElementsByTagName('item');

if(!empty($_POST['files'])) {
    if($_POST['action'] == 'add'){
        for($i=0; $i<($mp3list->length); $i++) {
            $album = $mp3list->item($i)->getElementsByTagName('album')->item(0)->nodeValue;
            $date =  DateTime::createFromFormat('d.m.Y', $album)->format('Ymd');
            $artist= $mp3list->item($i)->getElementsByTagName('artist')->item(0)->nodeValue;
            $title = $mp3list->item($i)->getElementsByTagName('title')->item(0)->nodeValue;
            $file  = $mp3list->item($i)->getElementsByTagName('file')->item(0)->nodeValue;
            if (in_array($file, $_POST['files'])) {
                $db->exec("INSERT INTO $mp3_table (file_name, file_date,  artist,    title)
                                           VALUES ('$file',   '$date',  '$artist',  '$title')");
            }
        }
    } else if ($_POST['action'] == 'ignore') {
        foreach($_POST['files'] as $file){
            $db->exec("INSERT INTO $ignore_table (file_name)
                                   VALUES ('$file')");
        }
    }
}

header("Location: index.html");
die();
?>
