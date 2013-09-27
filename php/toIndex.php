<?php

error_reporting(0);
$files = array();
$dir = "";
$folderToFind = "files/";
if ($folderToFind != "") {
    if (substr($folderToFind, 0, 1) === "/") {
        $folderToFind = substr($folderToFind, 1);
    }

    if (substr($folderToFind, -1) === "/") {
        $dir = "../" . $folderToFind;
    } else {
        $dir = "../" . $folderToFind . "/";
    }
} else {
    $obj = array(
        "error" => "Please define a folder in var &#36;folderToFind",
        "line_code" => "line 5 index.php"
    );
    print json_encode($obj);
    return;
}

$url = $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://';
$url .= $_SERVER['SERVER_PORT'] != '80' ? $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"] : $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$urlPath = explode("php/", $url);

function file_get_contents_curl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

function listFolderFiles($dir) {
    global $files;
    global $urlPath;
    global $json_data;
    $ffs = scandir($dir);
    foreach ($ffs as $ff) {
        if ($ff != '.' && $ff != '..' && $ff != ".DS_Store") {
            if (is_dir($dir . $ff)) {
                listFolderFiles($dir . $ff . '/');
            } else {
                $fileSplode = explode("../", $dir);
                $file = $urlPath[0] . $fileSplode[1] . $ff;

                if (strtolower(substr($file, stripos($file, ".htm"))) == ".htm" || strtolower(substr($file, stripos($file, ".html"))) == ".html" || strtolower(substr($file, stripos($file, ".asp"))) == ".asp" || strtolower(substr($file, stripos($file, ".php"))) == ".php") {
                    $html = file_get_contents_curl($urlPath[0] . $fileSplode[1] . $ff);
                    $doc = new DOMDocument();
                    @$doc->loadHTML($html);
                    $nodes = $doc->getElementsByTagName('title');

                    $title = $nodes->item(0)->nodeValue;

                    $metas = $doc->getElementsByTagName('meta');

                    for ($i = 0; $i < $metas->length; $i++) {
                        $meta = $metas->item($i);
                        if ($meta->getAttribute('name') == 'description')
                            $description = $meta->getAttribute('content');
                        if ($meta->getAttribute('name') == 'keywords')
                            $keywords = $meta->getAttribute('content');
                    }

                    $description == null ? $description = "" : 0;
                    $keywords == null ? $keywords = "" : 0;
                    $title == null ? $title = "" : 0;

                    $obj = array(
                        "title" => $title,
                        "link" => $file,
                        "description" => $description,
                        "claves" => $keywords
                    );
                    array_push($files, $obj);
                } else {
                    $obj = array(
                        "title" => $ff,
                        "link" => $file,
                        "description" => "",
                        "claves" => ""
                    );
                    array_push($files, $obj);
                }
            }
        }
    }
}

listFolderFiles($dir);
file_put_contents('../js/databasefolder.js', '');
$save = file_put_contents('../js/databasefolder.js', json_encode($files));
if ($save) {
    $obj = array(
        "success" => "Se han indexado correctamente todos los archivos del directorio " . $folderToFind,
    );
    print json_encode($obj);
}else{
    $obj = array(
        "error" => "No se han indexado correctamente los archivos del directorio " . $folderToFind,
    );
    print json_encode($obj);
}
?>
