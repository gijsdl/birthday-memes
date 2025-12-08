<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=birthday_lianne_photos', 'root', '');
} catch (PDOException $e){
    die('ERROR: ' . $e->getMessage());
}