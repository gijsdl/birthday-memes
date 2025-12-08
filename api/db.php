<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=birthday_memes', 'root', '');
} catch (PDOException $e){
    die('ERROR: ' . $e->getMessage());
}