<?php 

require_once("header.php"); 
require_once("include/Ausleihe.php");

$liste = Ausleihe::liste();

?>

<table>
<thead>
	<tr>
		<th>ausleihen</th>
		<th>Typ</th>
		<th>Nummer</th>
		<th>Hersteller</th>
		<th>Bemerkung</th>
		<th>Rep. notw.</th>
		<th>letzte Termine: Regler &amp; Flasche TÜV Computer Batt.-wechsel</th>
		<th>Reservierung</th>
	</tr>
</thead>
<tbody>

<?php $i = 0; ?>
<?php foreach($liste as $g): ?>
<tr class="<?=$i++ %2 == 0 ? "gerade" : "ungerade"?>">
	<td><input type="checkbox" id="ausleihen" name="ausleihen" /></td>
	<td><?=$g["Typ"]?></td>
	<td><?=$g["RegNR"]?></td>
	<td><?=$g["Hersteller"]?></td>
	<td><?=$g["Bemerk"]?></td>
	<td><input type="checkbox" id="reparieren" name="reparieren" /></td>
	<td><?=$g["TUEV"]?></td>
	<td><?=$g["ausgeliehen"]?></td>
</tr>
<?php endforeach ?>

</tbody>
</table>

<?php require_once("footer.php"); ?>