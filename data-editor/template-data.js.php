<?php
include_once("default-templates.php");
global $DPTEMPLATES;
ob_start();
header("Content-type: text/javascript");
$callback = $_GET["jsoncallback"];
$ident = $_GET["identifier"];
$template = NULL;
foreach ($DPTEMPLATES as $tem) {
	if ($tem["identifier"] == $ident) {
		$template = $tem;
		break;
	}
}	
$json = json_encode($template);
print <<<EOF
$callback($json);
EOF
?>