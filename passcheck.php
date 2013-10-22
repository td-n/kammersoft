<html>
<head>
<?php session_start(); //nötig um auf Sessionvariablen zuzugreifen!
include ("#mysql.inc.php"); //nötig um den Zugriff auf die MySQL-Datenbank zu gewährleisten!
$user = $_POST['user']; //holt sich den eingetippten Usernamen aus der Variable $POST
$pass = MD5($_POST['pass']); //holt sich das eingetippte Passwort aus der Variable $POST
$user_ok ='';
$pass_ok ='';
$query = "SELECT * FROM `user` WHERE Name LIKE '$user'"; //Sucht alle Einträge, welche mit dem eingegebenen User übereinstimmen
$sql = mysql_query($query) //trägt die Ergebnisse in die Variable $sql ein
or die('keinpassenden User gefunden'); // Fehler abfangen, falls nichts ausgelesen werden kann
while ($ds = mysql_fetch_array($sql)) //Alle Ergebnisse durchgehen
{
$user_ok = $ds[1]; //passenden User aus der Spalte User heraussuchen, an der Stelle des passenden Users
$ID = $ds[0]; 
$lesen = $ds[3];   //passenden securitylevel aus der Spalte User heraussuchen, an der Stelle des passenden Users
$schreiben = $ds[4];
$pass_ok = $ds[2];
$kname = $ds[5];
}
if (($pass_ok=="d41d8cd98f00b204e9800998ecf8427e") AND ($user == $user_ok) AND ($user!=""))     //Passwort=leer
{
      $_SESSION['ID'] = $ID; //ID des nutzers in die Session übergeben
      $_SESSION['prozedur'] = 'neuespasswort'; //Parameterzuweisung der Sessionvariable "Prozedur" damit in der nächsten Seite der Interpreter weiß, welche Prozedur als erstes ausgeführt werden soll        
      header("Location: passwort.php"); //weiterleitung zur Passworteingabe
      exit;
}
if ($user == $user_ok && $pass == $pass_ok && $user!="" && $pass!="d41d8cd98f00b204e9800998ecf8427e") //Prüfen ob der User und das PW übereinstimmen mit der eingabe
{
$_SESSION['authenticated'] = "true"; //setzt die Sessionvariable 'authenticated' auf true
$_SESSION['user'] = $user;
$_SESSION['lesen'] = $lesen;
$_SESSION['schreiben'] = $schreiben;
$_SESSION['ID'] = $ID;
$_SESSION['kname'] = $kname;
header("Location: passwortrueck.php");     //weiterleitung zur Notfall Seite
//header("Location: start.php"); //weiterleitung zur ersten Seite
}
else echo "<div align='center'><br><br><br><div align='center'><font color=red>Eingegebene Benutzer-Passwort-Kombination ist ungültig</font><br><a href='login.php'>Nocheinmal versuchen</a>"; //falls Eingabe falsch, dann zeige dies an und verlinke zum erneuten veruschen!
mysql_close($dz); //schließen der MySQL Abfrage
?>
  <title>passwortcheck</title>
</head>
<body>
<br>
</body>
</html>
