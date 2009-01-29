<?
// $Id$
	require_once("inc/stdLib.php");
	include("inc/FirmenLib.php");
	include("inc/persLib.php");
	include("inc/crmLib.php");
	include("inc/UserLib.php");
	$ALabels=getLableNames();
	$freitext=$_POST["freitext"];
	if (!$_POST["format"] || empty($_POST["format"])) {
		$usr=getUserStamm($_SESSION["loginCRM"]);
		$etikett=($usr["etikett"])?$usr["etikett"]:$ALabels[0]["id"];
	} else {
		$tmp=split("=",$_POST["src"]);
		$_GET[$tmp[0]]=$tmp[1];
		if ($tmp[2]=="ep") $_GET["ep"]=$tmp[3];  
		$etikett=$_POST["format"];
	}
	if ($_GET["pid"]) {
		$dest="pid=".$_GET["pid"];
		$data=getKontaktStamm($_GET["pid"]);
		$id=$_GET["pid"];
		if ($_GET["ep"]==1) {	// Einzelperson
			$dest.="=ep=1";
			$firma="";
		} else {
			$data2=getFirmenStamm($data["cp_cv_id"],true,"C");
			if (!$data2) $data2=getFirmenStamm($data["cp_cv_id"],true,"V");
			$firma=$data2["name"];
		}
		$anrede=$data["cp_greeting"];
		$title=$data["cp_title"];
		$name=$data["cp_givenname"]." ".$data["cp_name"];
		$name1=$data["cp_name"];
		$name2=$data["cp_givenname"];
		$strasse=$data["cp_street"];
		$land=$data["cp_country"];
		$plz=$data["cp_zipcode"];
		$ort=$data["cp_city"];
		$telefon=$data["cp_phone1"];
		$fax=$data["cp_fax"];
		$email=$data["cp_email"];
		$kontakt="";
	} else if ($_GET["sid"]) {
		$id=$_GET["sid"];
		$dest="sid=".$_GET["sid"];
		$data=getShipStamm($_GET["sid"]);
		//$anrede="Firma";
		if ($data) {
			$anrede=$data ["shiptogreeting"];
			$name=$data ["shiptoname"];
			$name2=$data["shiptodepartment_1"];
			$kontakt=$data ["shiptocontact"];
			$strasse=$data ["shiptostreet"];
			$land=$data ["shiptocountry"];
			$plz=$data["shiptozipcode"];
			$ort=$data["shiptocity"];
			$telefon=$data["shiptophone"];
			$fax=$data["shiptofax"];
			$email=$data["shiptoemail"];
		} else {
			$data=getFirmenStamm($_GET["sid"],true,"C");
			if ($data["name"]=="") $data=getFirmenStamm($_GET["sid"],true,"V");
			$name=$data ["name"];
			$name2=$data["department_1"];
			$kontakt=$data ["contact"];
			$strasse=$data ["street"];
			$land=$data ["country"];
			$plz=$data["zipcode"];
			$ort=$data["city"];
			$telefon=$data["phone"];
			$fax=$data["fax"];
			$email=$data["email"];
			$kdnr=$data["customernumber"];
		}
		$name1=$name;
	} else {
		if ($_GET["fid"]) {
			$id=$_GET["fid"];
			$dest="fid=".$_GET["fid"];
			$data=getFirmenStamm($_GET["fid"],true,"C");
		} else if ($_GET["lid"]) {
			$id=$_GET["lid"];
			$dest="lid=".$_GET["lid"];
			$data=getFirmenStamm($_GET["lid"],true,"V");
		}
		//$anrede="Firma";
		$anrede=$data ["greeting"];
		$name=$data ["name"];
		$name1=$name;
		$name2=$data["department_1"];
		$kontakt=$data ["contact"];
		$strasse=$data ["street"];
		$land=$data ["country"];
		$plz=$data["zipcode"];
		$ort=$data["city"];
		$telefon=$data["phone"];
		$fax=$data["fax"];
		$email=$data["email"];
		$kdnr=$data["customernumber"];
	}
	$label=getOneLable($etikett);
	if ($_POST["print"]) {
		$platzhalter=array("ANREDE"=>"anrede","TITEL"=>"title","TEXT"=>"freitext",
				   "NAME"=>"name","NAME1"=>"name1","NAME2"=>"name2",
				   "STRASSE"=>"strasse","PLZ"=>"plz","ORT"=>"ort","LAND"=>"land",
				   "KONTAKT"=>"kontakt","FIRMA"=>"firma","ID"=>"id","KDNR"=>"kdnr",
				   "EMAIL"=>"email","TEL"=>"telefon","FAX"=>"fax");
		$lableformat=array("paper-size"=>$label["papersize"],'name'=>$label["name"], 'metric'=>$label["metric"], 
							'marginLeft'=>$label["marginleft"], 'marginTop'=>$label["margintop"], 
							'NX'=>$label["nx"], 'NY'=>$label["ny"], 'SpaceX'=>$label["spacex"], 'SpaceY'=>$label["spacey"],
							'width'=>$label["width"], 'height'=>$label["height"], 'font-size'=>6);
		require_once('inc/PDF_Label.php');
		$tmp=split(":",$_POST["xy"]);
		$SX=substr($tmp[0],1);
		$SY=substr($tmp[1],1);
		$pdf = new PDF_Label($lableformat, $label["metric"], $SX, $SY);
		$pdf->Open(); 
		unset($tmp);
		if ($SX<>1 or $SY<>1)	$pdf->AddPage();
		foreach ($label["Text"] as $row) {
			preg_match_all("/%([A-Z0-9_]+)%/U",$row["zeile"],$ph, PREG_PATTERN_ORDER);
			if ($ph) {
				$first=true;
				$oder=strpos($row["zeile"],"|");
				$ph=array_slice($ph,1);
				if ($ph[0]) { foreach ($ph as $x) {
					foreach ($x as $u) {
						$y=$platzhalter[$u];
						if (${$y} <>"" and $first) {
							$y=utf8_decode($y);
							$row["zeile"]=str_replace("%".$u."%",${$y},$row["zeile"]);	
							if ($oder>0) $first=false;
						} else {
							$row["zeile"]=str_replace("%".$u."%","",$row["zeile"]);
						}
					}
				}
			};
			if ($oder>0) $row["zeile"]=str_replace("|","",$row["zeile"]);
			if ($row["zeile"]<>"!") {
				if ($row["zeile"][0]=="!") {
							$text=substr($row["zeile"],1);
						} else {
							$text=$row["zeile"];
						}
						$tmp[]=array("text"=>utf8_decode($text),"font"=>$row["font"]);
						//$tmp[]=array("text"=>$text,"font"=>$row["font"]);
					}
			}
		};
		$pdf->Add_PDF_Label2($tmp);
		$pdf->Output();
		exit;
	}
?>
<html>
<head><title></title>
	<link type="text/css" REL="stylesheet" HREF="css/main.css"></link>
<body>
<form name='form' method='post' action='showAdr.php'>
<input type="hidden" name="src" value="<?= $dest ?>">
<p class="norm">
Anschrift<br><hr>
	<?=  ($firma)?"Firma ".$firma."<br><br>":"" ?>
	<?= $anrede ?> <?= $title ?><br>
	<?= $name ?><br>
	<?=  ($name2)?$name2."<br>":"" ?>
	<?=  ($kontakt)?$kontakt."<br>":"" ?>
	<?=  $strasse ?><br><br>
	<?= ($land<>"")?$land." - ":"" ?>
	<?= $plz ?> <?= $ort ?><br>
</p>
	<hr>
	<input type="text" name="freitext" size="25" value="<?= $freitext ?>">
	<hr>
	<table>
		<tr><td>Etikett&nbsp;</td>
			<td>
				<select name='format' >
<?	foreach ($ALabels as $data) { ?>
					<option value='<?= $data["id"]?>'<?= ($data["id"]==$etikett)?" selected":"" ?>><?= $data["name"] ?>

<?	} ?>
				</select>&nbsp;<input type='submit' name='chfrm' value='wechseln'><br><br> 
			</td>
		</tr>
		<tr>
			<td>
<?	$sel="checked";
	for ($y=1; $y<=$label["ny"];$y++) {
		echo "\t\t\t\t";
		for ($x=1; $x<=$label["nx"];$x++) {
			echo "<input type='radio' name='xy' value='x$x:y$y' $sel>";
			$sel="";
		}
		echo "<br>\n";
	}
?>
			</td>
			<td>
				<input type='submit' name='print' value='erzeugen'></form><br><br>
				<a href="javascript:self.close()">schlie&szlig;en</a>
				<script language='JavaScript'>self.focus();</script>
			</td>
		</tr>
	</table>
</body>
</html>
