<?php


if (file_exists('config.php')) {
    $config = require_once 'config.php';
}else {
    die('config file not exists');
}

if (!file_exists('db.php')) {
    die('db file not found');
}

try {

    $db = new PDO($config['db']['dsn'] , $config['db']['username'] , $config['db']['password']);

    $db->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);

    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE , PDO::FETCH_OBJ);

    $db->exec("SET NAMES utf8");

}catch (PDOException $e) {

    die($e->getMessage());
}