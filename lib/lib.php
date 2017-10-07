<?php
require_once  __DIR__.'/../config.php';
require_once  __DIR__.'/user/User.php';
require_once  __DIR__.'/user/Setting.php';
require_once  __DIR__.'/user/DbConnection.php';
require_once  __DIR__.'/user/UserRepository.php';
require __DIR__.'/user/Security.php';
session_start();
$repo = new UserRepository();
$s = new Security($repo);