<?php
if (!isset($_FILES['xml']) || !isset($_FILES['logo'])) {
    header('Location: ./../index.php');
    exit();
}
$name = $_FILES['xml']['name'];
$type = $_FILES['xml']['type'];
$tmp = $_FILES['xml']['tmp_name'];

var_dump($_FILES['xml']);

var_dump($_FILES['logo']);
