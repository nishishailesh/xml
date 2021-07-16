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
	$sqlt='select xsl from xml_template where id=\''.$ar['xml_template_id'].'\'';
	$resultt=run_query($link,$GLOBALS['database'],$sqlt);
	$art=get_single_row($resultt);
	$xslt->importStyleSheet(DomDocument::load('psychiatry.xsl'));
	//$xslt->importStyleSheet(simplexml_load_string($art['xsl']));

	print $xslt->transformToXML($xml);

	$user_data=get_user_info($link,$_SESSION['login']);
	echo 'Database ID: '.$_POST['id'].', Last Edited by:'.$user_data['name'].'('.$_SESSION['login'].') at '.$ar['recording_time'];

}
?>

<style>
.print_hide
{
	display:none;
}
</style>

