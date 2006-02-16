<?
// $Id: vcardexp.php,v 1.1 2005/12/03 09:46:30 hli Exp $
	require_once 'Contact_Vcard_Build.php';
	require_once("inc/stdLib.php");
	include("inc/FirmaLib.php");
	// instantiate a builder object (defaults to version 3.0)
	$vcard = new Contact_Vcard_Build();
	if ($_GET["pid"]) {
		include("inc/persLib.php");
		$data=getKontaktStamm($_GET["pid"]);
		// set a formatted name
		$vcard->setFormattedName($data["cp_givenname"]." ".$data["cp_name"]);
		// set the structured name parts
		$vcard->setName($data["cp_name"],$data["cp_givenname"],"",$data["cp_title"],"");	
		// add a work email.  note that we add the value
		// first and the param after -- Contact_Vcard_Build
		// is smart enough to add the param in the correct place.
		if ($data["cp_email"]) { 
			$vcard->addEmail($data["cp_email"]);
			$vcard->addParam('TYPE', 'WORK');
			$vcard->addParam('TYPE', 'PREF');
		}
		// add a work address
		$vcard->addAddress('', '', $data["cp_street"], $data["cp_city"], '', $data["cp_zipcode"], $data["cp_country"]);
		$vcard->addParam('TYPE', 'HOME');
		if ($data["cp_gebdatum"]) $vcard->setBirthday($data["cp_gebdatum"]);
		if ($data["cp_notes"]) $vcard->setNote($data["cp_notes"]);
		if ($data["cp_phone1"]) {
			$vcard->addTelephone($data["cp_phone1"]);
			$vcard->addParam('TYPE', 'WORK');
			$vcard->addParam('TYPE', 'PREF');
		}
		if ($data["cp_fax"]) {
			$vcard->addTelephone($data["cp_fax"]);
			$vcard->addParam('TYPE', 'FAX');
		}
		if ($data["cp_position"]) $vcard->setTitle($data["cp_position"]);
		if ($data["cp_cv_id"] && $data["tabelle"]=="C") {
			$fa=getFirmaStamm($data["cp_cv_id"]);
			$vcard->addAddress('', '', $fa["street"], $fa["city"], '', $fa["zipcode"], $fa["country"]);
			$vcard->addParam('TYPE', 'WORK');
			if ($data["cp_abteilung"]) {
				$vcard->addOrganization(array($fa["name"],$data["cp_abteilung"]));
			} else {
				$vcard->addOrganization($fa["name"]);
			}

		} else if ($data["cp_cv_id"] && $data["tabelle"]=="V") {
			$fa=getLieferStamm($data["cp_cv_id"]);
			$vcard->addAddress('', '', $fa["street"], $fa["city"], '', $fa["zipcode"], $fa["country"]);
			$vcard->addParam('TYPE', 'WORK');
			$vcard->addOrganization($fa["name"]);
		}
	} else if ($_GET["fid"]) {
		$data=getFirmaStamm($_GET["fid"]);
		$vcard->setFormattedName($data["name"]);
		if ($data["department_1"]) { 
			$vcard->setName($data["name"],$data["department_1"],"","","");	
		} else { 
			$vcard->setName($data["name"],"","","","");
		}
		$vcard->addAddress('', '', $data["street"], $data["city"], '', $data["zipcode"], $data["country"]);
		$vcard->addParam('TYPE', 'WORK');
		$vcard->addOrganization($data["name"]);
		$vcard->addOrganization($data["department_1"]);
		if ($data["email"]) { 
			$vcard->addEmail($data["email"]);
			$vcard->addParam('TYPE', 'WORK');
			$vcard->addParam('TYPE', 'PREF');
		}
		if ($data["phone"]) {
			$vcard->addTelephone($data["phone"]);
			$vcard->addParam('TYPE', 'WORK');
			$vcard->addParam('TYPE', 'PREF');
		}
		if ($data["fax"]) {
			$vcard->addTelephone($data["fax"]);
			$vcard->addParam('TYPE', 'FAX');
		}
		if ($data["notes"]) $vcard->setNote($data["notes"]);
	} else if ($_GET["lid"]) {
		$data=getLieferStamm($_GET["lid"]);
		$vcard->setFormattedName($data["name"]);
		$vcard->setName($data["name"],"",$data["department_1"],"","");	
		$vcard->addAddress('', '', $data["street"], $data["city"], '', $data["zipcode"], $data["country"]);
		$vcard->addParam('TYPE', 'WORK');
		$vcard->addOrganization($data["name"]);
		if ($data["email"]) { 
			$vcard->addEmail($data["email"]);
			$vcard->addParam('TYPE', 'WORK');
			$vcard->addParam('TYPE', 'PREF');
		}
		if ($data["phone"]) {
			$vcard->addTelephone($data["phone"]);
			$vcard->addParam('TYPE', 'WORK');
			$vcard->addParam('TYPE', 'PREF');
		}
		if ($data["fax"]) {
			$vcard->addTelephone($data["fax"]);
			$vcard->addParam('TYPE', 'FAX');
		}
		if ($data["notes"]) $vcard->setNote($data["notes"]);
	} else {
		exit;
	}
	// get back the vCard and print it
	$text = $vcard->fetch();
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Datum aus Vergangenheit
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");    
	header("Content-type: application/octetstream");
	header("Content-Disposition: attachment; filename=lxo-vcard.vcf");
	header("Content-Disposition: filename=lxo-vcard.vcf");
	echo $text;
?>
