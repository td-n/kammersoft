<html>
<head>
<?php include ("#mysql.inc.php"); ?>
<?php include ("#authenticated.inc.php"); ?>
<?php include ("#log.inc.php") ?>
<title>Passwort l&ouml;schen</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
Passwort l&ouml;schen  <br>
</big></big></big>
Nach dem L&ouml;schen kann das Passwort mit einer Anmeldung mit den Namen und ohne Passwort neu vergeben werden !
<?php                                                               //passwortrücksetzung mit Anmeldung Passwort
if($_SESSION['user'] == "Passwort")                                  
{
    $vorh="0";
    $pws=0;
    if (isset($_POST['ename']))
    {
            $ename = $_POST['ename'];
            $sql = mysql_query("SELECT * FROM user ");                  //user auswählen
            while($ds= mysql_fetch_object($sql))
            {
                 $name = $ds->Name ;
                 $pid= $ds->ID ;
                 if ($name == $ename)
                 {
                    $vorh="1";
                    if ($pid < 3)                                    //Passwort von Passwortanmeldung schützen
                    {
                        ?> <font color="#FF0000"><big><big><br><br>Passwort kann nicht zur&uuml;ckgesetzt werden - gesch&uuml;tzt</big></big><br><br></font> <?php ;
                        $pws=1;
                    }
                    else
                    {
                        $query = "UPDATE `user` SET `Passwort` = 'd41d8cd98f00b204e9800998ecf8427e' WHERE `ID` =$pid";  //Passwort löschen
                        $resultID = @mysql_query($query);
                    }
                 }    
            }
        if ($vorh == "1" and $ename<>"0")
        {
            if ($pws=0)
            {
                 ?> <font color="#FF0000"><big><big><br><br>Passwort zur&uuml;ckgesetzt sie werden zur Anmeldung weitergeleitet</big></big><br><br></font> <?php ;
            }
            ?>
            <meta http-equiv="refresh" content="3; URL=login.php">
            <?php
            $ew=" Passwort geloescht" ;
            $l=$ename.$ew ;
            logsch($l);
        }
        else
        {
            ?> <font color="#FF0000"><big><big><br><br>Passwort nicht zur&uuml;ckgesetzt - keinen Benutzer gefunden</big></big><br><br></font> <?php ;
        }        
    }
    ?>
    </form>
    <br><br>Geben Sie den Benutzer ein, von dem das Passwort zur&uuml;ckgesetzt werden soll:
    <form name="eingabe" action="passwortrueck.php" method="post" >
    <input type="text" name="ename">
    <input type="submit" value="ausw&auml;hlen">
    </form>
    <?php
}
else
{
  $nam=$_SESSION['kname']."/".$_SESSION['user'];
  $ew= " hat sich eingelogt" ;
  $l= $nam.$ew;
//  echo $l;
  logsch($l);
header("Location: start.php");
/*
?>
<meta http-equiv="refresh" content="0; URL=start.php">              <!--weiterleitung zur Übersichtsseite  -->
<?php
*/
}    
?>
</body>
</html>
<?php  
/*
$ds = datenzähler
$ename = eingebener user
$ew = log text
$l = logeintrag
$nam = logname / klarname
$name = name in user
$pid = id in user
$pws = kontrollvariable 0=zurückgesetzt
$query / $resultID / $sql = variable in datenbankabfrage
$vorh = kontrollvariable passwort vorhanden 
*/
?>
