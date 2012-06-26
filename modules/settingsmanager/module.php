<?php
$module = array( 'name' => 'settingsmanager' );

$ViewList = array(); //add as many views as you want here:
$ViewList['settings'] = array( 'script' => 'settings.php',
                               'functions' => array( 'read', 'write' ));

$ViewList['data'] = array( 'script' => 'data.php',
                           'functions' => array( 'read'));

$ViewList['textmate'] = array( 'script' => 'textmate.php',
                               'functions' => array( 'read'));

$FunctionList = array();
$FunctionList['read'] = array();
$FunctionList['write'] = array();

?>