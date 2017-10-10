<?php

session_start();

$code = isset($_SESSION['login']) ? $_SESSION['login'] : null;


if (is_null($code))
{
    header('Location: login.php');
    exit();
}

if (!file_exists('db.php')) {
    die('db file not found');
}


require_once 'db.php';

$query = $db->prepare('SELECT * FROM `admins` WHERE code = ?');
$query->bindParam(1, $code);
$query->execute();

$admin  = $query->fetch();

if (!$admin OR !$admin->code == $code) {
    header('Location: login.php');
    exit;
}

function pre($var) {
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}