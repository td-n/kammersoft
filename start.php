<html>
<head>
<?php include ("#authenticated.inc.php"); ?>
<?php include ("#log.inc.php"); ?>
<?php include ("style.php") ?>
<?php
function date_mysql2engl($date) {
    $d    =    explode(".",$date);                                  //in englisches Format wandeln
    return    sprintf("%04d-%02d-%02d", $d[2], $d[1], $d[0]); }
if ($_SESSION['lesen']==0)
{
    ?><meta http-equiv="refresh" content="0; URL=forbitten-all.php" /><?php
}
echo "Benutzer :", $_SESSION['kname'], "<br>";                       //Benutzer anzeigen
$heut = date("d.m.Y");  //heutuger Datum
$heuteng= "" . date_mysql2engl($heut) . " \n";
echo $heut,"<br>";
/*
while (list ($key, $value) = each ($_REQUEST))                      //alle Rückgabewerte auslesen
{
  echo $key." => ".$value."<br />\n";
}
*/
if (isset($_POST['start']))
{
    switch($_POST['start'])
    {                                                       //Gerät löschen
        case"Ausleihe":
            ?><meta http-equiv="refresh" content="0; URL=Ausleihe-m.php" /><?php
        break;
        case"Rückgabe":
            ?><meta http-equiv="refresh" content="0; URL=Rueckgabe.php" /><?php
        break;
        case"vorhandenes Material anzeigen":
            ?><meta http-equiv="refresh" content="0; URL=vorhand_material.php" /><?php
        break;
        case"Ausleihe verschiedenes":
            ?><meta http-equiv="refresh" content="0; URL=Ausleihe-v1.php" /><?php
        break;
        case"Ausgeliehenes Material anzeigen":
            ?><meta http-equiv="refresh" content="0; URL=ausgel_mat.php" /><?php
        break;
        case"Ausleihhistorie anzeigen":
            ?><meta http-equiv="refresh" content="0; URL=ausgel_hist.php" /><?php
        break;
        case"ausgeliehenes Material nach Ausleihgrund anzeigen":
            ?><meta http-equiv="refresh" content="0; URL=Ausleihgrund.php" /><?php
        break;
        case"Namen in Ausleihnummer tauschen":
            ?><meta http-equiv="refresh" content="0; URL=namtausch.php" /><?php
        break;
        case"Geräteliste anzeigen":
            ?><meta http-equiv="refresh" content="0; URL=Typlisten.php" /><?php
        break;
        case"Mängelinfo eintragen":
            ?><meta http-equiv="refresh" content="0; URL=Info_eintragen.php" /><?php
        break;
        case"Reservieren":
            ?><meta http-equiv="refresh" content="0; URL=kontr_res.php" /><?php
        break;
        case"Mängelinfo anzeigen und löschen":
            ?><meta http-equiv="refresh" content="0; URL=Info.php" /><?php
        break;
        case"Reservierungen anzeigen und aufheben":
            ?><meta http-equiv="refresh" content="0; URL=reserva.php" /><?php
        break;
        case"Mängelhistorie anzeigen":
            ?><meta http-equiv="refresh" content="0; URL=infohist.php" /><?php
        break;
        case"Reservierung in Ausleihe wandeln":
            ?><meta http-equiv="refresh" content="0; URL=reservwa.php" /><?php
        break;
        case"Einsätze von Reglern und Computern anzeigen":
            ?><meta http-equiv="refresh" content="0; URL=reglereinsatz.php" /><?php
        break;
        case"Entleiher bearbeiten":
            ?><meta http-equiv="refresh" content="0; URL=user_bearbeiten.php" /><?php
        break;
        case"TÜV-Termin ändern":
            ?><meta http-equiv="refresh" content="0; URL=tuev-aendern.php" /><?php
        break;
        case"Paketauswahl":
            ?><meta http-equiv="refresh" content="0; URL=packausw.php" /><?php
        break;
        case"beenden":
            ?><meta http-equiv="refresh" content="0; URL=ende.php" /><?php
        break;
        case"Logout":
            ?><meta http-equiv="refresh" content="0; URL=login.php" /><?php
        break;
        case"Techniker bearbeiten":
            ?><meta http-equiv="refresh" content="0; URL=Ausgeber.php" /><?php
        break;
        case"Geräteliste bearbeiten":
            ?><meta http-equiv="refresh" content="0; URL=Typlisten-bearbeiten.php" /><?php
        break;
        case"Archiv bearbeiten":
            ?><meta http-equiv="refresh" content="0; URL=Archiv.php" /><?php
        break;
        case"Mängelhistorie bearbeiten":
            ?><meta http-equiv="refresh" content="0; URL=info-loeschen.php" /><?php
        break;
        case"Datenbestände miteinander vergleichen":
            ?><meta http-equiv="refresh" content="0; URL=vergleich-start.php" /><?php
        break;
        case"Log-Datei ansehen":
            ?><meta http-equiv="refresh" content="0; URL=log.php" /><?php
        break;
        case"Inventurliste":
            ?><meta http-equiv="refresh" content="0; URL=invhist.php" /><?php
        break;
        case"Zusatzliste bearbeiten":
            ?><meta http-equiv="refresh" content="0; URL=Typlisten-ib.php" /><?php
        break;
        case"Updates ausführen":
            ?><meta http-equiv="refresh" content="0; URL=update.php" /><?php
        break;
        case"zur Spieldatenbank":
            ?><meta http-equiv="refresh" content="0; URL=../tauchkammers/index.php" /><?php
        break;
        case"Kontrolle der Datenbank":
            ?><meta http-equiv="refresh" content="0; URL=db_kontr.php" /><?php
        break;
        case"Konfiguration bearbeiten":
            ?><meta http-equiv="refresh" content="0; URL=conf_bearb.php" /><?php
        break;
        case"Ausleihe":
            ?><meta http-equiv="refresh" content="0; URL=Ausleihe-m.php" /><?php
        break;
        case"Ausleihe":
            ?><meta http-equiv="refresh" content="0; URL=Ausleihe-m.php" /><?php
        break;
        default:
    }
}
?>
<title>&Uuml;bersicht</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big>
Was wollen Sie tun bzw. anzeigen ? <br><br>
</big></big>
<?php
if($_SESSION['lesen'] == true)
{
?>
<form name="start" action="start.php" method="post">
<table border="0" width="100%">
<tr align="center"><td>
</td>
<td>
<input style="background-color:#00D8F8;color:#000000" type="submit" name="start" value="R&uuml;ckgabe" class="Button-s"/>
</td>
</tr>
<tr align="center"><td>
<input style="background-color:#FF8F00;color:#000000" type="submit" name="start" value="Ausleihe" class="Button-s"/>
</td>
<td>
<input style="background-color:#00D8F8;color:#000000" type="submit" name="start" value="vorhandenes Material anzeigen" class="Button-s"/>
</td></tr>
<tr align="center"><td>
<input style="background-color:#FF8F00;color:#000000" type="submit" name="start" value="Ausleihe verschiedenes" class="Button-s"/>
</td>
<td>
<input style="background-color:#00D8F8;color:#000000" type="submit" name="start" value="Ausgeliehenes Material anzeigen" class="Button-s"/>
</td></tr>
<tr align="center"><td>
<input style="background-color:#FF8F00;color:#000000" type="submit" name="start" value="Ausleihhistorie anzeigen" class="Button-s"/>
</td>
<td>
<input style="background-color:#00D8F8;color:#000000" type="submit" name="start" value="ausgeliehenes Material nach Ausleihgrund anzeigen" class="Button-s"/>
</td></tr>
<tr align="center"><td>
<input style="background-color:#FF8F00;color:#000000" type="submit" name="start" value="Namen in Ausleihnummer tauschen" class="Button-s"/>
</td>
<td>
<input style="background-color:#00D8F8;color:#000000" type="submit" name="start" value="Ger&auml;teliste anzeigen" class="Button-s"/>
</td></tr>
<tr align="center"><td>
<input style="background-color:#FF8F00;color:#000000" type="submit" name="start" value="Entleiher bearbeiten" class="Button-s"/>
</td>
<td>
<input style="background-color:#FFFF00;color:#000000" type="submit" name="start" value="Reservieren" class="Button-s"/>
</td></tr>
<tr align="center"><td>
<input style="background-color:#FF8F00;color:#000000" type="submit" name="start" value="Paketauswahl" class="Button-s"/>
</td>
<td>
<input style="background-color:#FFFF00;color:#000000" type="submit" name="start" value="Reservierungen anzeigen und aufheben" class="Button-s"/>
</td></tr>
<tr align="center"><td>
<input style="background-color:#FF5000;color:#000000" type="submit" name="start" value="M&auml;ngelhistorie anzeigen" class="Button-s"/>
</td>
<td>
<input style="background-color:#FFFF00;color:#000000" type="submit" name="start" value="Reservierung in Ausleihe wandeln" class="Button-s"/>
</td></tr>
<tr align="center"><td>
<input style="background-color:#FF5000;color:#000000" type="submit" name="start" value="M&auml;ngelinfo eintragen" class="Button-s"/>
</td>
<td>
<input style="background-color:#00FF00;color:#000000" type="submit" name="start" value="Eins&auml;tze von Reglern und Computern anzeigen" class="Button-s"/>
</td></tr>
<tr align="center"><td>
<input style="background-color:#FF5000;color:#000000" type="submit" name="start" value="M&auml;ngelinfo anzeigen und l&ouml;schen" class="Button-s"/>
</td>
<td>
<input style="background-color:#00FF00;color:#000000" type="submit" name="start" value="T&Uuml;V-Termin &auml;ndern" class="Button-s"/>
</td></tr>
<tr align="center"><td>
<input style="background-color:#DDDDFF; color:#FF2700" type="submit" name="start" value="beenden" class="Button-s"/>
</td>
<td>
<input style="background-color:#DDDDFF; color:#FF2700" type="submit" name="start" value="Logout" class="Button-s"/>
</td></tr>




</table>
<?php                                                     
}                                                
    if($_SESSION['schreiben'] == true)                                      
{                      
    ?>                   
    <big><big><br><font color="#FF0000">Admin-Teil:</font></big></big>
    <table border="0" width="90%">
    <tr align="center"><td>
    <input style="background-color:#00FFFF;color:#0000FF" type="submit" name="start" value="Kontrolle der Datenbank" class="Button-s"/>
    </td>
    <td>
    <input style="background-color:#00FFFF;color:#0000FF" type="submit" name="start" value="Ger&auml;teliste bearbeiten" class="Button-s"/>
    </td></tr>
    <tr align="center"><td>
    <input style="background-color:#00FFFF;color:#0000FF" type="submit" name="start" value="Log-Datei ansehen" class="Button-s"/>
    </td>
    <td>
    <input style="background-color:#00FFFF;color:#0000FF" type="submit" name="start" value="M&auml;ngelhistorie bearbeiten" class="Button-s"/>
    </td></tr>
    <tr align="center"><td>
    <input style="background-color:#00FFFF;color:#0000FF" type="submit" name="start" value="Datenbest&auml;nde miteinander vergleichen" class="Button-s"/>
    </td>
    <td>
    <input style="background-color:#00FFFF;color:#0000FF" type="submit" name="start" value="Archiv bearbeiten" class="Button-s"/>
    </td></tr>
    <tr align="center"><td>
    <input style="background-color:#00FFFF;color:#0000FF" type="submit" name="start" value="Inventurliste" class="Button-s"/>
    </td>
    <td>
    <input style="background-color:#00FFFF;color:#0000FF" type="submit" name="start" value="Zusatzliste bearbeiten" class="Button-s"/>
    </td></tr>
    <tr align="center"><td>
    <input style="background-color:#00FFFF;color:#0000FF" type="submit" name="start" value="Updates ausf&uuml;hren" class="Button-s"/>
    </td>
    <td>
    <input style="background-color:#00FFFF;color:#0000FF" type="submit" name="start" value="Techniker bearbeiten" class="Button-s"/>
    </td></tr>          
    <tr align="center"><td>
    <input style="background-color:#00FFFF;color:#0000FF" type="submit" name="start" value="zur Spieldatenbank" class="Button-s"/>
    </td>
    <td>
    <input style="background-color:#00FFFF;color:#0000FF" type="submit" name="start" value="Konfiguration bearbeiten" class="Button-s"/>
    </td></tr>
    <tr align="center"><td>
    <big><big><a href="http://localhost/msd1.24.4/index.php" target="_blank" >zur Datenbanksicherung im neuen Fenster</a><br></big>
    </big></td>
    <td>
    <big><big><a href="http://localhost/phpmyadmin/index.php?db=taucherkammer" target="_blank" >zur Datenbank im neuen Fenster</a><br></big></big>
    </td></tr>
    <br>
    </form>
    <?php
    }
?>
</big></big>
</body>
</html>
<?php  
/*
$d = variable in functionen
$date = übergabevariable in functionen
$heut = heute deu
$heuteng = heute engl
*/
?>
