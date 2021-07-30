<?php
//$GLOBALS['nojunk']='';
require_once 'base/verify_login.php';
require_once 'xml_common.php';
///////User code below/////////////////////
$link=get_link($GLOBALS['main_user'],$GLOBALS['main_pass']);
//echo '<pre>';print_r($_POST);echo '</pre>';

$action=isset($_POST['action'])?$_POST['action']:'';
$GLOBALS['grp']=get_group($link);

//echo 'action='.$action;

if($action=='permission')
{
	echo '<h3>Set Permissions for Groups / Users for record : '.$_POST['id'].'</h3>';
	echo '<div class=two_column_auto>';
		echo '<div class="border border-warning">';
			manage_group($link,$_POST['id']);
		echo '</div>';
		echo '<div class="border border-warning">';
			manage_user($link,$_POST['id']);
		echo '</div>';
	echo '</div>';
}



function manage_group($link,$id)
{
	$acl=(array)get_acl($link,$GLOBALS['database'],'xml','acl','id',$id);
	//echo '<pre>ACL:';print_r($acl);echo '</pre>';
	//echo '<pre>GROUP:';print_r($GLOBALS['grp']);echo '</pre>';

	$sql='select * from user_group';
	$result=run_query($link,$GLOBALS['database'],$sql);
	echo '<table class="table table-sm table-striped">';
	echo '<tr><th>View</th><th>Edit</th><th>Group</th></tr>';
	while($ar=get_single_row($result))
	{
		$rcheck='';
		$ucheck='';
		
		if(in_array($ar['user_group'],array_keys($acl)))
		{
			if(strpos($acl[$ar['user_group']],'r')!==false){$rcheck='checked';}
			if(strpos($acl[$ar['user_group']],'u')!==false){$ucheck='checked';}			
		}
		
		echo ' <tr>
					<td><input type=checkbox '.$rcheck.' id=\''.$ar['user_group'].'^r\' name=\''.$ar['user_group'].'\'></td>
					<td><input type=checkbox '.$ucheck.' id=\''.$ar['user_group'].'^u\' name=\''.$ar['user_group'].'\'></td>
					<td>'.$ar['user_group'].'</td>
				</tr>';
	}
	echo '</table>';
}



function manage_user($link,$id)
{
	$acl=(array)get_acl($link,$GLOBALS['database'],'xml','acl','id',$id);
	//echo '<pre>ACL:';print_r($acl);echo '</pre>';


	$sql='select user,name,`group` from user';
	$result=run_query($link,$GLOBALS['database'],$sql);
	echo '<table class="table table-sm table-striped">';
	echo '<tr><th>View</th><th>Edit</th><th>User</th><th>This User\'s groups</th></tr>';
	while($ar=get_single_row($result))
	{
		//print_r($ar);
		//print_r($ar);
		$rcheck='';
		$ucheck='';
		if(array_key_exists($ar['user'],$acl))
		{
			if(strpos($acl[$_SESSION['login']],'r')!==false){$rcheck='checked';}
			if(strpos($acl[$_SESSION['login']],'u')!==false){$ucheck='checked';}
			
		}
				
		echo '<tr>
				<td><input type=checkbox '.$rcheck.' id=\''.$ar['user'].'^r\' name=\''.$ar['user'].'\'></td>
				<td><input type=checkbox '.$ucheck.' id=\''.$ar['user'].'^u\' name=\''.$ar['user'].'\'></td>
				<td>'.$ar['name'].'</td>
				<td>'.$ar['group'].'</td>
			</tr>';

	}
	echo '</table>';
}
?>
