<?php
  try {
    $connection_url = getenv("MONGOHQ_URL");

    $m = new Mongo($connection_url);

    $url = parse_url($connection_url);
    $db_name = preg_replace('/\/(.*)/', '$1', $url['path']);

    $db = $m->selectDB($db_name);
    $grid = $db->getGridFS();

    $id = $_GET['id'];

    $grid->remove(array("_id" => $id)); 

    $m->close();
  } catch ( MongoConnectionException $e ) {
    die('Error connecting to MongoDB server');
  } catch ( MongoException $e ) {
    die('Mongo Error: ' . $e->getMessage());
  } catch ( Exception $e ) {
    die('Error: ' . $e->getMessage());
  }
?>
