<?php
require __DIR__.'/lib.php';

$s = new Security(new UserRepository());
if ($s->isLoggin()) {
    $s->logout();
}

header('Location: ../');