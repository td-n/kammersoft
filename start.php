<?php require_once("header.php"); ?>

<nav id="borrow" class="left">
	<input type="submit" name="start" value="Ausleihe" tabindex="1" />
	<input type="submit" name="start" value="Ausleihe verschiedenes" tabindex="2" />
	<input type="submit" name="start" value="Ausleihhistorie anzeigen" />
	<input type="submit" name="start" value="Namen in Ausleihnummer tauschen" />
	<input type="submit" name="start" value="Entleiher bearbeiten" />
	<input type="submit" name="start" value="Paketauswahl" />
	<input type="submit" name="start" value="Mängelhistorie anzeigen" />
	<input type="submit" name="start" value="Mängelinfo eintragen" />
	<input type="submit" name="start" value="Mängelinfo anzeigen und l&ouml;schen" />
	<input type="submit" name="start" value="Beenden" />
</nav>

<nav id="bring" class="right">
	<input type="submit" name="start" value="Rückgabe" />
	<input type="submit" name="start" value="vorhandenes Material anzeigen" />
	<input type="submit" name="start" value="Ausgeliehenes Material anzeigen" />
	<input type="submit" name="start" value="ausgeliehenes Material nach Ausleihgrund anzeigen" />
	<input type="submit" name="start" value="Geräteliste anzeigen" />
	<input type="submit" name="start" value="Reservieren" />
	<input type="submit" name="start" value="Reservierungen anzeigen und aufheben" />
	<input type="submit" name="start" value="Reservierung in Ausleihe wandeln" />
	<input type="submit" name="start" value="Einsätze von Reglern und Computern anzeigen" />
	<input type="submit" name="start" value="TÜV-Termin ändern" />
	<input type="submit" name="start" value="Logout" />
</nav>

<br style="clear:both"><br><br style="clear:both"><br><br style="clear:both"><br>

<nav id="admindb">
	<input type="submit" name="start" value="Kontrolle der Datenbank" />
	<input type="submit" name="start" value="Log-Datei ansehen" />
	<input type="submit" name="start" value="Datenbestände miteinander vergleichen" />
	<input type="submit" name="start" value="Inventurliste" />
	<input type="submit" name="start" value="Updates ausführen" />
	<input type="submit" name="start" value="zur Spieldatenbank" />
	<a href="http://localhost/msd1.24.4/index.php" target="_blank" >zur Datenbanksicherung im neuen Fenster</a>
</nav>

<nav id="admingear">
	<input type="submit" name="start" value="Geräteliste bearbeiten" />
	<input type="submit" name="start" value="Mängelhistorie bearbeiten" />
	<input type="submit" name="start" value="Archiv bearbeiten" />
	<input type="submit" name="start" value="Zusatzliste bearbeiten" />
	<input type="submit" name="start" value="Techniker bearbeiten" />
	<input type="submit" name="start" value="Konfiguration bearbeiten" />
	<a href="http://localhost/phpmyadmin/index.php?db=taucherkammer" target="_blank" >zur Datenbank im neuen Fenster</a>
</nav>

<?php require_once("footer.php"); ?>