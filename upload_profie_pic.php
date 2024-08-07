<?php
// Check if file was uploaded without errors
if(isset($_FILES["profile_picture"]) && $_FILES["profile_picture"]["error"] == 0){
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if file already exists
    if (file_exists($target_file)) {
        echo json_encode(array("success" => false, "message" => "File already exists."));
        exit();
    }

    // Check file size
    if ($_FILES["profile_picture"]["size"] > 500000) {
        echo json_encode(array("success" => false, "message" => "File is too large."));
        exit();
    }

    // Allow only certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        echo json_encode(array("success" => false, "message" => "Only JPG, JPEG, PNG & GIF files are allowed."));
        exit();
    }

    // Move the file to the uploads directory
    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
        echo json_encode(array("success" => true, "file_path" => $target_file));
    } else {
        echo json_encode(array("success" => false, "message" => "Error uploading file."));
    }
} else {
    echo json_encode(array("success" => false, "message" => "No file uploaded."));
}
?>

