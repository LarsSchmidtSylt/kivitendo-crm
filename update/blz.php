<html>
<LINK REL="stylesheet" HREF="../css/lx-office-erp.css" TYPE="text/css" TITLE="Lx-Office stylesheet">
<body>
<?php
/*
BLZimport mit Browser nach Lx-Office ERP
Holger Lindemann <hli@lx-system.de>
*/

if ($_POST) {

function ende($nr) {
	echo "Abbruch: $nr<br>";
	echo "Fehlende oder falsche Daten.";
	exit(1);
}

if (!$_SESSION["db"]) {
	$conffile="../config/authentication.pl";
	if (!is_file($conffile)) {
		ende(4);
	}
}
require ("import_lib.php");

if (!anmelden()) ende(5);
/* get DB instance */
$db=$_SESSION["db"]; //new myDB($login);


	$test=$_POST["test"];

	clearstatcache ();

	/* no data? */
	if (empty($_FILES["Datei"]["name"]))
		ende (2);

	/* copy file */
	if (!move_uploaded_file($_FILES["Datei"]["tmp_name"],"blz.txt")) {
		print_r($_FILES);
		echo $_FILES["Datei"]["tmp_name"];
		echo "Upload von Datei fehlerhaft.";
		echo $_FILES["Datei"]["error"], "<br>";
		ende (2);
	} 

	/* check if file is really there */
	if (!file_exists("blz.txt")) 
		ende(3);

	$sqlins="INSERT INTO blz_data (blz,fuehrend,bezeichnung,plz,ort,kurzbez,pan,bic,pzbm,nummer,aekz,bl,folgeblz) ";
	$sqlins.="VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s',%d,'%s','%s','%s')";
	$sqldel="delete from blz";
	$ok="true";
	$f=fopen("blz.txt","r");
	if ($test) echo "Testdurchlauf <br>";
	$i=0;
	if ($f) {
		if (!$test) $rc=$db->query("BEGIN");
		if (!$test) $rc=$db->query($sqldel);
		while (($zeile=fgets($f,256)) != FALSE) {
			$sql=sprintf($sqlins,substr($zeile,0,8),substr($zeile,8,1),substr($zeile,9,58),substr($zeile,67,5),
						substr($zeile,72,35),substr($zeile,107,27),substr($zeile,134,5),substr($zeile,139,11),
						substr($zeile,150,2),substr($zeile,152,6),substr($zeile,158,1),substr($zeile,159,1),
						substr($zeile,160,8));
			if (!$test){
				$rc=$db->query($sql);
				 if(DB::isError($rc)) {
                        		echo $sql."<br><pre>";
					echo $rc->getMessage()."</pre><br>";
					$ok=false;
					break;
				}
			}
			$i++;
		}
		if ($ok) {
			if (!$test) $rc=$db->query("COMMIT");
			echo "$i Daten erfolgreich importiert";
		} else {
			if (!$test) $rc=$db->query("ROLLBACK");
			echo "Fehler in Zeile: ".$i."<br>";
			echo $sql."<br>";
			ende(6);
		}
	} else {
		ende(4);
	}
} 
?>
<p class="listtop">BLZ-Import f&uuml;r die ERP<p>
<br>Die erste Zeile enth&auml;lt keine Feldnamen der Daten.<br>
Die Datenfelder haben eine feste Breite.<br><br>
Die Daten k&ouml;nnen hier bezogen werden:<br>
<a http='http://www.bundesbank.de/zahlungsverkehr/zahlungsverkehr_bankleitzahlen_download.php'>
http://www.bundesbank.de/zahlungsverkehr/zahlungsverkehr_bankleitzahlen_download.php</a><br><br>
ggf. das File vorher noch auf UTF8 wandeln: iconv -f latin1 -t  utf8 blz.txt -o blz1.txt<br><br>
Achtung!! Die bestehenden BLZ-Daten werden zun&auml;chst gel&ouml;scht.
<br>
<form name="import" method="post" enctype="multipart/form-data" action="blz.php">
<input type="hidden" name="MAX_FILE_SIZE" value="20000000">
<input type="hidden" name="login" value="<?= $login ?>">
<table>
<tr><td>Test</td><td><input type="checkbox" name="test" value="1">ja</td></tr>
<tr><td>Daten</td><td><input type="file" name="Datei"></td></tr>
<tr><td></td><td><input type="submit" name="ok" value="Import"></td></tr>
</table>
</form>
