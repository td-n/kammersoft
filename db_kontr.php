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
<title>Datenbankkontrolle</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
Datenbankkontrolle<br>
</big></big></big>
<?php
echo "Benutzer :", $_SESSION['kname'], "<br>";
$heut = date("d.m.Y");  //heutuger Datum
echo $heut;
$l="Datenbankkontrolle ge&ouml;ffnet / ".$_SESSION['kname'];
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
if (isset ($_POST['wei']))
{
    ?><meta http-equiv="refresh" content="0; URL=db_kontr2.php" /><?php ;
}
?>
<br><br><font color="#0000FF"><big>
Die gesamte Datenbank besteht aus mehreren Tabellen. 
Die Haupttabelle ist die Tabelle "Ger&auml;te". 
3 weitere Tabellen - Ausleihe, M&auml;ngel und Reservierung 
beziehen sich auf Werte in der Ger&auml;tetabelle und umgedreht. 
Hier wurde und wird &uuml;berpr&uuml;ft, ob die Verbindungen zwischen der Haupttabelle und den 3 weiteren Tabellen noch existieren. 
Gegebenenfalls k&ouml;nnen Sie entscheiden, ob Sie einen Eintrag l&ouml;schen oder eine Verkn&uuml;pfung einf&uuml;gen wollen. </big><br></font>
<form name="aendern" action="db_kontr.php" method="post" >          <!--  Tabelle ändern schreiben -->
<table border="1" ><tr>
<?php 
    
if (isset($_POST['eintre']))
{
    if (isset($_POST['auslj']))
        {
            foreach ($_POST['auslj'] as $key => $val)
            {
//                echo "<br>",$key,"/",$val,"<br>";
                $query = "UPDATE `geraete` SET `ausgeliehen` = 1 WHERE `ID` =$val";  //lesen in user nach vorgabe setzen
                $resultID = @mysql_query($query);
            }
        }
        else
        {
            ?><font color="#FF0000"><big><big>kein Eintrag in der Ausleih-Datenbank bearbeitet oder nichts ausgew&auml;hlt</big></big><br></font><?php
                     
        }
    if (isset($_POST['res']))
        {
            foreach ($_POST['res'] as $key => $val)
            {
//                echo "<br>",$key,"/",$val,"<br>";
                $query = "UPDATE `geraete` SET `res` = 1 WHERE `ID` =$val";  //lesen in user nach vorgabe setzen
                $resultID = @mysql_query($query);
            }
        }
        else
        {
            ?><font color="#FF0000"><big><big>kein Eintrag in der Reservierungs-Datenbank bearbeitet oder nichts ausgew&auml;hlt</big></big><br></font><?php

        }
    if (isset($_POST['mang']))
        {
            foreach ($_POST['mang'] as $key => $val)
            {
//                echo "<br>",$key,"/",$val,"<br>";
                $query = "UPDATE `geraete` SET `Rep_notw` = 1 WHERE `ID` =$val";  //lesen in user nach vorgabe setzen
                $resultID = @mysql_query($query);
            }
        }
        else
        {
            ?><font color="#FF0000"><big><big>kein Eintrag in der M&auml;ngel-Datenbank bearbeitet oder nichts ausgew&auml;hlt</big></big><br></font><?php

        }
}
if (isset($_POST['eintrl']))
{
    if (isset($_POST['auslj']))
    {
        foreach ($_POST['auslj'] as $key => $val)
        {
            $query = "DELETE FROM `ausleihe` WHERE IDGeraet=$val AND abgeschlAusleihe=0 ";   //user in var löschen
            $resultID = @mysql_query($query);
        }
    }
        else
        {
            ?><font color="#FF0000"><big><big>kein Eintrag in der Ausleih-Datenbank bearbeitet oder nichts ausgew&auml;hlt</big></big></font><?php
        }
    if (isset($_POST['res']))
    {
        foreach ($_POST['res'] as $key => $val)
        {
            $query = "DELETE FROM `res` WHERE ID=$val ";   //user in var löschen
            $resultID = @mysql_query($query);
        }
    }
        else
        {
            ?><font color="#FF0000"><big><big>kein Eintrag in der Reservierungs-Datenbank bearbeitet oder nichts ausgew&auml;hlt</big></big></font><?php
        }
    if (isset($_POST['auslj']))
    {
        foreach ($_POST['auslj'] as $key => $val)
        {
            $query = "DELETE FROM `info` WHERE ID_Geraet=$val ";   //user in var löschen
            $resultID = @mysql_query($query);
        }
    }
        else
        {
            ?><font color="#FF0000"><big><big>kein Eintrag in der M&auml;ngel-Datenbank bearbeitet oder nichts ausgew&auml;hlt</big></big></font><?php
        }

}
$f=0;
$sql = mysql_query("SELECT * FROM ausleihe WHERE abgeschlAusleihe=0");   //Tabelle ausleihe auswählen 
while($ds= mysql_fetch_object($sql))
{
    $gid=$ds->IDGeraet;
    $auln=$ds->AuslName;
    $ausg=$ds->Ausgeber;
    $auslgr=$ds->auslgrund;
    $versch=$ds->verschiedenes;
    $dat_von=$ds->Datum_von;
    $dat_bis=$ds->Datum_bis;
    $jahr=$ds->jahr;
    $lfdnr=$ds->lfnr;
    if ($gid > 0)                                                   //ist es nicht verschiedenes?
    {
        $sql1 = mysql_query("SELECT * FROM geraete WHERE ID=$gid ");   //Tabelle geraete auswählen
        while($ds1= mysql_fetch_object($sql1))
        {
            $ausgl=$ds1->ausgeliehen;
            $typ=$ds1->Typ;
            $regnr=$ds1->RegNR;
        }
        $query    = "SELECT Typ FROM typ WHERE ID=$typ";                //aus Typ-ID Typ machen
        $resultID = @mysql_query($query);                               //aus Typ-ID Typ machen
        $typan = mysql_result($resultID,0);
    //    echo "<br>",$gid,"/",$ausgl,"/",$lfdnr;
        if ($ausgl==0)
            {
                $f=1;
                ?>
                <td>
                Ein Eintrag in der Ausleihe-Datenbank existiert, der aber keine Verkn&uuml;pfung mit dem dazugeh&ouml;rigen Ger&auml;t hat.
                <br>
                Eintrag: <?php echo $typan, $regnr, $versch, " / ", $ausg, " / ", $auslgr, " / von:", $dat_von," bis: ", $dat_bis, " / ", $jahr,"/",$lfdnr ?>
                </td>
                <?php     
                echo "<td><input type=\"checkbox\" name=\"auslj[]\" value= \" $gid \" />ja</td></tr>"; // häckchen setzen
            }
    }
}

$sql = mysql_query("SELECT * FROM res ");   //Tabelle ausleihe auswählen
while($ds= mysql_fetch_object($sql))
{
    $gid=$ds->gid;
    $auln=$ds->name;
    $auslgr=$ds->grund;
    $dat_von=$ds->von;
    $dat_bis=$ds->bis;
    $sql1 = mysql_query("SELECT * FROM geraete WHERE ID=$gid ");   //Tabelle geraete auswählen
    while($ds1= mysql_fetch_object($sql1))
    {
        $res=$ds1->res;
        $typ=$ds1->Typ;
        $regnr=$ds1->RegNR;
    }
    $query    = "SELECT Typ FROM typ WHERE ID=$typ";                //aus Typ-ID Typ machen
    $resultID = @mysql_query($query);                               //aus Typ-ID Typ machen
    $typan = mysql_result($resultID,0);
//    echo "<br>",$gid,"/",$ausgl,"/",$lfdnr;
    if ($res==0)
        {
            $f=1;
            ?>
            <td>
            Ein Eintrag in der Reservierungs-Datenbank existiert, der aber keine Verkn&uuml;pfung mit dem dazugeh&ouml;rigen Ger&auml;t hat.
            <br>
            Eintrag: <?php echo $typan, $regnr, " / ", $ausg, " / ", $auslgr, " / von:", $dat_von," bis: ", $dat_bis ?>
            </td>
            <?php
            echo "<td><input type=\"checkbox\" name=\"res[]\" value= \" $gid \" />ja</td></tr>"; // häckchen setzen
        }
}

$sql = mysql_query("SELECT * FROM info WHERE erl=0");   //Tabelle ausleihe auswählen
while($ds= mysql_fetch_object($sql))
{
    $gid=$ds->ID_Geraet;
    $mang=$ds->Maengel;
    $rueckg=$ds->Rueckgeber;
    $datum=$ds->Datum;
    $sql1 = mysql_query("SELECT * FROM geraete WHERE ID=$gid ");   //Tabelle geraete auswählen
    while($ds1= mysql_fetch_object($sql1))
    {
        $r_n=$ds1->Rep_notw;
        $typ=$ds1->Typ;
        $regnr=$ds1->RegNR;
    }
    $query    = "SELECT Typ FROM typ WHERE ID=$typ";                //aus Typ-ID Typ machen
    $resultID = @mysql_query($query);                               //aus Typ-ID Typ machen
    $typan = mysql_result($resultID,0);
    $query    = "SELECT kname FROM user WHERE ID=$rueckg";                //aus Typ-ID Typ machen
    $resultID = @mysql_query($query);                               //aus Typ-ID Typ machen
    $rueckga = mysql_result($resultID,0);

//    echo "<br>",$gid,"/",$ausgl,"/",$lfdnr;
    if ($r_n==0)
        {
            $f=1;
            ?>
            <td>
            Ein Eintrag in der M&auml;ngel-Datenbank existiert, der aber keine Verkn&uuml;pfung mit dem dazugeh&ouml;rigen Ger&auml;t hat.
            <br>
            Eintrag: <?php echo $typan, $regnr," / ", $mang, " / ", $rueckga, " / ", $datum ?>
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
    <br><input type="submit" name="eintre" value="Eintrag erstellen" class="Button-w"/> von ausgew&auml;hlten Ger&auml;ten den Eintrag in der Ger&auml;tedatenbank erstellen  <!-- button -->
    <?php  
    
    ?>
    <br><input type="submit" name="eintrl" value="Eintrag l&ouml;schen" class="Button-w"/> von ausgew&auml;hlten Ger&auml;ten den Eintrag in der Datenbank l&ouml;schen  <!-- button -->
    <?php
}
?>
</form>                                                                 
<form method="POST" action="db_kontr.php">
<input name="wei" type="submit" value="zur Kontrolle 2" class="Button-w"/><br/><br/>
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
