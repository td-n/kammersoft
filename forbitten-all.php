<?php
<?php include ("#mysql.inc.php"); ?>
<?php include ("#authenticated.inc.php"); ?>
<?php include ("#log.inc.php") 
$l="forbitten-all ge&ouml;ffnet - nicht eingelogt";
logsch ($l);
echo "<big><big>Sie sind nicht berechtigt eine Seite anzuw&auml;hlen</big></big><br>";
echo "<big><big>Loggen sie sich bitte ein</big></big>";
?><meta http-equiv="refresh" content="5; URL=login.php" /><?php
?>