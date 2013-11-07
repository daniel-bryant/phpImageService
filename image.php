<?php
$id = $_GET['id'];

$connection_url = getenv("MONGOHQ_URL");
$m = new Mongo($connection_url);
$url = parse_url($connection_url);
$db_name = preg_replace('/\/(.*)/', '$1', $url['path']);
$db = $m->selectDB($db_name);
$grid = $db->getGridFS();

header('Content-type: image/jpg;');
echo $grid->findOne(array('_id' => new MongoId($id)))->getBytes();
?>
