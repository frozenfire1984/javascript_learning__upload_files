<?php
error_reporting(0);
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json; charset=utf-8');

//echo("<pre>");
//print_r($_POST);
//echo("</pre>");

//echo("<pre>");
//print_r($_FILES);
//echo("</pre>");
//echo(count($_FILES));
//goto end;
try {
    //break;
    /*
    if (count($_FILES) === 0) {
        throw new Exception("file don't received to server!");
    }*/

    if (isset($_POST["input-title"])) {
        $title = trim(stripslashes($_POST["input-title"]));
    }

    $desc = "default desc";
    if (isset($_POST["input-title"])) {
        $desc = trim(stripslashes($_POST["input-desc"]));
    }

    if (isset($_POST["input-title"])) {
        $price = $_POST["input-price"];
    }

    require_once("connect.php");

    $sql = "INSERT INTO goods (id, title, desc_text, price) VALUES (NULL, '$title', '$desc', $price)";

    if ($conn->query($sql)) {
        //echo("insert to bd successs!");
    } else {
        throw new Exception("insert to bd failed!");
    }

    $output_array = [];
    foreach ($_FILES as $key => $value) {
        $extension = pathinfo($value["name"], PATHINFO_EXTENSION);
        $new_name = time().'_'.$key.'.'.$extension;
        if (!move_uploaded_file($value['tmp_name'], 'images/' . $new_name)) {
            throw new Exception("Could not move file; bad credentials of target folder!");
            break;
        }
        array_push($output_array, 
        [
            'image_source' => 'images/' . $new_name, 
            'file_size' => $value["size"],
            'old_name' => $value["name"]
        ]);
    }

    //echo("<pre>");
        //print_r($output_array);
        //echo("</pre>");
    sleep(2);
    $output_array = array(
        "images_array" => $output_array
    );

    echo json_encode($output_array, JSON_PRETTY_PRINT);  

} catch (Exception $e) {
    $data_error = array(
        'error_name' => $e->getMessage()
    );
    echo json_encode($data_error);
}
end:
?>