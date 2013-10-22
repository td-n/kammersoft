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
<title>Info eintragen</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
M&auml;ngelliste eintragen
</big></big></big>
<br>
<?php
echo "Benutzer :", $_SESSION['kname'], "<br>";
$heut = date("d.m.Y");  //heutuger Datum
echo $heut;
$l="info_eintragen ge&ouml;ffnet";
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
    
if ( ! isset($_POST['mangel']))
{
    if ( ! isset($_POST['ausl2']))                                  // wenn Variable übergeben 1. if
    {                                                               // dann 1. if
        if (isset($_POST['ausl']))                                  //ist die Variable vorhanden bereits übergeben
        {                                                           // dann 2. if
            $ausl1 =  $_POST['ausl'] ;                              // Variable auslesen
            //echo $ausl1;                                          //Service
            $query    = "SELECT Typ FROM typ WHERE ID=$ausl1";      //aus Typ-ID Typ machen
             $resultID = @mysql_query($query);                      //aus Typ-ID Typ machen
             $nrtyp = mysql_result($resultID,0);
            ?>
            <p> F&uuml;r welche Nummer von <?php echo $nrtyp ?> soll ein M&auml;ngel eingetragen werden?  </p>         <!--  Variable vorhanden -->
            <p> Das Ger&auml;t darf nicht ausgeliehen sein </p>
            <form method = "POST" align="center">
            <select name = "ausl2" size="1">
            <?php
            $rn="0";
            $sql = mysql_query("SELECT * FROM geraete WHERE Typ = $ausl1 AND Rep_notw = $rn AND ausgeliehen= 0 ");    //Tabelle gereate auswählen diew nicht ausgeliehen sind
            while($ds= mysql_fetch_object($sql))
            { 
                $regnr=$ds->RegNR ;                                 // aus Datenbank auslesen
                $idg=$ds->ID;                                       // aus Datenbank auslesen
                echo "<option value = '$idg'>" .$regnr. "</option>"  ;  
            }                                                       //Dropdown mit ausgewählten schreiben
            ?>
            </select>
            <input name="submit" type="submit" value="ausw&auml;hlen" class="Button-w"/>  <!-- Abschicktaste -->
            </form>
            <?php
        }
        else
        {
             ?>
            <p><font color="#0A0AF0" ><big> F&uuml;r welchen Ger&auml;tetyp soll ein M&auml;ngel eingetragen werden?  </big><br></font></p> <!-- Variable nicht vorhanden -->
            <form method = "POST" align="center">
            <select name = "ausl" size="1" >
            <?php
            $sql = mysql_query("SELECT * FROM typ ORDER by TYP");   //Gerätetyp auswählen
              while($ds= mysql_fetch_object($sql))
            {   
                $typ = $ds->Typ ;                                   // aus Datenbank auslesen
                $id = $ds->ID ;
                echo "<option value = '$id'>" .$typ. "</option>"  ; // Dropdown schreiben  
            }             
            ?>
            </select>
            <input name="submit" type="submit" value="ausw&auml;hlen" class="Button-w"/>  <!-- Abschicktaste -->
            </form>
            <?php
        }                                                           // ende oder 2. if
    }                                                               // ende dann 1. if
    else                                                            // oder
    {
        $ausw = $_POST['ausl2'];                                    //Auswahl fertig
        $sql = mysql_query("SELECT * FROM geraete WHERE ID=$ausw"); //Tabelle gereate auswählen nur ausgewähltes Gerät 1 Wert
        while($ds= mysql_fetch_object($sql))
        {   
            $typa = $ds->Typ ;                                      // aus Datenbank auslesen
            $regnra=$ds ->RegNR ;                                   
            $herstellera= $ds->Hersteller ;                         
            $query    = "SELECT Typ FROM typ WHERE ID=$typa";       //aus Typ-ID Typ machen
            $resultID = @mysql_query($query);                       
            $typanz = mysql_result($resultID,0);                    
            // echo $typan;                                         //Service
            ?> 
            <table border="1" width="60%">
            <tr> <th>Typ</th><th>Nummer</th><th>Hersteller</th> </tr>
            <?php                                                   //in Tabelle ausgeben
            echo "<tr>" ;
            echo "<td>";
            echo $typanz,"</td>","<br>";
            echo "<td>";
            echo $regnra,"</td>","<br>";
            echo "<td>";
            echo $herstellera,"</td>","<br>";
//            echo "<td>";
            echo "</tr>";
        }                                                           //ende auslesen
        ?>
        </table>
        <?php    
        ?>
        <p><font color="#0A0AF0"><big>Für gew&auml;hltes Ger&auml;t eine M&auml;ngelinfo eintragen. </big><br></font></p>
        <form  method="POST">
        <textarea name= "mangel" cols="30" rows="6" > </textarea>       <!-- Mängeltextfeld -->
        <br><input type="checkbox" name="sperren" checked /><font color="#0A0AF0"><big> Ger&auml;t f&uuml;r die Ausleihe sperren </big><br></font>
        <input name="eintr" type="submit" value="eintragen"  class="Button-w" />        <!-- Abschicktaste -->
        </form>
        <?php
    }                                                               //ende 1.if
    if (isset($ausw))
    {
        if ($ausw <> "")
        {
            $eintr = "INSERT INTO `var` (`ingid`)VALUES ('$ausw')"; //ingid in var eintragen
            mysql_query($eintr);
        }
    }
}                                                                   //ende von 1. Zeile $_POST['mangel']
else                                                                
{                                                                   //oder
    if (isset ($_POST['sperren']))
    {
        $sperr=3;
    }
    else
    {
        $sperr=0;
    }



    $mang= $_POST['mangel'];
//echo $mang;                                                       //Service
    if ($mang <> "") 
    {
        $eintr = "INSERT INTO `var` (`mang`, `user`)VALUES ('$mang', $sperr)";      //Mängeltext in var eintragen
        mysql_query($eintr)  ;    
    }
    ?>
    <meta http-equiv="refresh" content="0; URL=infoeintragenvonWerten.php"> 
    <?php     
}                                                                   //ende von ausw abfrage
//mysql_close($ds);
?>
<br>
</form>
<form method="POST" action="info_eintragen.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
<?php  
/*
$ausl1 = ausleihtyp auslesen
ausl2 = ausleihnummr auslesen
$ds = datenzähler
$eintr = variable in datenbankabfrage
$herstellera = hersteller in geräte
$heut = heute in deu
$id = id in typ
$idg = id in geräte
$l = logeintrag
$mang = text mängel
$nrtyp = typnummer in typ
$query = variable in datenbankabfrage
$regnr = registriernummer in geräte
$regnra = registriernummer in geräte
$resultID = variable in datebankabfrage
$rn = variable rep notwendig
$sql = variable in datenbankabfrage
$typ = typnummer in typ
$typa = typnummer in geräte
$typanz = typtext in typ
*/
?>
