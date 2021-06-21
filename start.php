<?php
require_once 'base/verify_login.php';
require_once 'xml_common.php';
head($GLOBALS['application_name']);
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
	echo '<button name=action value=edit>Show</button>';
	echo '<input type=text name=id>';
	echo '</form>';
}
else if($action=='edit')
{
	edit($link,$_POST['id']);
}
else if($action=='save')
{
	save($link,$_POST);
	edit($link,$_POST['id']);
}

tail();

function show_templates($link)
{
	$sql='select * from xml_template';
	$result=run_query($link,$GLOBALS['database'],$sql);
	echo '<form method=post>';
	echo '<input type=hidden name=session_name value=\''.session_name().'\'>';
	echo '<input type=hidden name=action value=select_template>';
	while($ar=get_single_row($result))
	{
		echo '<button name=xml_template_type value=\''.$ar['id'].'\'>'.$ar['template_name'].'</button>';
	}	
	echo '</form>';
}

function insert_template($link,$template_id)
{
	$t_sql='select * from xml_template where id=\''.$template_id.'\'';
	$t_result=run_query($link,$GLOBALS['database'],$t_sql);
	$ar=get_single_row($t_result);
	$sql='insert into xml (xml) values(\''.my_safe_string($link,$ar['xml']).'\')';
	$result=run_query($link,$GLOBALS['database'],$sql);
	return last_autoincrement_insert($link);
}

function edit($link,$id)
{
	$sql='select * from xml where id=\''.$id.'\'';
	$result=run_query($link,$GLOBALS['database'],$sql);
	$ar=get_single_row($result);
	$xml=simplexml_load_string($ar['xml']);
	//save_post_as_xml($GLOBALS['xml']);
	//$sql='update xml set xml=\''.$GLOBALS['xml']->asXML().'\' where  id=\''.$ar['id'].'\'';
	//run_query($link,$GLOBALS['database'],$sql);

	//echo '<pre>';
	//print_r($GLOBALS['xml']);
	echo '<form method=post>';
	echo '<input type=hidden name=session_name value=\''.session_name().'\'>';
	echo '<input type=text readonly name=id value=\''.$id.'\'>';
	echo '<input type=submit name=action value=save>';
  
  
	echo '<ul><span class=bg-warning>'.$xml->getName().'</span>';
        edit_direct_xml($link,$xml);
	echo '</ul>';
  
	echo '</form>';
}

function save($link,$post)
{
	$sql='select * from xml where id=\''.$_POST['id'].'\'';
	$result=run_query($link,$GLOBALS['database'],$sql);
	$ar=get_single_row($result);
	$xml=simplexml_load_string($ar['xml']);
	save_post_as_xml($xml);
	$sql='update xml set xml=\''.$xml->asXML().'\' where  id=\''.$_POST['id'].'\'';
	run_query($link,$GLOBALS['database'],$sql);	
}

?>
