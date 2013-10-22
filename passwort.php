<html>
<head>
<?php
session_start(); //initialisiert die Sessionvariable - wird auch benötigt um auf vorhandene zuzugreifen, andernfalls findet eine erstinitialisierung statt
include ("#mysql.inc.php"); //nötig um den Zugriff auf die MySQL-Datenbank zu gewährleisten!
?>
<title>Passwort &auml;ndern</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<?php     
if($_SESSION['prozedur'] == "passwortzuweisen")
{              
    $ID = $_SESSION['ID'];
        
    $pass = $_POST['pass']; //holt sich das eingetippte Passwort aus der Variable $POST
    if(empty($pass))
    {
        $_SESSION['prozedur'] = 'leerespasswort';
        header("Location: passwort.php"); //weiterleitung zur ersten Seite
        exit;
    }
    $pass=md5($pass);
    $query = "UPDATE `user` SET `Passwort` = '$pass' WHERE `user`.`ID` = '$ID'"; //Sucht alle Einträge, welche mit dem eingegebenen ID übereinstimmen
    mysql_query($query); //führt die Befehlszeile aus
    mysql_close($dz); //schließen der MySQL Abfrage 
    header("Location: login.php"); //weiterleitung zur ersten Seite 
}
if($_SESSION['prozedur'] == "neuespasswort")
{
    $_SESSION['prozedur'] = 'passwortzuweisen';
    echo "<form method='post' action='passwort.php'>";
    echo "<div style='position:absolute; top:20%; left:20%; float:left; height:200px; width:800px; border:black 0px solid'>";
    echo "<br><big><big><big>";
/*    echo "<big>";
    echo "<big>";
    echo "<big>";
    echo "<big>";  */
    echo "<div align='center'>";
    echo "Sie haben noch kein eigenes Passwort eingegeben, dies ist zwingend notwendig.";
    echo "<br>";
    echo "Bitte tragen Sie ein Passwort ein und melden Sie sich neu an";
    echo "<br>";
    echo "<input name='pass' type='password'>";
    echo "<br>";
    echo "<input value='eintragen' type='submit'>";
    echo "</div>";
    echo "</form>";
}
if($_SESSION['prozedur'] == "leerespasswort")
{
       echo "<big><big><br><br><br><div align='center'>Sie haben kein Passwort eingetragen!<br></big></big>";
       $_SESSION['prozedur'] = 'neuespasswort';
       echo "<a href='passwort.php'>Erneut versuchen</a>";
}
?> 
</big></big></big>
</body>
</html>
                                       