<?
$log=fopen("tmp/upd120130.log","a");
fputs($log,date("d.m.Y H:i:s")."\n");
echo "Update auf Version $VERSION<br>";
echo "Vorraussetzungen pr&uuml;fen:<br>";
	$chk=array("DB","fpdf","fpdi","Mail","Mail/mime","Image/Canvas","Image/Graph","jpgraph");
	$ok=true;
	foreach($chk as $file) {
		echo "$file: ";
		if (@include("$file.php")) {
			echo "ok<br>";
			fputs($log,"$file: ok\n");
		} else {
			$ok=false;
			echo "false<br>";
			fputs($log,"$file: false\n");
		}
	}
	if (!$ok) {
		echo "Einige Vorraussetzungen sind nicht erf&uuml;llt.<br>&Uuml;berpr&uuml;fen Sie die die Variable 'include_path' in der 'php.ini'.<br>";
		echo "Andernfalls installieren Sie die noch fehlenden Programme<br>";
	}
echo "Konfigurationsdatei erweitern ";
	if (is_writable("inc/conf.php")) {
		require("inc/conf.php");
		$configfile=file("inc/conf.php");
		$f=fopen("inc/conf.php","w");
		foreach($configfile as $row) {
			$tmp=trim($row);
			if (ereg("\?>",$tmp)) {
				fputs($f,'$showErr=false;'."\n");
				fputs($f,'$logmail=true;'."\n");
				fputs($f,'$tinymce=false;'."\n");
				fputs($f,'$jcalendar=true;'."\n");
				fputs($f,'$listLimit=200;'."\n");
				fputs($f,'$jpg=false;'."\n");
				fputs($f,$tmp."\n");
			} else {
				fputs($f,$tmp."\n");
			}
		}
		fclose($f);
		echo "<b>ok</b><br>";
		fputs($log,"conf.php geändert\n");
	} else {
		echo "<br>inc/conf.php ist nicht beschreibbar. Abbruch!<br>";
		fputs($log,"conf.php nicht beschreibbar\n");
		fclose($log);
		exit();
	};

echo "Datenbankinstanz ".$_SESSION["dbname"]." erweitern : ";
	$updatefile="update/update".$rc[0]["version"]."-$VERSION";
	$updatefile=ereg_replace("\.","",$updatefile).".sql";
	if (ob_get_level() == 0) ob_start();
	ob_flush();
	flush();
	$f=fopen($updatefile,"r");
	if (!$f) { 
		fputs($log,"$updatefile nicht gefunden\n");
		echo "<br>Kann Datei $updatefile nicht &ouml;ffnen.<br>Abbruch!";
		fclose($log);
		exit();
	}
	$zeile=trim(fgets($f,1000));
	$query="";
	$ok=0;
	$fehl=0;
	while (!feof($f)) {
		if (empty($zeile)) { $zeile=trim(fgets($f,1000)); continue; };
		if (preg_match("/^--/",$zeile)) { $zeile=trim(fgets($f,1000)); continue; };
		if (!preg_match("/;$/",$zeile)) { 
			$query.=$zeile;
		} else {
			$query.=$zeile;
			$rc=$db->query(substr($query,0,-1));
			if ($rc) { $ok++; echo "."; fputs($log,"."); }
			else { $fehl++; echo "!"; fputs($log,"!");};
			ob_flush();
			flush();
			$query="";
		};
		$zeile=trim(fgets($f,1000));
	};
	fputs($log,"\n");
	if ($fehl>0) { 
		echo "Es sind $fehl Fehler aufgetreten<br>";  
		fputs($log,"Es sind $fehl Fehler aufgetreten\n");
	}
	else { 
		fputs($log,"Datenbankupdate erfolgreich\n");
		echo "Datenbankupdate erfolgreich durchgef&uuml;hrt.<br>"; 
	}
	fclose($f);

echo "Menue erweitern<br>";
	if (is_writable("../$ERPNAME/menu.ini")) {
		$menufile=file("../$ERPNAME/menu.ini");
		$f=fopen("../$ERPNAME/menu.ini","w");
		foreach($menufile as $row) {
			$tmp=trim($row);
			if (ereg("crm/personen3.php",$tmp)) {
				fputs($f,$tmp."\n");
				fputs($f,"\n");
				fputs($f,"[CRM--Auftragschance]\n");
				fputs($f,"module=crm/opportunity.php\n");
				fputs($f,"\n");
				fputs($f,"[CRM--Wissens-DB]\n");
				fputs($f,"module=crm/wissen.php\n");
				fputs($f,"\n");
				fputs($f,"[CRM--Notizen]\n");
				fputs($f,"module=crm/postit.php\n");
			} else {
				fputs($f,$tmp."\n");
			}
		}
		fputs($log,"Menue erweitert\n");
		fclose($f);
		echo "<b>ok</b><br>";
	} else {
		fputs($log,"../$ERPNAME/menu.ini ist nicht beschreibbar\n");
		echo "<br>../$ERPNAME/menu.ini ist nicht beschreibbar. Abbruch!<br>";
		echo "Bitte manuell eintragen:<br>";
		echo "[CRM--Auftragschance]<br>";
		echo "module=crm/opportunity.php<br>";
		echo "<br>";
		echo "[CRM--Wissens-DB]<br>";
		echo "module=crm/wissen.php<br>";
		echo "<br>";
		echo "[CRM--Notizen]<br>";
		echo "module=crm/postit.php<br>";
		echo "<br>";
	};
fclose($log);
?>
