<?php

require_once("db.php");

$user = $_POST["username"];
$pass = $_POST["password"];

$sql = "SELECT COUNT(ID) FROM user WHERE Name = :username AND Passwort = MD5(:password)";
$stmt = $db->prepare($sql);
$stmt->bindParam(':username', $user);
$stmt->bindParam(':password', $pass);

if (!$stmt->execute())
	die("DB-Fehler.");

$row = $stmt->fetch();
if ($row[0] == 0)
	die("Falsches Passwort.");

?>