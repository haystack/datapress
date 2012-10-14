<?php
include_once("default-templates.php");
global $DPTEMPLATES;
ob_start();
header("Content-type: text/javascript");
$callback = $_GET["jsoncallback"];
$json = json_encode($DPTEMPLATES);
print <<<EOF
$callback($json);
EOF
?>