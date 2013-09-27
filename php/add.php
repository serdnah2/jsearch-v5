<?php

error_reporting(0);
if (isset($_POST['titulo']) && isset($_POST['link']) && isset($_POST['desc']) && isset($_POST['claves'])) {
    $json_data = json_decode(file_get_contents('../js/database.js'), true);
    $obj = array(
        claves => $_POST['claves'],
        description => $_POST['desc'],
        link => $_POST['link'],
        title => $_POST['titulo'],
    );
    array_push($json_data, $obj);
    $validate = file_put_contents('../js/database.js', json_encode($json_data));
    if ($validate) {
        $obj = array(
            "success" => "saved correctly"
        );
        print json_encode($obj);
    } else {
        $obj = array(
            "error" => "Failed to open file, please change the link"
        );
        print json_encode($obj);
    }
    die;
} else {
    $obj = array(
        "error" => "Please send for method post the attributes"
    );
    print json_encode($obj);
}

/* for ($i = 0, $len = count($json_data); $i < $len; ++$i) { //for add new key into item
  $json_data[$i]['num'] = (string) ($i + 1);
  } */
?>