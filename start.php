<?php
require_once 'base/verify_login.php';
require_once 'xml_common.php';


///////User code below/////////////////////
$link=get_link($GLOBALS['main_user'],$GLOBALS['main_pass']);
//echo '<pre>';print_r($grp);echo '</pre>';

//$GLOBALS['per_style']='';//if defined=display, undefined=hidden

//echo '<pre>';print_r($_POST);echo '</pre>';
$action=isset($_POST['action'])?$_POST['action']:'';

//echo 'action='.$action;
main_menu();
$user=$_SESSION['login'];

if($action=='new')
{
	show_templates($link);
}
if($action=='select_template')
{
	$inserted_id=insert_template($link,$_POST['xml_template_type'],$user);
	echo 'inserted id==>'.$inserted_id.'<==<br>';
	if($inserted_id!==false)
	{
		edit($link,$inserted_id,$user);
	}
	else
	{
		echo 'New data creation falied. Insufficient permissions?<br>';
	}
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
	echo '<h4 class="text-danger">Search by database ID</h4>';

	echo '<form method=post>';
	echo '<input type=hidden name=session_name value=\''.session_name().'\'>';
	echo 'Database ID:<input type=text name=id>';
	echo '<button class="btn btn-sm btn-primary" name=action value=view>Show</button>';
	echo '</form>';

	echo '<br><h4 class="text-success">---------- OR ----------</h4><br>';

	show_search_form($link);
	
}
else if($action=='edit')
{	
	edit($link,$_POST['id'],$user);
}
else if($action=='view')
{
	view($link,$_POST['id'],$user);
}
else if($action=='save')
{
	if(save($link,$_POST))
	{
			echo 'successfully saved at '.strftime("%Y-%m-%d %H:%M:%S").'<br>';
	}
	else
	{
			echo 'failed to saved at'.strftime("%Y-%m-%d %H:%M:%S").'<br>';
	}
	edit($link,$_POST['id'],$user);
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
else if($action=='view_single')
{
	view($link,$_POST['id'],$user);
}

tail();
?>
