<?php
require 'db.php';
global $db;
header('Content-type: application/json');
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (empty($id)){
    $query = $db->prepare('SELECT * FROM photos ORDER BY category_id');
} else{
    $query = $db->prepare('SELECT * FROM photos WHERE category_id = :id');
    $query->bindParam('id', $id);
}
$query->execute();
echo json_encode($query->fetchAll(PDO::FETCH_ASSOC));