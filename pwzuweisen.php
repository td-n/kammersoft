<?php  
if($_SESSION['prozedur'] == "passwortzuweisen")
{
    $ID = $_SESSION['ID'];
    if(isset($_POST['pass']))
    {
        $pass = $_POST['pass']; //holt sich das eingetippte Passwort aus der Variable $POST
        if(empty($pass))
        {
            $_SESSION['prozedur'] = 'leerespasswort';
            header("Location: passwort.php"); //weiterleitung zur ersten Seite
            exit;
        }
        $pass=md5($pass);
        $query = "UPDATE `user` SET `Passwort` = '$pass' WHERE `user`.`ID` = '$ID'"; //Sucht alle Eintr�ge, welche mit dem eingegebenen ID �bereinstimmen
        mysql_query($query); //f�hrt die Befehlszeile aus
        mysql_close($dz); //schlie�en der MySQL Abfrage
        header("Location: login.php"); //weiterleitung zur ersten Seite
    }
    else
    {
            $_SESSION['prozedur'] = 'leerespasswort';
            header("Location: passwort.php"); //weiterleitung zur ersten Seite
            exit;
    }
}    
?>