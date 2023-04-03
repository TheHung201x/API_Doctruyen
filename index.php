<?php
 header('Content-Type: application/json; charset=utf-8');
 require_once 'db.php'; // kết nối CSDL 

if(!isset($_GET['res']))
    die('Resource notfound');

$file = $_GET['res'];

// kiểm tra tồn tại file
$file_path = __DIR__.'/'.$file.'.php';

if( file_exists(  $file_path   ) )
    require_once $file_path;
else
    die('File notfound: ' . $file );