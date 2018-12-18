<?php
require_once __DIR__ . '/../../api/api.php';
require_once API::entity('image');

function scale_height($width, $height, $destWidth) {
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
    echo "Please choose a file 2";
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
$smallheight = scale_height($width, $height, $smallwidth);
$small = imagecreatetruecolor($smallwidth, $smallheight);
imagecopyresized($small, $original, 0, 0, 0, 0, $smallwidth, $smallheight, $width, $height);

// 4. Medium
$mediumwidth = min($width, 1024);
$mediumheight = scale_height($width, $height, $mediumwidth);
$medium = imagecreatetruecolor($mediumwidth, $mediumheight);
imagecopyresized($medium, $original, 0, 0, 0, 0, $mediumwidth, $mediumheight, $width, $height);

$imageid = Image::create();

// echo "IMAGEID = $imageid";


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

// echo '<pre style="font-size:150%">';
echo json_encode([
    'id'   => $imageid,
    'info' => $info,
    'rect' => $rect,
    'userfilename' => $userfilename
], JSON_PRETTY_PRINT);
// echo '</pre>';
end:
?>