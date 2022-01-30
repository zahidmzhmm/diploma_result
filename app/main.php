<?php
require __DIR__ . '/../config.php';
require __DIR__ . '/../vendor/autoload.php';
error_reporting(Error);

$url = str_replace('/', '.', $_GET['url']);
$path = explode('/', $_GET['url']);
global $url;

use app\Controller;

$controller = new Controller();

if ($path[0] === 'api') {
    header("Content-Type:application/json");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    if (isset($_POST['csrf'])) {
        if ($path[1] === 'upload') {
            $controller->uploadFile($_FILES);
        }
    }
    if ($path[1] === 'result') {
        $controller->fetchResult($_GET);
    }
}