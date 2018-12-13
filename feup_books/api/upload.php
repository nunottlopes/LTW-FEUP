<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/api.php';
require_once API::entity('image');

/**
$target_dir = "uploads/";             $UPLOAD_DIR
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
**/

$userfilename = basename($_FILES['upload-file']['name']);
$tmpfilename = $_FILES['upload-file']['tmp_name'];
$storefilename = $UPLOAD_DIR . "$userfilename";

$fileExtension = strtolower(pathinfo($userfilename, PATHINFO_EXTENSION));

$size = getimagesize($tmpfilename);

$fileExists = file_exists($storefilename);

echo '<pre style="font-size:150%">';

echo json_encode([
    'userfilename' => $userfilename,
    'tmpfilename' => $tmpfilename,
    'storefilename' => $storefilename,
    'fileExtension' => $fileExtension,
    'size' => $size,
    'fileExists' => $fileExists
], JSON_PRETTY_PRINT);

echo '</pre>';
?>
<form action="/feup_books/api/upload.php" method="post" enctype="multipart/form-data">
    <input type="file" name="upload-file" id="upload-file"/>
    <input type="submit" value="Upload Image" name="submit"/>
</form>
