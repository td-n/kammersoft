<?php

class Ausleihe {

public static function liste($bool_flags = "", $order = "g.typ, g.regnr")
{
	global $db;

	$sql = "SELECT * FROM geraete AS g, typ AS t WHERE g.typ = t.id AND NOT ausgeliehen ORDER BY $order";
	$stmt = $db->prepare($sql);
	if (!$stmt->execute())
		die("DB-Fehler");

	$list = array();
	while ($row = $stmt->fetch()) {

		if ($row["TUEV"] != "0000-00-00") {
			$isodate_tuev = strtotime($row["TUEV"]);
			$row["TUEV"] = date('d.m.Y', $isodate_tuev);
		} else
			$row["TUEV"] = "";
		$list[] = $row;
	}

	return $list;
}

}

?>
