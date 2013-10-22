<?php
function logsch ($eing) 
{
    $auser=$_SESSION['kname'];
    $eintrag = "INSERT INTO log (eintrag, name) VALUES ('$eing', '$auser')";
    mysql_query($eintrag);
}
/*
$auser = Klarname
$eintrag = Übergabevariable
*/
?>