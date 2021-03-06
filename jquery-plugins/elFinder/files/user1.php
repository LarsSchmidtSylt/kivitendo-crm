<?php
require_once( "inc/stdLib.php" );
include( "inc/grafik1.php" );
include( "inc/template.inc" );
include( "inc/crmLib.php" );
include_once( "inc/UserLib.php" );
if ( isset( $_POST["ok"] ) && $_POST["ok"] and $_POST["termseq"] < 61 ) {
    if ( $_POST["proto"] == 1 ) {
        $_POST["proto"] = 't';
    }
    else {
        $_POST["proto"] = 'f';
    }
    $rc = saveUserStamm( $_POST );
    //$id=$_POST["UID"];
    $no = array(
        'save',
        'uid',
        'login',
        'ok',
    );
    $chkbox = array(
        'tinymce',
        'preon',
        'feature_ac',
        'angebot_button',
        'auftrag_button',
        'rechnung_button',
        'liefer_button',
        'zeige_extra',
        'zeige_lxcars',
        'zeige_etikett',
        'zeige_tools',
        'zeige_bearbeiter',
        'zeige_dhl',
        'external_mail',
        'sql_error',
        'php_error',
        'streetview_default',
    );
    while ( list( $key, $val ) = each( $_POST ) ) {
        if ( !in_array( $key, $no ) ) 
            if ( in_array( $key, $chkbox ) ) {
                $_SESSION[$key] = ( $val == 't' ) ? 't' : 'f';
        }
        else {
            $_SESSION[$key] = $val;
        }
    }
    $_SESSION['theme'] = ( $_POST['theme'] != 'base' ) ? $_POST['theme'] : '';
} elseif ( isset( $_POST["mkmbx"] ) ) {
    $rc = createMailBox( $_POST["Postf2"], $_POST["Login"] );
}
//if ( isset( $_POST["mkmbx"] ) ) {
//    $rc = createMailBox( $_POST["Postf2"], $_POST["Login"] );
//}
$t = new Template( $base );
doHeader( $t );

if ( isset( $_GET["id"] ) && $_GET["id"] && $_GET["id"] <> $_SESSION["loginCRM"] ) {
    $fa = getUserStamm( $_GET["id"] );
    $t->set_file( array( "usr1" => "user1b.tpl" ) );
    $t->set_var( array( vertreter => $fa["vertreter"]." ".$fa["vname"] ) );
    $own = false;
} else {
    $fa = getUserStamm( $_SESSION["loginCRM"] );
    $t->set_file( array( "usr1" => "user1.tpl" ) );
    $own = true;
};
if ( $fa['streetview_default'] == 't' ) {
    $_SESSION['streetview'] = $fa['streetview'] = $_SESSION['streetview_man'];
    $_SESSION['planspace'] = $fa['planspace'] = $_SESSION['planspace_man'];
}
if ( empty( $fa["ssl"] ) ) 
    $fa["ssl"] = "n";
if ( empty( $fa["proto"] ) ) 
    $fa["proto"] = "t";
$gruppen = '';
if ( $fa ) 
    foreach ( $fa["gruppen"] as $row ) {
        $gruppen .= $row["grpname"]."<br>";
}
$i = ( $fa["termbegin"] >= 0 && $fa["termbegin"] <= 23 ) ? $fa["termbegin"] : 8;
$j = ( $fa["termend"] >= 0 && $fa["termend"] <= 23 && $fa["termend"] > $fa["termbegin"] ) ? $fa["termend"] : 19;
$tbeg = '';
$tend = '';
for ( $z = 0;$z < 24;$z++ ) {
    $tbeg .= "<option value=$z".(( $i == $z ) ? " selected" : "" ).">$z";
    $tend .= "<option value=$z".(( $j == $z ) ? " selected" : "" ).">$z";
}
$jahr = date( "Y" );
$re = getReJahr( $fa["id"], $jahr, false, true );
$an = getAngebJahr( $fa["id"], $jahr, false, true );
$IMG = getLastYearPlot( $re, $an, false );
$t->set_var( array( 'IMG'                       => $IMG, 
                    'login'                     => $fa["login"], 
                    'name'                      => $fa["name"], 
                    'addr1'                     => $fa["addr1"], 
                    'addr2'                     => $fa["addr2"], 
                    'addr3'                     => $fa["addr3"], 
                    'uid'                       => $fa["id"], 
                    'homephone'                 => $fa["homephone"], 
                    'workphone'                 => $fa["workphone"], 
                    'notes'                     => $fa["notes"], 
                    'mailsign'                  => $fa["mailsign"], 
                    'email'                     => $fa["email"], 
                    'msrv'                      => $fa["msrv"], 
                    'port'                      => $fa["port"], 
                    'mailuser'                  => $fa["mailuser"], 
                    'kennw'                     => $fa["kennw"], 
                    'postf'                     => $fa["postf"], 
                    'postf2'                    => $fa["postf2"], 
                    'protopop'                  => ( $fa["proto"] == "f" ) ? "checked" : "", 
                    'protoimap'                 => ( $fa["proto"] == "t" ) ? "checked" : "", 
                    'sql_error'                 => ( $fa["sql_error"] == "t" ) ? ",#sql_error" : "", 
                    'php_error'                 => ( $fa["php_error"] == "t" ) ? ",#php_error" : "", 
                    'ssl'.$fa["ssl"]            => "checked", 
                    'mandsig'.$fa['mandsig']    => 'checked',
                    'interv'                    => $fa["interv"],   
                    'pre'                       => $fa["pre"], 
                    'kdviewli'.$fa["kdviewli"]  => "selected", 
                    'kdviewre'.$fa["kdviewre"]  => "selected", 
                    'searchtab'.$fa["searchtab"]=> "selected", 
                    'abteilung'                 => $fa["abteilung"], 
                    'position'                  => $fa["position"], 
                    'termbegin'                 => $tbeg, 
                    'termend'                   => $tend, 
                    'termseq'                   => ( $fa["termseq"] ) ? $fa["termseq"] : 30, 
                    'GRUPPE'                    => $gruppen, 
                    'DATUM'                     => date( 'd.m.Y' ), 
                    'icalext'                   => $fa["icalext"], 
                    'icaldest'                  => $fa["icaldest"], 
                    'icalart'.$fa["icalart"]   => "selected", 
                    'preon'                     => ( $fa["preon"] ) ? ",#preon" : "", 
                    'streetview'                => $fa['streetview'], 
                    'planspace'                 => $fa['planspace'], 
                    'feature_ac'                => ( $fa['feature_ac'] == 't' ) ? ',#feature_ac' : '', 
                    'feature_ac_minlength'      => $fa['feature_ac_minlength'], 
                    'feature_ac_delay'          => $fa['feature_ac_delay'], 
                    'angebot_button'            => ( $fa['angebot_button'] == 't' )         ? ',#angebot_button' : '', 
                    'auftrag_button'            => ( $fa['auftrag_button'] == 't' )         ? ',#auftrag_button' : '',
                    'rechnung_button'           => ( $fa['rechnung_button'] == 't' )        ? ',#rechnung_button': '', 
                    'liefer_button'             => ( $fa['liefer_button'] == 't' )          ? ',#liefer_button' : '', 
                    'zeige_extra'               => ( $fa['zeige_extra'] == 't' )            ? ',#zeige_extra' : '', 
                    'zeige_dhl'                 => ( $fa['zeige_dhl'] == 't' )              ? ',#zeige_dhl' : '', 
                    'external_mail'             => ( $fa['external_mail'] == 't' )          ? ',#external_mail' : '', 
                    'zeige_karte'               => ( $fa['zeige_karte'] == 't' )            ? ',#zeige_karte' : '', 
                    'zeige_etikett'             => ( $fa['zeige_etikett'] == 't' )          ? ',#zeige_etikett' : '', 
                    'zeige_tools'               => ( $fa['zeige_tools'] == 't' )            ? ',#zeige_tools' : '', 
                    'zeige_bearbeiter'          => ( $fa['zeige_bearbeiter'] == 't' )       ? ',#zeige_bearbeiter' : '', 
                    'feature_unique_name_plz'   => ( $fa['feature_unique_name_plz'] == 't' )? ',#feature_unique_name_plz' : '', 
                    'zeige_lxcars'              => ( $fa['zeige_lxcars'] == 't' )           ? ',#zeige_lxcars' : '', 
                    'tinymce'                   => ( $fa['tinymce'] == 't' )                ? ',#tinymce' : '', 
                    'streetview_default'        => ( $fa['streetview_default'] == 't' )     ? ',#streetview_default' : '',
                    'search_history'            => $fa['search_history'] ) );
if ( $own ) {
    $t->set_block( "usr1", "Selectbox", "Block" );
    $select = ( !empty( $fa["vertreter"] ) ) ? $fa["vertreter"] : $fa["id"];
    $user = getAllUser( array( 0 => true, 1 => "" ) );
    if ( $user ) 
        foreach ( $user as $zeile ) {
            if ( $zeile['id'] != $_SESSION['loginCRM'] ) {
                $t->set_var( array( 
                        'Sel' => ( $select == $zeile["id"] ) ? " selected" : "", 
                        'vertreter' => $zeile["id"], 
                        'vname' => ( $zeile["name"] != '' ) ? $zeile['name'] : $zeile['login'] 
                ) );
            $t->parse( "Block", "Selectbox", true );
        }
    }
    $t->set_block( "usr1", "SelectboxB", "BlockB" );
    $ALabels = getLableNames( );
    if ( $ALabels ) 
        foreach ( $ALabels as $data ) {
            $t->set_var( array( 
                'FSel' => ( $data["id"] == $fa["etikett"] ) ? " selected" : "", 
                'LID' => $data["id"], 
                'FTXT' => $data["name"] 
            ) );
        $t->parse( "BlockB", "SelectboxB", true );
    }
    chdir( "jquery-themes" );
    $theme = glob( "*" );
    $t->set_block( 'usr1', 'Theme', 'BlockT' );
    if ( $theme ) 
        foreach ( $theme as $file ) {
            $t->set_var( array( 
                'TSel' => ( $file == $fa["theme"] ) ? " selected" : "", 
                'themefile' => $file, 
                'themename' => ucwords( strtr( $file, '-', ' ' ) ), 
            ) );
        $t->parse( 'BlockT', 'Theme', true );
    };
    chdir( "../.." );
} else {
    $t->set_var( array( 'vertreter' => $fa["vertreter"]." ".$fa["vname"], ) );
};
$t->Lpparse( "out", array( "usr1" ) ,$_SESSION['countrycode'],"firma");
?>
