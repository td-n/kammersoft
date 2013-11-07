<?php

require_once('conf/config.php');

$db = new PDO("mysql:host=$db_host;dbname=$db_database", $db_user, $db_pass);

?>