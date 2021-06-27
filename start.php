<?php
require_once 'base/verify_login.php';
require_once 'xml_common.php';
///////User code below/////////////////////
$link=get_link($GLOBALS['main_user'],$GLOBALS['main_pass']);
//echo '<pre>';print_r($_POST);echo '</pre>';
$action=isset($_POST['action'])?$_POST['action']:'';
//echo 'action='.$action;
main_menu();


if($action=='new')
{
	show_templates($link);
}
if($action=='select_template')
{
	$inserted_id=insert_template($link,$_POST['xml_template_type']);
	edit($link,$inserted_id);
}
else if($action=='get_edit_id')
{
	echo '<form method=post>';
	echo '<input type=hidden name=session_name value=\''.session_name().'\'>';
	echo 'Database ID:<input type=text name=id>';
	echo '<button class="btn btn-sm btn-primary" name=action value=edit>Show</button>';
	echo '</form>';
}
else if($action=='get_view_id')
{
	echo '<form method=post>';
	echo '<input type=hidden name=session_name value=\''.session_name().'\'>';
	echo 'Database ID:<input type=text name=id>';
	echo '<button class="btn btn-sm btn-primary" name=action value=view>Show</button>';
	echo '</form>';
}
else if($action=='edit')
{
	edit($link,$_POST['id']);
}
else if($action=='view')
{
	view($link,$_POST['id']);
}
else if($action=='save')
{
	save($link,$_POST);
	edit($link,$_POST['id']);
}
else if($action=='get_search')
{
	show_search_form($link);
}
else if($action=='show_search_result')
{
	$ids=find_search_result($link,$_POST);
	//echo '<pre>';print_r($ids);
	show_search_result($link,$ids,$_POST);
}

tail();
?>
