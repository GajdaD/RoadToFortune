<?php
define('DB_SERVER', 'XXX');
define('DB_USERNAME', 'XXX');
define('DB_PASSWORD', 'XXX');
define('DB_NAME', 'XXX');
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>