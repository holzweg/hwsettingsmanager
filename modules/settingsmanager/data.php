<?php

$iniFile = $_POST["inifile"];

if(!file_exists("settings/$iniFile")) {
    $response = array( 'status'=> 'error');
    print( json_encode( $response ) );
    eZExecution::cleanExit();
}

// Get all available siteaccesses
$siteIni = eZINI::instance( "site.ini" );
$availableSiteAccessList = $siteIni->variable( 'SiteAccessSettings', 'AvailableSiteAccessList');

// Initialize dataset
$dataset = array();

// Build default settings
$defaultIni = eZINI::instance( "settings/$iniFile" );
$defaultIni->parseFile("settings/$iniFile");
$i = 0;
foreach($defaultIni->BlockValues as $group => $line) {
    foreach($line as $key => $value) {
        $type = "string";
        if(is_array($value)) {
            $type = "array";
        }
        $dataset["{$group}_{$key}"] = array(
            "index"     => $i++,
            "group"     => $group,
            "key"       => $key,
            "default"   => $value,
            "type"      => $type
        );
    }
}

// Build override settings
$overrideIni = eZINI::instance("settings/override/$iniFile.append.php");
$overrideIni->parseFile("settings/override/$iniFile.append.php");
foreach($overrideIni->BlockValues as $group => $line) {
    foreach($line as $key => $value) {
        if(isset($dataset["{$group}_{$key}"])) {
            $dataset["{$group}_{$key}"]["override"] = $value;
        }
    }
}

// Build siteaccess settings
foreach($availableSiteAccessList as $availableSiteAccess) {

    // skip if siteaccess does not exist
    if(!is_dir("settings/siteaccess/$availableSiteAccess")) {
        continue;
    }

    $siteaccessIni = eZINI::instance("settings/siteaccess/$availableSiteAccess/$iniFile.append.php");
    $siteaccessIni->parseFile("settings/siteaccess/$availableSiteAccess/$iniFile.append.php");
    foreach($siteaccessIni->BlockValues as $group => $line) {
        foreach($line as $key => $value) {
            if(isset($dataset["{$group}_{$key}"])) {
                $dataset["{$group}_{$key}"]["sa_$availableSiteAccess"] = $value;
            }
        }
    }
}


$response = array(
    "status"        => "success",
    "data"          => array_values($dataset),
    "siteaccesses"  => $availableSiteAccessList
);
print( json_encode( $response ) );
eZExecution::cleanExit();

?>