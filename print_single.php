<?php
//$GLOBALS['nojunk']='';
require_once 'base/verify_login.php';
require_once 'xml_common.php';
///////User code below/////////////////////
$link=get_link($GLOBALS['main_user'],$GLOBALS['main_pass']);
//echo '<pre>';print_r($_POST);echo '</pre>';

$action=isset($_POST['action'])?$_POST['action']:'';
//echo 'action='.$action;

if($action=='print')
{
	$sql='select * from xml where id=\''.$_POST['id'].'\'';
	$result=run_query($link,$GLOBALS['database'],$sql);
	$ar=get_single_row($result);
	$xml=simplexml_load_string($ar['xml']);
	
	$xslt = new xsltProcessor;
	$xslt->importStyleSheet(DomDocument::load('psychiatry.xsl'));
	print $xslt->transformToXML($xml);
}


?>


