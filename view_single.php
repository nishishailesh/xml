<?php
require_once 'base/verify_login.php';
require_once 'xml_common.php';
///////User code below/////////////////////
$link=get_link($GLOBALS['main_user'],$GLOBALS['main_pass']);
//echo '<pre>';print_r($_POST);echo '</pre>';
$action=isset($_POST['action'])?$_POST['action']:'';
//echo 'action='.$action;


if($action=='view_single')
{
	edit($link,$_POST['id']);
}

else if($action=='save')
{
	save($link,$_POST);
	edit($link,$_POST['id']);
	//view($link,$_POST['id']);
}
tail();


?>
