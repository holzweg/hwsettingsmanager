<?php

$iniFile = $_POST["inifile"];
$dataIndex = $_POST["dataindex"];
$key = $_POST["key"];
$group = $_POST["group"];

// Get correct file
if($dataIndex == "default") {
    $file = "settings/$iniFile";
}elseif($dataIndex == "override") {
    $file = "settings/override/$iniFile.append.php";
}elseif(substr($dataIndex, 0, 3) == "sa_") {
    $siteaccess = substr($dataIndex, 3);
    $file = "settings/siteaccess/$siteaccess/$iniFile.append.php";
}else{
    eZExecution::cleanExit();
}

if(!file_exists($file)) {
    eZExecution::cleanExit();
}

$rootPath = $_SERVER["DOCUMENT_ROOT"];
$fullPath = "$rootPath/$file";

// Look for key
$passedGroup = false;
foreach (new SplFileObject($file) as $lineNumber => $lineContent) {
    if ($passedGroup === true && substr($lineContent,0,1) != "#" && FALSE !== strpos($lineContent, $key)) {
        $txmtLine = $lineNumber + 1;
        echo "txmt://open/?url=file://$fullPath&line=$txmtLine";
        eZExecution::cleanExit();
    }

    if(FALSE !== strpos($lineContent, "[$group]")) {
        $passedGroup = true;
    }

}

// Look for group
foreach (new SplFileObject($file) as $lineNumber => $lineContent) {
    if (substr($lineContent,0,1) != "#" && FALSE !== strpos($lineContent, "[$group]")) {
        $txmtLine = $lineNumber + 1;
        echo "txmt://open/?url=file://$fullPath&line=$txmtLine";
        eZExecution::cleanExit();
    }
}

// Just open file
echo "txmt://open/?url=file://$fullPath";
eZExecution::cleanExit();

?>