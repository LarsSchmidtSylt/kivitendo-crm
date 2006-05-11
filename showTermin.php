<?
// $Id$
	require_once("inc/stdLib.php");
	include("inc/crmLib.php");
	if ($_GET["del"]) {
		deleteTermin($_GET["del"]);
		echo "<script language='JavaScript'>self.close();</script>";
	}
	$data=getTerminData($_GET["termid"]);
	$usr=getTerminUser($_GET["termid"]);
	$links="";
	if ($usr) foreach ($usr as $row) {
		if (substr($row["uid"],0,1)<>"G" and $row["uid"]<>"E".$_SESSION["loginCRM"]) {
			$user[]=$row["uid"];
		}
	}
	if ($user) {
		$selusr=getUsrNamen($user);
		foreach($selusr as $row) {
			if (substr($row["id"],0,1)=="C") { $tmp="firma1.php?id=".substr($row["id"],1); }
			else if (substr($row["id"],0,1)=="V") { $tmp="liefer1.php?id=".substr($row["id"],1); }
			else if (substr($row["id"],0,1)=="P") { $tmp="kontakt.php?id=".substr($row["id"],1); }
			$links.="[<a href='#' onClick='openstamm(\"$tmp\")'>".(($row["name"])?$row["name"]:$row["login"])."</a>] &nbsp; \n";
		}
	}
	list($tt,$mm,$yy)=split("\.",$data["starttag"]);
	$ft=feiertage($yy);
	$x=mktime(0,0,0,$mm,$tt,$yy);
	$wdhlg=array("0"=>"einmalig","1"=>"t&auml;glich","2"=>"2-t&auml;gig","7"=>"w&ouml;chentlich",
		"14"=>"2-w&ouml;chenltich","30"=>"monatlich","365"=>"j&auml;hlich");
?>
<html>
<head><title>Lx-Termin</title>
<link type="text/css" REL="stylesheet" HREF="css/main.css"></link>

<script language="JavaScript">
	function editterm() {
		opener.top.main_window.location.href='termin.php?holen=<?= $_GET["termid"] ?>';
		self.close();
	}
	function delterm() {
		Check = confirm("Wirklich l�schen?");
		if(Check == false) return false;
		document.location.href='showTermin.php?del=<?= $_GET["termid"] ?>';
	}
	function openstamm(link) {
		opener.top.main_window.location.href=link;
		self.close();
	}
</script>
</head>
<body>
<?
	echo "Termin: <b>".$data["cause"]."</b><br>";
	echo db2date($data["starttag"])." ".$data["startzeit"]." - ";
	echo ($data["stoptag"]<>$data["starttag"])?db2date($data["stoptag"])." ".$data["stopzeit"]:$data["stopzeit"];
	echo "<br>";
	echo "Wiederholung: ".$wdhlg[$data["repeat"]].", ";
	echo ($data["ft"]==1)?"nur Arbeitstage":"auch Feiertage";
	echo "<br>";
	if ($ft[$x]) echo $ft[$x];
	echo "<hr><br>";
	echo $data["c_cause"]."<br>";
?>
<hr>
<?= $links ?>
<br>
<br>
<input type="button" onClick="self.close()" value="schlie&szlig;en"> &nbsp; &nbsp;
<input type="button" onClick="delterm()" value="l&ouml;schen"> &nbsp; &nbsp;
<input type="button" onClick="editterm()" value="&auml;ndern">
<script language='JavaScript'>self.focus();</script>
</body>
</html>
