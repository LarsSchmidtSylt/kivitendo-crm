<?
// $Id$
	require_once("inc/stdLib.php");
	include("inc/template.inc");
	include("inc/FirmenLib.php");
	include("inc/UserLib.php");
	$t = new Template($base);
	$t->set_file(array("fa1" => "firmen3.tpl"));
	if ($_POST["saveneu"]) {
		$rc=saveNeuFirmaStamm($_POST,$_FILES,"C");
		if ($rc[0]>0) { header("location:firmen3.php?id=".$rc[0]."&edit=1");}
		else { $msg="Fehler beim Sichern (".($rc[1]).")"; };
		$btn1=""; $btn2=""; $_POST["id"]="";
		vartpl ($t,$_POST,"C",$msg,$btn1,$btn2,3);
	} else if ($_POST["save"]) {
		if ($_POST["id"]) {
			$rc=saveFirmaStamm($_POST,$_FILES,"C");
		} else {
			$rc[0]=-1; $rc[1]="Kein Bestandskunde";
		}
		if ($rc[0]>0) {
			$msg="Daten gesichert.";
			$_POST=getFirmenStamm($rc[0],false,"C");
		} else {
			$msg="Fehler beim Sichern ( ".$rc[1]." )";
		};
		$btn1="<input type='submit' name='save' value='sichern'>";
		$btn2="<input type='submit' name='show' value='zur Anzeige'>";
		vartpl ($t,$_POST,"C",$msg,$btn1,$btn2,3);
	} else if ($_POST["show"]) {
		header("location:firma1.php?id=".$_POST["id"]);
	} else if ($_GET["edit"]) {
		$daten=getFirmenStamm($_GET["id"],false,"C");
		$msg="Edit: <b>".$_GET["id"]."</b>";
		$btn1="<input type='submit' name='save' value='sichern'>";
		$btn2="<input type='submit' name='show' value='zur Anzeige'>";
		vartpl ($t,$daten,"C",$msg,$btn1,$btn2,3);
	} else {
		leertpl($t,3,"C","Neuer Kunde");
	}
	$t->pparse("out",array("fa1"));

?>
