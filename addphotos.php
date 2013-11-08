<!-- PHP Mongo Docs: http://php.net/manual/en/class.mongodb.php -->
<html>
<body>
<?php
  try {
    // connect to MongoHQ assuming your MONGOHQ_URL environment
    // variable contains the connection string
    $connection_url = getenv("MONGOHQ_URL");

    // create the mongo connection object
    $m = new Mongo($connection_url);

    // extract the DB name from the connection path
    $url = parse_url($connection_url);
    $db_name = preg_replace('/\/(.*)/', '$1', $url['path']);

    // use the database we connected to
    $db = $m->selectDB($db_name);
    //get GridFS
    $grid = $db->getGridFS();

    $albumId = $_POST['albumId'];

    //Loop through each file
    for($i=0; $i<count($_FILES['upload']['name']); $i++) {
      //Get the temp file path
      $tmpFilePath = $_FILES['upload']['tmp_name'][$i];

      //Make sure we have a filepath
      if ($tmpFilePath != "") {
        //Store into gridfs photos
        $imageId = $grid->storeFile($tmpFilePath);

        $thumb = new Imagick($tmpFilePath);
        $thumb->cropThumbnailImage(150, 150);
        $thumbId = $grid->storeBytes($thumb->getImageBlob());

        http_post_fields('http://sheltered-brook-1332.herokuapp.com/album/' . $albumId . '/' . $imageId . '/' . $thumbId, array());
        http_redirect('http://sheltered-brook-1332.herokuapp.com/album/' . $albumId);
      }
    }

    // disconnect from server
    $m->close();
  } catch ( MongoConnectionException $e ) {
    die('Error connecting to MongoDB server');
  } catch ( MongoException $e ) {
    die('Mongo Error: ' . $e->getMessage());
  } catch ( Exception $e ) {
    die('Error: ' . $e->getMessage());
  }
?>
</body>
</html> 
