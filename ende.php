<html>
<head>
<?php include ("#authenticated.inc.php"); ?>
<?php include ("#mysql.inc.php"); ?>
<?php include ("#log.inc.php") ?>
<?php
if ($_SESSION['lesen']==0)
{
    ?><meta http-equiv="refresh" content="0; URL=forbitten-all.php" /><?php
}
?>
<title>Ende</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
Datensicherung wird durchgef&uuml;hrt
<br><br>
<?php  
            $ew=" Sitzung geschlossen " ;
            $l=$ew ;
            logsch ($l);
session_unset(); ?>
</big></big></big>
<br>
<meta http-equiv="refresh" content="1; URL=sicherunglwa.php" />
</body>
</html>
<?php  
/*
$ew / $l = logeinträge
*/
?>