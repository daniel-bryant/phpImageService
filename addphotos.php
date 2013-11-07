<html>
 <head>
  <title>Add Photos</title>
 </head>
 <body>
<?php
// parts taken from - http://stackoverflow.com/questions/2704314/multiple-file-upload-in-php/2704321
// usage - <input name="upload[]" type="file" multiple="multiple" />
// Requirements
// MongoDB
// *PECL mongo
// *PECL pecl_http
// ImageMagick <libmagickwand-dev imagemagick>
// *PECL imagick

try
{
  $connection_url = getenv("MONGOHQ_URL");

  $m = new Mongo($connection_url);

  $url = parse_url($connection_url);
  $db_name = preg_replace('/\/(.*)/', '$1', $url['path']);

  $db = $m->selectDB($db_name);
  $grid = $db->getGridFS();

}
catch ( MongoConnectionException $e )
{
  echo '<p>Couldn\'t connect to mongodb, is the "mongo" process running?</p>';
  exit();
}

$albumId = $_POST['albumId'];

//Loop through each file
for($i=0; $i<count($_FILES['upload']['name']); $i++) {

  //Get the temp file path
  $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
  //Make sure we have a filepath
  if ($tmpFilePath != ""){
    //Store into gridfs photos
    $imageId = $grid->storeFile($tmpFilePath);

    $thumb = new Imagick($tmpFilePath);
    $thumb->cropThumbnailImage(150, 150);
    $thumbId = $grid->storeBytes($thumb->getImageBlob());

    $response = http_post_fields('http://sheltered-brook-1332.herokuapp.com/album/' . $albumId . '/' . $imageId . '/' . $thumbId, array());
  }
}

http_redirect('http://sheltered-brook-1332.herokuapp.com/album/' . $albumId);

?> 
 </body>
</html>
