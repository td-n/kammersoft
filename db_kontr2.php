<html>
<head>
<?php include ("#authenticated.inc.php"); ?>
<?php include ("#mysql.inc.php"); ?>
<?php include ("#log.inc.php") ?>
<?php include ("style.php") ?>
<?php
if ($_SESSION['lesen']==0)
{
    ?><meta http-equiv="refresh" content="0; URL=forbitten-all.php" /><?php
}
?>
<?php
if ($_SESSION['schreiben']==0)
{
    ?><meta http-equiv="refresh" content="0; URL=forbitten.php" /><?php
}
?>
<title>Datenbankkontrolle2</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
Datenbankkontrolle2<br>
</big></big></big>
<?php
echo "Benutzer :", $_SESSION['kname'], "<br>";
$heut = date("d.m.Y");  //heutuger Datum
echo $heut;
$l="Datenbankkontrolle2 ge&ouml;ffnet / ".$_SESSION['kname'];
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
<br><br><font color="#0000FF"><big>
Die gesamte Datenbank besteht aus mehreren Tabellen. 
Die Haupttabelle ist die Tabelle "Ger&auml;te". 
3 weitere Tabellen - Ausleihe, M&auml;ngel und Reservierung 
beziehen sich auf Werte in der Ger&auml;tetabelle und umgedreht. 
Hier wurde und wird &uuml;berpr&uuml;ft, ob die Verbindungen zwischen der Haupttabelle und den 3 weiteren Tabellen noch existieren. 
Gegebenenfalls k&ouml;nnen Sie hier den Eintrag l&ouml;schen, da die Angaben zur Reservierung nicht mehr existieren . </big><br></font>
<form name="aendern" action="db_kontr2.php" method="post" >          <!--  Tabelle ändern schreiben -->
<table border="1" ><tr>
<?php 
if (isset($_POST['eintrl']))
{
    if (isset($_POST['auslj']))
        {
            foreach ($_POST['auslj'] as $key => $val)
            {
//                echo "<br>",$key,"/",$val,"<br>";
                $query = "UPDATE `geraete` SET `ausgeliehen` = 0 WHERE `ID` =$val";  //lesen in user nach vorgabe setzen
                $resultID = @mysql_query($query);
            }
        }
        else
        {
            ?><font color="#FF0000"><big><big>kein Eintrag in der Ger&auml;te-Datenbank bearbeitet oder nichts ausgew&auml;hlt</big></big><br></font><?php
        }
    if (isset($_POST['res']))
        {
            foreach ($_POST['res'] as $key => $val)
            {
//                echo "<br>",$key,"/",$val,"<br>";
                $query = "UPDATE `geraete` SET `res` = 0 WHERE `ID` =$val";  //lesen in user nach vorgabe setzen
                $resultID = @mysql_query($query);
            }
        }
        else
        {
            ?><font color="#FF0000"><big><big>kein Eintrag in der Ger&auml;te-Datenbank bearbeitet oder nichts ausgew&auml;hlt</big></big><br></font><?php
        }
    if (isset($_POST['mang']))
        {
            foreach ($_POST['mang'] as $key => $val)
            {
//                echo "<br>",$key,"/",$val,"<br>";
                $query = "UPDATE `geraete` SET `Rep_notw` = 0 WHERE `ID` =$val";  //lesen in user nach vorgabe setzen
                $resultID = @mysql_query($query);
            }
        }
        else
        {
            ?><font color="#FF0000"><big><big>kein Eintrag in der Ger&auml;te-Datenbank bearbeitet oder nichts ausgew&auml;hlt</big></big><br></font><?php
        }
}
$f=0;

$sql1 = mysql_query("SELECT * FROM geraete WHERE ausgeliehen=1 ");   //Tabelle geraete mit Ausleihtabelle vergleichen
while($ds1= mysql_fetch_object($sql1))
{
    $gid=$ds1->ID;
    $typ=$ds1->Typ;
    $regnr=$ds1->RegNR;
    $query    = "SELECT Typ FROM typ WHERE ID=$typ";                //aus Typ-ID Typ machen
    $resultID = @mysql_query($query);                               //aus Typ-ID Typ machen
    $typan = mysql_result($resultID,0);
//    echo $gid,"<br>";
    $sql = mysql_query("SELECT * FROM ausleihe WHERE IDGeraet=$gid AND abgeschlAusleihe=0 ");   //Tabelle ausleihe auswählen
    while($ds= mysql_fetch_object($sql))
    {
        $gid1=$ds->IDGeraet;
//        echo $gid1,"+","<br>";
    }
        if (!isset($gid1))
            {
                $f=2;
                ?>
                <td>
                Ein Eintrag in der Ger&auml;te-Datenbank existiert, der aber kein Eintrag in der Ausleih-Datenbank hat.
                <br>
                Eintrag: <?php echo $typan, $regnr ?>
                </td>
                <?php
                echo "<td><input type=\"checkbox\" name=\"auslj[]\" value= \" $gid \" />ja</td></tr>"; // häckchen setzen
            }
}

$sql1 = mysql_query("SELECT * FROM geraete WHERE res=1 ");   //Tabelle geraete mit Reservierungstabelle vergleichen
while($ds1= mysql_fetch_object($sql1))
{
    $gid=$ds1->ID;
    $typ=$ds1->Typ;
    $regnr=$ds1->RegNR;
    $query    = "SELECT Typ FROM typ WHERE ID=$typ";                //aus Typ-ID Typ machen
    $resultID = @mysql_query($query);                               //aus Typ-ID Typ machen
    $typan = mysql_result($resultID,0);
//    echo $gid;
    $sql = mysql_query("SELECT * FROM res WHERE gid= $gid ");   //Tabelle ausleihe auswählen
    while($ds= mysql_fetch_object($sql))
    {
        $gid1=$ds->gid;
//        echo $gid1;
    }

    if (!isset($gid1))
    {
        $f=2;
        ?>
        <td>
        Ein Eintrag in der Ger&auml;te-Datenbank existiert, der aber kein Eintrag in der Reservierungs-Datenbank hat.
        <br>
        Eintrag: <?php echo $typan, $regnr ?>
        </td>
        <?php
        echo "<td><input type=\"checkbox\" name=\"res[]\" value= \" $gid \" />ja</td></tr>"; // häckchen setzen
    }
}

$sql1 = mysql_query("SELECT * FROM geraete WHERE Rep_notw=1 ");   //Tabelle geraete mit Mängeltabelle vergleichen
while($ds1= mysql_fetch_object($sql1))
{
    $gid=$ds1->ID;
    $typ=$ds1->Typ;
    $regnr=$ds1->RegNR;
    $query    = "SELECT Typ FROM typ WHERE ID=$typ";                //aus Typ-ID Typ machen
    $resultID = @mysql_query($query);                               //aus Typ-ID Typ machen
    $typan = mysql_result($resultID,0);
//    echo $gid,"<br>";
    $sql = mysql_query("SELECT * FROM info WHERE ID_Geraet = $gid AND erl = 0 ");   //Tabelle ausleihe auswählen
    while($ds= mysql_fetch_object($sql))
    {
        $gid1=$ds->ID_Geraet;
//        echo $gid1,"++","<br>";
    }
    if (!isset($gid1))
    {
        $f=2;
        ?>
        <td>
        Ein Eintrag in der Ger&auml;te-Datenbank existiert, der aber kein Eintrag in der M&auml;ngel-Datenbank hat.
        <br>
        Eintrag: <?php echo $typan, $regnr ?>
        </td>
        <?php
        echo "<td><input type=\"checkbox\" name=\"mang[]\" value= \" $gid \" />ja</td></tr>"; // häckchen setzen
    }
}
?>

</table>
<?php
if ($f==0)
{
    ?><font color="#FF0000"><big><big>In der Datenbank existieren offensichtlich keine falschen Eintr&auml;ge (mehr).</big></big></font><?php    
}
else
{
    ?>
    <br><input type="submit" name="eintrl" value="Eintrag l&ouml;schen" class="Button-w"/> von ausgew&auml;hlten Ger&auml;ten den Eintrag in der Datenbank l&ouml;schen  <!-- button -->
    <?php
}
?>
</form>                                                                 
<form method="POST" action="db_kontr2.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
