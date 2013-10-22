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
<?php
function date_mysql2german($date) {
    $d    =    explode("-",$date);                                  //in deutsches Format wandeln
    return    sprintf("%02d.%02d.%04d", $d[2], $d[1], $d[0]);
}
function date_mysql2engl($date) {
    $d    =    explode(".",$date);
    return    sprintf("%04d-%02d-%02d", $d[2], $d[1], $d[0]); }
?>
<title>Packetausleihe</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
Packetausleihe
</big></big></big>
<br>
<form name="ausw" action="packausw.php" method="post" >
<br>
<?php
echo "Benutzer :", $_SESSION['kname'], "<br>";                      //Benutzer anzeigen
$heut = date("d.m.Y");  //heutuger Datum
$heuteng= "" . date_mysql2engl($heut) . " \n";
echo $heut;
$l="Ausleihe-m ge&ouml;ffnet";
logsch ($l);
/*
while (list ($key, $value) = each ($_REQUEST))                      //alle Rückgabewerte anzeigen
{
  echo $key." => ".$value."<br />\n";
}
*/
if (isset ($_POST['zu']))
{
    ?><meta http-equiv="refresh" content="0; URL=start1.php" /><?php ;
}

$paket1=0;
$paket2=0;
$paket3=0;
$paket4=0;
$sql = mysql_query("SELECT DISTINCT paketnr FROM paket ");           //Tabelle gereate auswählen
while($ds= mysql_fetch_object($sql))
{
    $pnr=$ds->paketnr;
    switch ($pnr)
    {
    case 1:
        $paket1=1;
    break;
    case 2:
        $paket2=1;
    break;
    case 3:
        $paket3=1;
    break;
    case 4:
        $paket4=1;
    break;
    default:
    }
}
//echo "<br><br>";
//echo "+",$paket1,$paket2,$paket3,$paket4;
?>
<table border="1" width="80%"align="center">
<tr><td>
<?php  
?> <font color="#0000FF"><big><big>Paket1</big></big><br></font><br><?php 
if ($paket1==0)
   {
        ?> <font color="#FF0000"><big><big>noch kein Paket definiert</big></big><br></font><br>
        <input type="submit" name="neu" value="Paket 1 eintragen">
        <input type="submit" name="neu" value="Paket 1 ausleihen" disabled>
        <input type="submit" name="neu" value="Paket 1 l&ouml;schen" disabled><?php 
   } 
   else
   {
        $ausg=0;
        $sql1 = mysql_query("SELECT * FROM paket WHERE paketnr = 1 ORDER BY typ DESC, regnr" );            //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
        while($ds1 = mysql_fetch_object($sql1))
            {
                $typ = $ds1->typ ;                                        //aus Datenbank var auslesen
                $regnr=$ds1->regnr;
                $gid=$ds1->geraeteid;
                $versch=$ds1->verschiedenes;
                if ($gid>0)
                {
                    $sql2 = mysql_query("SELECT * FROM geraete WHERE ID=$gid ");    //Tabelle gereate auswählen
                    while($ds3= mysql_fetch_object($sql2))
                    {
                         $ausgel=$ds3->ausgeliehen;
                         if ($ausgel==1)
                         {
                            $ausg=1;
                         }
                    }
                }
                if ($ausgel==0)
                {
                    $ausgelt=" - nicht augeliehen";
                }
                else
                {
                    $ausgelt=" - ausgeliehen";
                }
                if (!$gid>0)
                {
                    $ausgelt=" - unbekannt";
                }
                if ($ausgel==1)
                {
                    ?><font color="#FB0000"><big><?php
                    echo $typ," ",$regnr," "," ",$versch," ",$ausgelt;
                    ?></big><br></font><?php
                }
                if (!$gid>0)
                {
                    ?><font color="#FF8A00"><big><?php
                    echo $typ," ",$regnr," "," ",$versch," ",$ausgelt;
                    ?></big><br></font><?php
                }
                if (($ausgel==0)AND($gid>0))
                {
                    ?><font color="#008F00"><big><?php
                    echo $typ," ",$regnr," "," ",$versch," ",$ausgelt;
                    ?></big><br></font><?php
                }
            }
        ?>
        <input type="submit" name="neu" value="Paket 1 eintragen" disabled>
        <?php 
        if ($ausg==0)
        {
        ?>
        <input type="submit" name="neu" value="Paket 1 ausleihen">
        <?php
        }
        else
        {
        ?>
        <input type="submit" name="neu" value="Paket 1 ausleihen" disabled>
        <?php
        }
        ?>
        <input type="submit" name="neu" value="Paket 1 l&ouml;schen">
       <?php 
   }
?>
</td><td>
<?php
?> <font color="#0000FF"><big><big>Paket 2</big></big><br></font><br><?php
if ($paket2==0)
   {
        ?> <font color="#FF0000"><big><big>noch kein Paket definiert</big></big><br></font><br>
        <input type="submit" name="neu" value="Paket 2 eintragen">
        <input type="submit" name="neu" value="Paket 2 ausleihen" disabled>
        <input type="submit" name="neu" value="Paket 2 l&ouml;schen" disabled><?php
   }
   else
   {
        $ausg=0;
        $sql1 = mysql_query("SELECT * FROM paket WHERE paketnr = 2 ORDER BY typ DESC, regnr");            //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
        while($ds1 = mysql_fetch_object($sql1))
            {
                $typ = $ds1->typ ;                                        //aus Datenbank var auslesen
                $regnr=$ds1->regnr;
                $gid=$ds1->geraeteid;
                $versch=$ds1->verschiedenes;

                if ($gid>0)
                {
                    $sql2 = mysql_query("SELECT * FROM geraete WHERE ID=$gid ");    //Tabelle gereate auswählen
                    while($ds3= mysql_fetch_object($sql2))
                    {
                         $ausgel=$ds3->ausgeliehen;
                         if ($ausgel==1)
                         {
                            $ausg=1;
                         }
                    }
                }
                if ($ausgel==0)
                {
                    $ausgelt=" - nicht augeliehen";
                }
                else
                {
                    $ausgelt=" - ausgeliehen";
                }
                if (!$gid>0)
                {
                    $ausgelt=" - unbekannt";
                }
                if ($ausgel==1)
                {
                    ?><font color="#FB0000"><big><?php
                    echo $typ," ",$regnr," "," ",$versch," ",$ausgelt;
                    ?></big><br></font><?php
                }
                if (!$gid>0)
                {
                    ?><font color="#FF8A00"><big><?php
                    echo $typ," ",$regnr," "," ",$versch," ",$ausgelt;
                    ?></big><br></font><?php
                }
                if (($ausgel==0)AND($gid>0))
                {
                    ?><font color="#008F00"><big><?php
                    echo $typ," ",$regnr," "," ",$versch," ",$ausgelt;
                    ?></big><br></font><?php
                }
            }
       ?>
        <input type="submit" name="neu" value="Paket 2 eintragen" disabled>
        <?php
        if ($ausg==0)
        {
        ?>
        <input type="submit" name="neu" value="Paket 2 ausleihen">
        <?php
        }
        else
        {
        ?>
        <input type="submit" name="neu" value="Paket 2 ausleihen" disabled>
        <?php
        }
        ?>
        <input type="submit" name="neu" value="Paket 2 l&ouml;schen">
       <?php
   }
?>
</td></tr><tr><td>
<?php
?> <font color="#0000FF"><big><big>Paket 3</big></big><br></font><br><?php
if ($paket3==0)
   {
        ?> <font color="#FF0000"><big><big>noch kein Paket definiert</big></big><br></font><br>
        <input type="submit" name="neu" value="Paket 3 eintragen">
        <input type="submit" name="neu" value="Paket 3 ausleihen" disabled>
        <input type="submit" name="neu" value="Paket 3 l&ouml;schen" disabled><?php
   }
   else
   {
        $ausg=0;
        $sql1 = mysql_query("SELECT * FROM paket WHERE paketnr = 3 ORDER BY typ DESC, regnr");            //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
        while($ds1 = mysql_fetch_object($sql1))
            {
                $typ = $ds1->typ ;                                        //aus Datenbank var auslesen
                $regnr=$ds1->regnr;
                $gid=$ds1->geraeteid;
                $versch=$ds1->verschiedenes;
                if ($gid>0)
                {
                    $sql2 = mysql_query("SELECT * FROM geraete WHERE ID=$gid ");    //Tabelle gereate auswählen
                    while($ds3= mysql_fetch_object($sql2))
                    {
                         $ausgel=$ds3->ausgeliehen;
                         if ($ausgel==1)
                         {
                            $ausg=1;
                         }
                    }
                }
                if ($ausgel==0)
                {
                    $ausgelt=" - nicht augeliehen";
                }
                else
                {
                    $ausgelt=" - ausgeliehen";
                }
                if (!$gid>0)
                {
                    $ausgelt=" - unbekannt";
                }
                if ($ausgel==1)
                {
                    ?><font color="#FB0000"><big><?php
                    echo $typ," ",$regnr," "," ",$versch," ",$ausgelt;
                    ?></big><br></font><?php
                }
                if (!$gid>0)
                {
                    ?><font color="#FF8A00"><big><?php
                    echo $typ," ",$regnr," "," ",$versch," ",$ausgelt;
                    ?></big><br></font><?php
                }
                if (($ausgel==0)AND($gid>0))
                {
                    ?><font color="#008F00"><big><?php
                    echo $typ," ",$regnr," "," ",$versch," ",$ausgelt;
                    ?></big><br></font><?php
                }
            }
       ?>
        <input type="submit" name="neu" value="Paket 3 eintragen" disabled>
        <?php
        if ($ausg==0)
        {
        ?>
        <input type="submit" name="neu" value="Paket 3 ausleihen">
        <?php
        }
        else
        {
        ?>
        <input type="submit" name="neu" value="Paket 3 ausleihen" disabled>
        <?php
        }
        ?>
        <input type="submit" name="neu" value="Paket 3 l&ouml;schen">
       <?php
   }
?>
</td><td>
<?php
?> <font color="#0000FF"><big><big>Paket 4</big></big><br></font><br><?php
if ($paket4==0)
   {
        ?> <font color="#FF0000"><big><big>noch kein Paket definiert</big></big><br></font><br>
        <input type="submit" name="neu" value="Paket 4 eintragen">
        <input type="submit" name="neu" value="Paket 4 ausleihen" disabled>
        <input type="submit" name="neu" value="Paket 4 l&ouml;schen" disabled><?php
   }
   else
   {
        $ausg=0;
        $sql1 = mysql_query("SELECT * FROM paket WHERE paketnr = 4 ORDER BY typ DESC, regnr");            //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
        while($ds1 = mysql_fetch_object($sql1))
            {
                $typ = $ds1->typ ;                                        //aus Datenbank var auslesen
                $regnr=$ds1->regnr;
                $gid=$ds1->geraeteid;
                $versch=$ds1->verschiedenes;
                if ($gid>0)
                {
                    $sql2 = mysql_query("SELECT * FROM geraete WHERE ID=$gid ");    //Tabelle gereate auswählen
                    while($ds3= mysql_fetch_object($sql2))
                    {
                         $ausgel=$ds3->ausgeliehen;
                         if ($ausgel==1)
                         {
                            $ausg=1;
                         }
                    }
                }
                if ($ausgel==0)
                {
                    $ausgelt=" - nicht augeliehen";
                }
                else
                {
                    $ausgelt=" - ausgeliehen";
                }
                if (!$gid>0)
                {
                    $ausgelt=" - unbekannt";
                }
                if ($ausgel==1)
                {
                    ?><font color="#FB0000"><big><?php
                    echo $typ," ",$regnr," "," ",$versch," ",$ausgelt;
                    ?></big><br></font><?php
                }
                if (!$gid>0)
                {
                    ?><font color="#FF8A00"><big><?php
                    echo $typ," ",$regnr," "," ",$versch," ",$ausgelt;
                    ?></big><br></font><?php
                }
                if (($ausgel==0)AND($gid>0))
                {
                    ?><font color="#008F00"><big><?php
                    echo $typ," ",$regnr," "," ",$versch," ",$ausgelt;
                    ?></big><br></font><?php
                }
            }
       ?>
        <input type="submit" name="neu" value="Paket 4 eintragen" disabled>
        <?php
        if ($ausg==0)
        {
        ?>
        <input type="submit" name="neu" value="Paket 4 ausleihen">
        <?php
        }
        else
        {
        ?>
        <input type="submit" name="neu" value="Paket 4 ausleihen" disabled>
        <?php
        }
        ?>
        <input type="submit" name="neu" value="Paket 4 l&ouml;schen">
       <?php
   }
?>
</td></tr>
<?php

if (isset ($_POST['neu']))
{
    switch($_POST['neu'])
    {
        case"Paket 1 eintragen":                                  //eintragen

            $eintr = "INSERT INTO `var` (`user`,`gida`)VALUES ('1','-9')";      //paketnummer in var eintragen
            mysql_query($eintr);
            ?><meta http-equiv="refresh" content="0; URL=paketeintr.php"><?php
        break;
        case"Paket 2 eintragen":                                  //eintragen
            $eintr = "INSERT INTO `var` (`user`,`gida`)VALUES ('2','-9')";      //paketnummer in var eintragen
            mysql_query($eintr);
            ?><meta http-equiv="refresh" content="0; URL=paketeintr.php"><?php
        break;
        case"Paket 3 eintragen":                                  //eintragen
            $eintr = "INSERT INTO `var` (`user`,`gida`)VALUES ('3','-9')";      //paketnummer in var eintragen
            mysql_query($eintr);
            ?><meta http-equiv="refresh" content="0; URL=paketeintr.php"><?php
        break;
        case"Paket 4 eintragen":                                  //eintragen
            $eintr = "INSERT INTO `var` (`user`,`gida`)VALUES ('4','-9')";      //paketnummer in var eintragen
            mysql_query($eintr);
            ?><meta http-equiv="refresh" content="0; URL=paketeintr.php"><?php
        break;
        case"Paket 1 ausleihen":                                  //auswahl ausleihen
            $sql1 = mysql_query("SELECT * FROM paket WHERE paketnr = 1 ORDER BY typ DESC, regnr");            //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
            while($ds1 = mysql_fetch_object($sql1))
                {
                    $gid=$ds1->geraeteid;
                    $versch=$ds1->verschiedenes;
                    if($gid<>"")
                    {
                        $eintr = "INSERT INTO `var` (`gida`)VALUES ('$gid')";      //$gida in var eintragen
                        mysql_query($eintr);
                    }
                    if($versch<>"")
                    {
                        $eintr1 = "INSERT INTO `var` (`mang`)VALUES ('$versch')";      //$gida in var eintragen
                        mysql_query($eintr1);
                    }
                    ?><meta http-equiv="refresh" content="0; URL=Ausleihe-m3.php"><?php
                }
        break;
        case"Paket 2 ausleihen":                                  //auswahl ausleihen
            $sql1 = mysql_query("SELECT * FROM paket WHERE paketnr = 2 ORDER BY typ DESC, regnr");            //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
            while($ds1 = mysql_fetch_object($sql1))
                {
                    $gid=$ds1->geraeteid;
                    $versch=$ds1->verschiedenes;
                    if($gid<>"")
                    {
                        $eintr = "INSERT INTO `var` (`gida`)VALUES ('$gid')";      //$gida in var eintragen
                        mysql_query($eintr);
                    }
                    if($versch<>"")
                    {
                        $eintr1 = "INSERT INTO `var` (`mang`)VALUES ('$versch')";      //$gida in var eintragen
                        mysql_query($eintr1);
                    }
                    ?><meta http-equiv="refresh" content="0; URL=Ausleihe-m3.php"><?php
                }
        break;
        case"Paket 3 ausleihen":                                  //auswahl ausleihen
            $sql1 = mysql_query("SELECT * FROM paket WHERE paketnr = 3 ORDER BY typ DESC, regnr");            //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
            while($ds1 = mysql_fetch_object($sql1))
                {
                    $gid=$ds1->geraeteid;
                    $versch=$ds1->verschiedenes;
                    if($gid<>"")
                    {
                        $eintr = "INSERT INTO `var` (`gida`)VALUES ('$gid')";      //$gida in var eintragen
                        mysql_query($eintr);
                    }
                    if($versch<>"")
                    {
                        $eintr1 = "INSERT INTO `var` (`mang`)VALUES ('$versch')";      //$gida in var eintragen
                        mysql_query($eintr1);
                    }
                    ?><meta http-equiv="refresh" content="0; URL=Ausleihe-m3.php"><?php
                }
        break;
        case"Paket 4 ausleihen":                                  //auswahl ausleihen
            $sql1 = mysql_query("SELECT * FROM paket WHERE paketnr = 4 ORDER BY typ DESC, regnr");            //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
            while($ds1 = mysql_fetch_object($sql1))
                {
                    $gid=$ds1->geraeteid;
                    $versch=$ds1->verschiedenes;
                    if($gid<>"")
                    {
                        $eintr = "INSERT INTO `var` (`gida`)VALUES ('$gid')";      //$gida in var eintragen
                        mysql_query($eintr);
                    }
                    if($versch<>"")
                    {
                        $eintr1 = "INSERT INTO `var` (`mang`)VALUES ('$versch')";      //$gida in var eintragen
                        mysql_query($eintr1);
                    }
                    ?><meta http-equiv="refresh" content="0; URL=Ausleihe-m3.php"><?php
                }
        break;
       case"Paket 1 löschen":                                  //auswahl löschen
            $query = "DELETE FROM `paket` WHERE paketnr=1 "; //Tabelle Paket löschen
            $resultID = @mysql_query($query);
            ?><meta http-equiv="refresh" content="0; URL=packausw.php"><?php
        break;
        case"Paket 2 löschen":                                  //auswahl löschen
            $query = "DELETE FROM `paket` WHERE paketnr=2 "; //Tabelle Paket löschen
            $resultID = @mysql_query($query);
            ?><meta http-equiv="refresh" content="0; URL=packausw.php"><?php
        break;
        case"Paket 3 löschen":                                  //auswahl löschen
            $query = "DELETE FROM `paket` WHERE paketnr=3 "; //Tabelle Paket löschen
            $resultID = @mysql_query($query);
            ?><meta http-equiv="refresh" content="0; URL=packausw.php"><?php
        break;
        case"Paket 4 löschen":                                  //auswahl löschen
            $query = "DELETE FROM `paket` WHERE paketnr=4 "; //Tabelle Paket löschen
            $resultID = @mysql_query($query);
            ?><meta http-equiv="refresh" content="0; URL=packausw.php"><?php
        break;
        default:
    }
}
?>
</table>
</form>
<form method="POST" action="packausw.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
