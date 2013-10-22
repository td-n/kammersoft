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

<title>Typenliste</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
Ger&auml;teseite
</big></big></big>
<br>
<table border="1" width="80%">
<tr> <th>Typ</th><th>Nummer</th><th>Hersteller</th><th>Bemerkung</th><th>Rep. notw. </th><th>T&Uuml;V bis</th><th>ausgeliehen</th> </tr> 
<?php
echo "Benutzer :", $_SESSION['kname'], "<br>";                       //Benutzer anzeigen
$heut = date("d.m.Y");  //heutuger Datum
$heuteng= "" . date_mysql2engl($heut) . " \n";  
echo $heut;
$l="Typenliste-tuev ge&ouml;ffnet";
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
if (isset ($_POST['zur']))
{
    ?><meta http-equiv="refresh" content="0; URL=Typlisten-bearbeiten.php" /><?php ;
}

$sql = mysql_query("SELECT * FROM geraete ORDER BY TUEV ");         //Tabelle gereate auswählen
while($ds= mysql_fetch_object($sql))
{
    $typ = $ds->Typ ;                                               // aus Datenbank auslesen
    $regnr= $ds->RegNR ;                                            
    $hersteller= $ds->Hersteller ;                                  
    $bemerkung= $ds->Bemerk;	                                    
    $rep_notw= $ds->Rep_notw ;                                      
    $tuef=$ds->TUEV;	                                            
    $ausgel=$ds->ausgeliehen;                                       
    $tuefeng=$tuef ;
    if ($tuef == '0000-00-00') 
    {
        $tuef= "kein Eintrag"; 
    }                                                               //Wert 0 abblocken   
    else 
    {
        $tuef= "" . date_mysql2german($tuef) . " \n";               // Formatwandlung
    }            
    $query    = "SELECT Typ FROM typ WHERE ID=$typ";                //aus Typ-ID Typ machen
    $resultID = @mysql_query($query);                               //aus Typ-ID Typ machen
    $typan = mysql_result($resultID,0);
    echo "<td>";                                                    //ausgeben
    echo $typan,"<br>";
    echo "<td>";
    echo $regnr,"<br>";
    echo "<td>";
    echo $hersteller,"<br>";
    echo "<td>";
    echo $bemerkung,"<br>";
    if ($rep_notw == "1")                                           //bei Reparatur notwendig auf rot setzen 
    {
        ?>
        <td bgcolor =#FF6F6C >
        <input type="checkbox" name="anzei[]" checked disabled />
        <a href="info.php" >zur M&auml;ngelanzeige </a>
        <?php
    }
    else 
    {
        ?>
        <td>
        <input type="checkbox" name="anzei[]"  disabled />    
        <?php
    }
    if ($tuef== "kein Eintrag")                                     // wenn TÜV Termin vorbei auf rot setzen
    {
        ?> <td bgcolor =#0ABDFF > <?php 
    } 
    elseif($heuteng >= $tuefeng)                                                                
    {
        ?> <td bgcolor =#FF6F6C > <?php                                                
    }
    else 
    {
        ?> <td bgcolor =#3FFF00 > <?php 
    } ;
    echo $tuef,"<br>";
    //echo "<td>";                                                  //Service
    if ($ausgel == "1")                                             //bei ausgeliehen auf rot setzen
    {
        ?>
        <td bgcolor =#FF6F6C >
        <input type="checkbox" name="anzei[]" checked disabled />
        <!--<a href="start.php" >zur &Uuml;bersicht </a>            //vorbereitung zur anzeige an wem --!> 
        <?php
    }
    else 
    {
        ?>
        <td>
        <input type="checkbox" name="anzei[]" disabled />
        <?php
    }
    echo "</tr>";
    //echo $heut ;                                                  //Service
    //echo $tuef ;                                                  //Service
}
//mysql_close($dz);
?>
</table>
<form method="POST" action="Typlisten-tuev.php">
<br/><input name="zur" type="submit" value="zur Bearbeitung" class="Button-w"/><br/>
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>                   
<?php  
/*
$ausgel = ausgeliehen in geräte
$bemerkung = bemerkung in geräte
$d = variable in functionen
$date = übergabevariable in functionen
$ds = datenzähler in datenbankabfrage
$hersteller = hersteller in geräte
$heut = heute deu
$heuteng = heute engl
$l = log eintrag
$query / $resultID = datenbankvariable
$regnr = regnummer
$rep_notw = reparatur notwendig in geräte
$sql = varaible in datenbankabfrage
$tuef = tüv in geräte
$tuefeng = $tuef
$typ = typ nummer
$typan = typ text 
*/
?>
