<?php

$tpl = eZTemplate::factory();

// Set inifile if posted
$ini_file = "";
if(isset($_POST["selectedINIFile"])) {
    $ini_file = $_POST["selectedINIFile"];
}
$tpl->setVariable( 'ini_file', $ini_file );

// Set all available ini files
$iniFiles = eZDir::recursiveFindRelative( "settings", '', '.ini' );
$iniFiles = preg_replace('%.*/%', '', $iniFiles );
sort( $iniFiles );
$tpl->setVariable( 'ini_files', array_unique( $iniFiles ) );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:settingsmanager/settings.tpl' );

$Result['path'] = array( array( 'url' => 'settingsmanager/settings',
                                'text' => 'Settings Manager' ) );

?>