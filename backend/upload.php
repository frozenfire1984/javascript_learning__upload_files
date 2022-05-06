<?php
error_reporting(0);
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json; charset=utf-8');
//echo("<pre>");
//print_r($_FILES);
//echo("</pre>");
//echo(count($_FILES));
//goto end;
try {
    //break;
    if (count($_FILES) === 0) {
        throw new Exception("file don't received to server!");
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
    sleep(5);
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