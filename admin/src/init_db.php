<?php
$dbfile = "getmp3.db";
$db = new SQLite3($dbfile);
$mp3_table = "MP3";
$ignore_table= "ignored";

$database_columns = [
    "day",
    "month",
    "year",
    "artist",
    "title",
];

$db->exec( "CREATE TABLE IF NOT EXISTS $mp3_table(
    file_name TEXT PRIMARY KEY,
    file_date TEXT,
    artist TEXT,
    title TEXT)");

$db->exec( "CREATE TABLE IF NOT EXISTS $ignore_table(
    file_name TEXT PRIMARY KEY)");
?>