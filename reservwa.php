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
function date_mysql2german($date) {
    $d    =    explode("-",$date);
    return    sprintf("%02d.%02d.%04d", $d[2], $d[1], $d[0]);
}
function date_mysql2engl($date) {
    $d    =    explode(".",$date);
    return    sprintf("%04d-%02d-%02d", $d[2], $d[1], $d[0]); }
?>
<title>Res. umwandeln</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
Reservierung in Ausleihe umwandeln
</big></big></big><br>
<?php
echo "Benutzer :", $_SESSION['kname'], "<br>";                      //Benutzer anzeigen
$heut = date("d.m.Y");  //heutuger Datum
$heuteng= "" . date_mysql2engl($heut) . " \n";
echo $heut;
$l="reservwa ge&ouml;ffnet";
logsch ($l);
/*
while (list ($key, $value) = each ($_REQUEST))                      //alle Rückgabewerte ausgeben
{
  echo $key." => ".$value."<br />\n";
}
*/
if (isset ($_POST['zu']))
{
    ?><meta http-equiv="refresh" content="0; URL=start1.php" /><?php ;
}

?>                                                                  
<br>
<form method = "POST" action="reservwa.php">                        
<br>bitte Namen der Reservierung ausw&auml;hlen!<br>
<select name = "ausname" size="1">
<?php                                                               //nach dieser form diese seite wieder anspringen


$sql = mysql_query("SELECT DISTINCT name FROM res  ");              //Tabelle res auswählen - welche namen haben etwas ausgeliehen
while($ds= mysql_fetch_object($sql))
{
    $name = $ds->name ;
    if (!isset($aname))
    {
        $aname=$name;                                               //namen die etwas ausgeliehen haben
    }
    echo "<option value = '$name'>" .$name. "</option>"  ;          //Dropdown mit ausgewählten namen schreiben
}
?></select>
<input name="submit1" type="submit" value="&uuml;bernehmen" class="Button-w"/>      <!-- Abschicktaste -->
</form>
<?php

if ((isset($_POST['anz'])) AND  (isset($_POST['submit'])))          //ist eine checkbox ausgewählt und  taste umwandeln gedrückt
    {

    foreach ($_POST['anz'] as $key => $val)                         //welche eintrag wurden gewählt
        {
        $query    = "SELECT bis FROM res WHERE id=$val";            //bis aus eintrag machen
        $resultID = @mysql_query($query);
        $resb = mysql_result($resultID,0);
        }
        $eintr = "INSERT INTO `var` (`gida`,`user`) VALUES ( -4, '$resb')";  //bis in var eintragen
        mysql_query($eintr);
    foreach ($_POST['anz'] as $key => $val)                         //welche eintrag wurden gewählt
        {
        $query    = "SELECT gid FROM res WHERE id=$val";            //geräte-id aus eintrag machen
        $resultID = @mysql_query($query);                           
        $gida = mysql_result($resultID,0);
        $eintr = "INSERT INTO `var` (`gida`)VALUES ('$gida')";       //$gida in var eintragen
        mysql_query($eintr);
        $query = "DELETE FROM `res` WHERE id=$val ";                //Tabelle Werte löschen
        $resultID = @mysql_query($query);

        $sql="SELECT COUNT(*) AS Anzahl FROM res ";                     //bei leer 
        $result = mysql_query($sql);
        $zeile = @mysql_fetch_array($result);
        $anzahl = $zeile['Anzahl'];
        if ($anzahl == 0)
            {
            $query1 = "UPDATE `geraete` SET  `res` = '0' WHERE `geraete`.`ID` = $gida";                                                            //in geräte res löschen
            $resultID1 = @mysql_query($query1);
            }
        }
        ?><meta http-equiv="refresh" content="0; URL=Ausleihe2.php" /><?php
    }
if ((!isset($_POST['anz'])) AND (isset($_POST['submit'])))          //nichts ausgewählt und umwandeln gedrückt
    {
        ?> 
        <textarea  cols="110" rows="1" class="text-a" readonly>Sie haben nichts ausgew&auml;hlt. Bitte nochmals ausw&auml;hlen und &uuml;bernehmen</textarea><br/>
        <?php ;
    }
?>
<form action="reservwa.php" method="POST">
<input type="hidden" name="senden" value="1"/>
<table border="1" width="90%">
<tr> <th>Typ</th><th>Nummer</th><th>Hersteller</th><th>Bemerkung</th><th>Ausleihgrund</th><th>ausgeliehen</th><th>reserviert</th><th>ausleihen</th></tr>
<?php
if (isset($_POST['ausname']))                                       //ist ein name ausgewählt
{
    $resnam=$_POST['ausname'];                                      //ausgewählter name
    $query = "TRUNCATE `var`";                                      //Tabelle var Werte löschen
    $resultID = @mysql_query($query);
    $eintr = "INSERT INTO `var` (`user`, `gida`) VALUES ('$resnam', -1)";  //Name in var eintragen -1
    mysql_query($eintr)  ;
    $sql1 = mysql_query("SELECT * FROM res WHERE  name='$resnam' ");//wenn ausgewählt nur ausgewählten anzeigen
}
else
{
    ?>
    <font color="#0000FF"><big>zur Zeit werden alle Namen angezeigt, eine Auswahl ist erst m&ouml;glich, wemm ein Name ausgew&auml;hlt wurde! </big></font>
    <?php 
    $sql1 = mysql_query("SELECT * FROM res ORDER BY name ");        //sonst alle anzeigen - wenn nichts ausgewählt
}
    while($ds1= mysql_fetch_object($sql1))
    {
        $id=$ds1->id;                                               //tabelle aufbauen
        $gid=$ds1->gid;
        $resv=$ds1->von;
        $resb=$ds1->bis;
        $resn=$ds1->name;
        $grund=$ds1->grund;
        $von= "" . date_mysql2german($resv) . " \n";
        $bis= "" . date_mysql2german($resb) . " \n";
        $sql = mysql_query("SELECT * FROM geraete WHERE  ID='$gid' ");
        while($ds= mysql_fetch_object($sql))
        {
            $typ = $ds->Typ ;                                       // aus Datenbank auslesen
            $regnr= $ds->RegNR ;                                    
            $hersteller= $ds->Hersteller ;                       
            $bemerkung= $ds->Bemerk;	                          
            $ausgel=$ds->ausgeliehen;
        }
        $query    = "SELECT Typ FROM typ WHERE ID=$typ";            //aus Typ-ID Typ machen
        $resultID = @mysql_query($query);                           
        $typan = mysql_result($resultID,0);
        echo "<td>",$typan,"</td>";
        echo "<td>",$regnr,"</td>";
        if ($hersteller!="")
        {
        echo "<td>",$hersteller,"</td>";
        }
        else
        {
        echo "<td>"," - ","</td>";
        }
        if ($bemerkung!="")
        {
        echo "<td>",$bemerkung,"</td>";
        }
        else
        {
        echo "<td>"," - ","</td>";
        }
        echo "<td>",$grund,"</td>";
        if ($ausgel == "1")                                         //bei ausgeliehen auf rot setzen
        {
            ?>
            <td bgcolor =#FF6F6C >
            <input type="checkbox" name="anzei[]" checked disabled />
            <?php
        }
        else
        {
            ?>
            <td>
            <input type="checkbox" name="anzei[]" disabled />
            <?php
        }
        $reserv="reserviert f&uuml;r: ".$resn." - ".$von." bis ".$bis;  // aus Datenbank auslesen
        echo "<td>",$reserv,"</td>";

        if (($ausgel == "0") AND (isset($resnam)))                  //
        {
        echo "<td><input type=\"checkbox\" name=\"anz[]\" value= \" $id \" /></td>"; // löschhäckchen
        }
        else
        {
        echo "<td><input type=\"checkbox\" name=\"anz[]\" value= \" $id \" disabled /></td>"; // löschhäckchen
        }
        echo "</tr>";
    }

?>
</table>    
<?php
    
if (isset($resnam))
{
    $sql="SELECT COUNT(*) AS Anzahl FROM res WHERE  name='$resnam' "; //bei leer ausblenden
    $result = mysql_query($sql);
    $zeile = @mysql_fetch_array($result);
    $anzahl = $zeile['Anzahl'];
    if ($anzahl == 0)
        {
         ?>
         <p><input type="submit" value="umwandeln" name="submit" disabled="disabled" /> ausgew&auml;hlte Reservierungen werden in eine Ausleihe gewandt</p>
         <?php
         }
         else
         {
         ?>
         <p><input type="submit" value="umwandeln" name="submit" class="Button-w" /> ausgew&auml;hlte Reservierungen werden in eine Ausleihe gewandt</p>
         <?php
         }
}
else
{
         ?>
         <p><input type="submit" value="umwandeln" name="submit" disabled="disabled" /> ausgew&auml;hlte Reservierungen werden in eine Ausleihe gewandt</p>
         <?php
    
}
//mysql_close($dz);
?>
</form>
<form method="POST" action="reservwa.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>

<?php  
/*
$aname = variable für name in res
$anzahl = anzahl der zeilen in res
$ausgel = ausgeliehen in geräte
$bemerkung = bemerkung in geräte
$bis = bis in res deu
$d = variable in functionen
$date = übergabevariable in function
$ds / $ds1 = datenzähler
$eintr = variable in datenbankabfrage
$gid = geräte id in res
$gida = gewählte geräte id in res
$grund = grund in res
$hersteller = hersteller in geräte
$heut = heute in deu
$heuteng = heute in engl
$id = id in res
$key = variable in arrayabfrage
$l = log eintrag
$name = name in res
$query / $query1 = variable in datenbankabfrage
$regnr = regnummer in geräte
$resb = bis in res engl
$reserv = reservierungstext zur anzeige
$resn = name in res
$resnam = ausgewählter name
$result / $resultID / $resultID1 = variable in datenbankabfrage
$resv = von in res
$sql / $sql1 = variable in datenbankabfrage
$typ = Typnummer in geräte
$typan = typ name in typ
$val = variable in arrayabfrage
$von = von in res deu
$zeile = zeilenanzahl in res    
*/
?>
