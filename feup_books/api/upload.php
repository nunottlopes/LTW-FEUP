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
function scale_height(int $width, int $height, int $destWidth) {
    if ($width <= $destWidth) return $height;
    else return $height * $destWidth / $width;
}

$supportedImageTypes = [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG];

if (!isset($_FILES['upload-file'])) {
    echo "Please choose a file";
    goto end;
}

$fup = $_FILES['upload-file'];

if (!isset($fup['name']) || !isset($fup['tmp_name'])) {
    echo "Please choose a file";
    goto end;
}

if ($fup['name'] === '' || $fup['tmp_name'] === '') {
    echo "No file";
    goto end;
}

$userfilename = basename($_FILES['upload-file']['name']);
$tmpfilename = $_FILES['upload-file']['tmp_name'];
$filesize = $_FILES['upload-file']['size'];

$exif = exif_imagetype($tmpfilename);

if (!in_array($exif, $supportedImageTypes)) {
    echo "Unsupported image type";
    goto end;
}

$extension = strtolower(image_type_to_extension($exif, false));

$imagesize = getimagesize($tmpfilename);

// 1. Original Image
switch ($extension) {
case 'gif':
    $original = imagecreatefromgif($tmpfilename);
    break;
case 'jpeg':
    $original = imagecreatefromjpeg($tmpfilename);
    break;
case 'png':
    $original = imagecreatefrompng($tmpfilename);
    break;
}

$width = $imagesize[0];
$height = $imagesize[1];
$side = min($width, $height);

$rect = [
    'x' => (($width - $side) / 2),
    'y' => (($height - $side) / 2),
    'width' => $side,
    'height' => $side
];

$crop = imagecrop($original, $rect);

// 2. Thumbnail
$thumbnail = imagecreatetruecolor(256, 256);
imagecopyresized($thumbnail, $crop, 0, 0, 0, 0, 256, 256, $side, $side);

// 3. Small
$smallwidth = min($width, 512);
$smallheight = scale_height($width, $height, 512);
$small = imagecreatetruecolor($smallwidth, $smallheight);
imagecopyresized($small, $original, 0, 0, 0, 0, $smallwidth, $smallheight, $width, $height);

// 4. Medium
$mediumwidth = min($width, 1024);
$mediumheight = scale_height($width, $height, 1024);
$medium = imagecreatetruecolor($mediumwidth, $mediumheight);
imagecopyresized($medium, $original, 0, 0, 0, 0, $mediumwidth, $mediumheight, $width, $height);

$imageid = Image::create();

echo "IMAGEID = $imageid";


// Output filenames
$imagefile = Image::filename($imageid, $extension);;
$files = Image::files($imagefile);

move_uploaded_file($tmpfilename, $files['original']);

switch ($extension) {
case 'gif':
    imagegif($thumbnail, $files['thumbnail']);
    imagegif($small, $files['small']);
    imagegif($medium, $files['medium']);
    break;
case 'jpeg':
    imagejpeg($thumbnail, $files['thumbnail']);
    imagejpeg($small, $files['small']);
    imagejpeg($medium, $files['medium']);
    break;
case 'png':
    imagepng($thumbnail, $files['thumbnail']);
    imagepng($small, $files['small']);
    imagepng($medium, $files['medium']);
    break;
}

$info = [
    'format'    => $extension,
    'imagefile' => $imagefile,
    'filesize'  => $filesize,
    'original'  => ['width' => $width, 'height' => $height],
    'medium'    => ['width' => $mediumwidth, 'height' => $mediumheight],
    'small'     => ['width' => $smallwidth, 'height' => $smallheight],
    'thumbnail' => ['width' => 256, 'height' => 256]
];

Image::setInfo($imageid, $imagefile, $info);




echo '<pre style="font-size:150%">';
echo json_encode([
    'info' => $info,
    'rect' => $rect,
    'userfilename' => $userfilename
], JSON_PRETTY_PRINT);
echo '</pre>';
end:
?>
<form action="/feup_books/api/upload.php" method="post" enctype="multipart/form-data">
    <input type="file" name="upload-file" id="upload-file"/>
    <input type="submit" value="Upload Image" name="submit"/>
</form>
