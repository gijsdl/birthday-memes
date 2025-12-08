<?php
require 'api/db.php';
global $db;
$message = "";

if (isset($_POST['submit-category'])) {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    if (!empty($name)) {

        $searchQuery = $db->prepare('SELECT * FROM category WHERE name = :name');
        $searchQuery->bindParam('name', $name);
        $searchQuery->execute();
        $result = $searchQuery->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $message = 'Bestaat al';
            var_dump($message);
        } else {
            $query = $db->prepare('INSERT INTO category (name) VALUES (:name)');
            $query->bindParam('name', $name);
            $query->execute();
        }
    }
}

$dir = 'img/new-img';
$files = scandir($dir);

if (isset($_POST['submit-photo'])) {
    if ($_POST['submit-photo'] === 'add') {
        $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_SPECIAL_CHARS);
        if (!empty($category)) {
            $idQuery = $db->query('SELECT id FROM photos ORDER BY id DESC');
            $id = $idQuery->fetch(PDO::FETCH_ASSOC);
            if (!$id) {
                $id = 0;
            } else {
                $id = $id['id'];
            }
            $id++;
            $newName = $id . '.' . pathinfo('img/new-img/' . $files[2])['extension'];
            rename('img/new-img/' . $files[2], 'img/old-img/' . $newName);
            $insertQuery = $db->prepare('INSERT INTO photos (id, name, category_id) VALUES (:id ,:name, :catId)');
            $insertQuery->bindParam('id', $id);
            $insertQuery->bindParam('name', $newName);
            $insertQuery->bindParam('catId', $category);
            $insertQuery->execute();

        } else {
            var_dump('empty');
        }
    } elseif ($_POST['submit-photo'] === 'delete') {
        unlink('img/new-img/' . $files[2]);
    }
}

$files = scandir($dir);

$query = $db->query('SELECT * FROM category');
$categories = $query->fetchAll(PDO::FETCH_ASSOC);


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>foto's</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-body-secondary">
<div class="container-fluid">
    <header class="row bg-primary">
        <div class="col d-flex justify-content-center p-2">
            <h1 class="text-light">Verjaardag Lianne</h1>
        </div>
    </header>
</div>
<div class="container mt-5">
    <main class="row">
        <div class="col-2">
            <div class="row">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="category" id="all" value="" checked>
                        <label class="form-check-label" for="all">
                            Alles
                        </label>
                    </div>
                    <?php foreach ($categories as $category): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="category" id="<?= $category['name'] ?>"
                                   value="<?= $category['id'] ?>">
                            <label class="form-check-label" for="<?= $category['name'] ?>">
                                <?= $category['name'] ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col">
                    <p class="fs-6">Nieuwe category</p>
                    <form method="post">
                        <div class="mb-3">
                            <label for="name" class="form-label">Naam</label>
                            <input type="text" class="form-control" name="name" id="name">
                            <p class="text-danger">
                                <?= $message ?? "" ?>
                            </p>
                        </div>
                        <button type="submit" name="submit-category" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class=" d-flex flex-column align-items-center justify-content-center">
                <span class="loader hidden"></span>
            </div>
            <div class=" d-flex flex-column align-items-center justify-content-center old-images">

            </div>
        </div>
        <div class="col-5 d-flex flex-column align-items-center justify-content-start new-image">
            <div class="sticky-top">
                <form method="post" class="mt-2">
                    <?php if (isset($files[2])): ?>
                        <img src="img/new-img/<?= $files[2] ?>">
                        <select class="form-select mb-3" name="category">
                            <option value="">--Kies een optie--</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button class="btn btn-primary" name="submit-photo" value="add">Toevoegen</button>
                        <button class="btn btn-danger" name="submit-photo" value="delete">Verwijderen</button>
                    <?php else: ?>
                        <p class="text-danger">Er zijn geen afbeeldingen meer </p>
                    <?php endif; ?>

                </form>
            </div>
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
<script src="js/main.js"></script>
</body>
</html>
