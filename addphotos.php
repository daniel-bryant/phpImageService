<!-- PHP Mongo Docs: http://php.net/manual/en/class.mongodb.php -->
<html>
<body>
<h1>Add Photos</h1>
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
