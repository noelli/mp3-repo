<?php
    $wwwroot = '/var/www';
    $path = ''; // $wwwroot.'/public/mp3/';
    $xmlpath = $wwwroot.'/public/mp3.xml';

    require_once __DIR__ . '/ID3Parser/getID3/getid3.php';
    require_once __DIR__ . '/ID3Parser/getID3/getid3_exception.php';
    require_once __DIR__ . '/ID3Parser/getID3/getid3_handler.php';
    require_once __DIR__ . '/ID3Parser/getID3/getid3_lib.php';
    require_once __DIR__ . '/ID3Parser/getID3/Tags/getid3_id3v1.php';
    require_once __DIR__ . '/ID3Parser/getID3/Tags/getid3_id3v2.php';
    require_once __DIR__ . '/ID3Parser/ID3Parser.php';


    // function definition to convert array to xml
    function array_to_xml( $data, &$xml_data ) {
        foreach( $data as $key => $value ) {
            $name = NULL;
            if( is_numeric($key) ){
                $key = 'item'; //.$key dealing with <0/>..<n/> issues
            }
            if( is_array($value) ) {
                $subnode = $xml_data->addChild($key);
                array_to_xml($value, $subnode);
            } else {
                $xml_data->addChild("$key",htmlspecialchars("$value"));
            }
            }
    }

    
    $files = array_diff(scandir($path), array('.', '..', 'index.php'));
    foreach ($files as $key => $link) {
        if(is_dir($path."\\".$link)){
            unset($files[$key]);
        }
    }
    $mp3list = [];
    foreach($files as $file) if ( substr($file, -4) === ".mp3" ){
        $mp3_tags = [
            "album" => "",
            "artist" => "",
            "title" => "",
            "file" => ""
        ];
        $analyzer = new \ID3Parser\ID3Parser();
        $output = $analyzer->analyze($path."/".$file);
        file_put_contents(__DIR__ ."/error.log", print_r($output, TRUE), FILE_APPEND);
        // date im Albums-Tag
        //if ($output["id3v2"]["comments"]["album"]){
        //    $mp3_tags["album"] = $output["id3v2"]["comments"]["album"][0];
        //} else {
            $mp3_tags["album"] = substr($file, 6, 2).".".substr($file, 4, 2).".".substr($file, 0, 4);
        //}
        // artist im artist-Tag
        if ($output["id3v2"]["comments"]["artist"]){
            $mp3_tags["artist"] = $output["id3v2"]["comments"]["artist"][0];
        } else {
            $mp3_tags["artist"] = substr($file, 11, -4);
        }
        // Stelle und evtl. Thema im title-Tag
        if ($output["id3v2"]["comments"]["title"]){
            $mp3_tags["title"] = $output["id3v2"]["comments"]["title"][0];
        } else {
            $mp3_tags["title"] = "-";
        }
        $mp3_tags["file"] = $file;
        array_push($mp3list, $mp3_tags);
    }
    
    // creating object of SimpleXMLElement
    $xml_data = new SimpleXMLElement('<?xml version="1.0"?><data></data>');
    
    // function call to convert array to xml
    array_to_xml($mp3list, $xml_data);
    
    //saving generated xml file; 
    $result = $xml_data->asXML($xmlpath);
?>
