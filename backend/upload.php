<?php
error_reporting(0);
header("Access-Control-Allow-Origin: *");
//header('Content-type: application/json; charset=utf-8');
//echo("<pre>");
//print_r($_FILES);
//echo("</pre>");
//goto end;
try {
    //break;
    if (!isset($_FILES['sample_image'])) {
        throw new Exception("file don't received to server!");
    }

    $extension = pathinfo($_FILES["sample_image"]["name"], PATHINFO_EXTENSION);
    $new_name = time().'.'.$extension;

    if (!move_uploaded_file($_FILES['sample_image']['tmp_name'], 'images/' . $new_name)) {
        throw new Exception("Could not move file; bad credentials of target folder!");
    }
    $data = array(
        'image_source' => 'images/' . $new_name
    );
    echo json_encode($data);
} catch (Exception $e) {
    $data_error = array(
        'error_name' => $e->getMessage()
    );
    echo json_encode($data_error);
}
end:
?>