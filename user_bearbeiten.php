<html>
<head>
<?php include ("#mysql.inc.php"); ?>
<?php include ("#authenticated.inc.php"); ?>
<?php include ("#log.inc.php") ?>
<?php include ("style.php") ?>
<?php
if ($_SESSION['lesen']==0)
{
    ?><meta http-equiv="refresh" content="0; URL=forbitten-all.php" /><?php
}
?>
<title>User bearbeiten</title>
</head>
<big><big><big>
Entleiher anzeigen, hinzuf&uuml;gen und l&ouml;schen  <br>
</big></big></big>
<?php 
echo "Benutzer :", $_SESSION['kname'], "<br>";
$heut = date("d.m.Y");  //heutuger Datum
echo $heut;
$l="user_bearbeiten ge&ouml;ffnet";
logsch ($l);
/*
while (list ($key, $value) = each ($_REQUEST))                      //alle Rückgabewerte auslesen
{
  echo $key." => ".$value."<br />\n";
}
*/
if (isset ($_POST['zu']))
{
    ?><meta http-equiv="refresh" content="0; URL=start1.php" /><?php ;
}

?>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">  <br><br>
<form name="loesch" action="user_bearbeiten2.php" method="post" >
Personen, welche bereits ausgeliehen haben <br><br>
<table border="1" >
<tr> <th>Name</th><th>l&ouml;schen</th> </tr>
<?php
$vorh="0";                                                          // vorhanden auf 0
if (isset($_POST['ename']))                                         //mit POST wird übergeben
{
    if  ($_POST['ename']== NULL )                                   //ist wert leer
    {
        ?>
        <textarea  cols="110" rows="1" class="text-a" readonly >Keinen Namen eingegeben</textarea><br/>
        <?php
    }
    else 
    {                                                               //sonst
        $ename = $_POST['ename'];
        $sql = mysql_query("SELECT * FROM benutzer ORDER BY Name"); //ist wert schon vorhanden
        while($ds= mysql_fetch_object($sql)) 
        {
            $name = $ds->Name ;
            if ($name  == $ename) 
            {
                ?> <font color="#FF0000"><big><big><br><br>Name bereits vorhanden</big></big></font> <?php ;    //ja
                $vorh="1" ;                                         //vorhanden auf 1
            }
         };
        if ($vorh==0) 
        {                                                           //bei vorhanden =0 eintragen
            $query = "INSERT INTO `benutzer` (`name`)VALUES ('$ename')";
            mysql_query($query);
            $ew=" in benutzer eingetragen" ;
            $l=$ename.$ew ;
            logsch($l);
        }
    }  
}
$sql = mysql_query("SELECT * FROM benutzer ORDER BY Name");         //Tabelle user auswählen
while($ds= mysql_fetch_object($sql)) 
{
    $name = $ds->Name ;
    $pid= $ds->id ;                                                 // aus Datenbank auslesen
    echo "<td>";                                                    //ausgeben
    echo $name, "<br>";
    echo "</td>";
    echo "<td><input type=\"checkbox\" name=\"lo[]\" value= \" $pid \" />"; // löschhäckchen 
    echo "</td>";
    //echo $pid;                                                    //Service
    echo "</tr>";
}
?>
</table>

<?php
$sql="SELECT COUNT(*) AS Anzahl FROM benutzer";
$result = mysql_query($sql);
$zeile = @mysql_fetch_array($result);
$anzahl = $zeile['Anzahl'];
if ($anzahl == 0)
    {
     ?>
     <br><input type="submit" value="loeschen" disabled="disabled"/>  ausgew&auml;hlte Entleiher l&ouml;schen   <!-- löschbutton -->
     <?php
     }
     else
     {
     ?>
     <br><input type="submit" value="loeschen"/>  ausgew&auml;hlte Entleiher l&ouml;schen   <!-- löschbutton -->
     <?php
     }
?>
</form>
<big><big>
<br><br>Weiteren Entleiher eintragen:                               <!--neuen Benutzer anlegen -->
</big></big><br><br>
<form name="eingabe" action="user_bearbeiten.php" method="post" >
Name, Vorname:     <input type="text" name="ename">                 <!-- neuen Namen eingeben -->
<input type="submit" value="eintragen">
</form>
<form method="POST" action="user_bearbeiten.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
<?php  
/*
$anzahl = anzahl benutzereinträge
$ds = datenzähler
$ename = eingabe name vorname
$ew = log text
$heut = heute deu
$l = log eintrag
$name = name in benutzer
$pid = id in benutzer
$query / $result / $sql = variable in datenbankabfrage
$vorh = eintrag vorhanden
$zeile = anzahl zeilen in benutzer  
*/
?>
