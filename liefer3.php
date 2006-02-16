<?
// $Id: liefer3.php,v 1.4 2005/11/02 10:37:51 hli Exp $
	require_once("inc/stdLib.php");
	include("inc/template.inc");
	include("inc/crmLib.php");
	include("inc/grafik.php");
	include("inc/LieferLib.php");
	$t = new Template($base);
	$fa=getLieferStamm($_GET["fid"]);
	if ($_GET["linlog"]) { $linlog="&linlog=0"; $ll=true; }
	else {$linlog="&linlog=1"; $ll=false; }
	$link1="liefer1.php?id=".$_GET["fid"];
	$link2="liefer2.php?fid=".$_GET["fid"];
	$link3="liefer3.php?fid=".$_GET["fid"].$linlog;
	$link4="liefer4.php?fid=".$_GET["fid"];
	$name=$fa["name"];
	$plz=$fa["zipcode"];
	$ort=$fa["city"];
	if ($_GET["monat"]) {
		$m=substr($_GET["monat"],3,4)."-".substr($_GET["monat"],0,2);
		$reM=getReMonat($_GET["fid"],$m,true);
		$t->set_file(array("fa1" => "liefer3a.tpl"));
		$IMG="";
	} else {
		$re=getReJahr($_GET["fid"],true);
		$an=array();
		$t->set_file(array("fa1" => "liefer3.tpl"));
		$IMG=getLastYearPlot($re,$an,$ll);
		$monat="";
	}
	$t->set_var(array(
			FID => $_GET["fid"],
			PID => $_GET["pid"],
			Link1 => $link1,
			Link2 => $link2,
			Link3 => $link3,
			Link4 => $link4,
			LInr => $fa["vendornumber"],
			Name => $name,
			Plz => $plz,
			Ort => $ort,
			IMG	=> $IMG,
			Monat => $monat
			));
	if ($re) {
		$t->set_block("fa1","Liste","Block");
			$i=0;
			$monate=array_keys($re);
			for ($i=0; $i<13; $i++) {
			$colr=array_shift($re);
			$t->set_var(array(
				LineCol => $bgcol[($i%2+1)],
				Month => substr($monate[$i],4,2)."/".substr($monate[$i],0,4),
				Rcount => $colr["count"],
				RSumme => sprintf("%01.2f",$colr["summe"]),
				Curr => $colr["curr"]
			));
			$t->parse("Block","Liste",true);
			//$i++;
		}
	}
	if ($reM) {
		$t->set_block("fa1","Liste","Block");
		$i=0;
		if ($reM) foreach($reM as $col){
			$t->set_var(array(
				LineCol => $bgcol[($i%2+1)],
				Datum => db2date($col["transdate"]),
				RNr	=> (array_key_exists("invnumber",$col))?$col["invnumber"]:$col["quonumber"],
				RNid => $col["id"],
				RSumme => sprintf("%01.2f",$col["netamount"]),
				RBrutto => sprintf("%01.2f",$col["amount"]),
				Curr => $col["curr"],
				//Typ => (array_key_exists("invnumber",$col))?"R":"A"
				));
			$t->parse("Block","Liste",true);
			$i++;
		}
	}
	if ($monat and !$reM) {
		$t->set_block("fa1","Liste","Block");
			$i=0;
			$t->set_var(array(
				LineCol => "",
				Datum => "",
				RNr	=> "Keine ",
				RSumme => "Ums&auml;tze",
				Curr => ""
				));
			$t->parse("Block","Liste",true);
	}
	$t->pparse("out",array("fa1"));
?>
