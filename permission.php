<?php
//$GLOBALS['nojunk']='';
require_once 'base/verify_login.php';
require_once 'xml_common.php';
///////User code below/////////////////////
$link=get_link($GLOBALS['main_user'],$GLOBALS['main_pass']);
//echo '<pre>';print_r($_POST);echo '</pre>';
//$GLOBALS['per_style']='';//blank/defined=display, undefined=hidden

$action=isset($_POST['action'])?$_POST['action']:'';

//echo 'action='.$action;

if($action=='save_permission')
{
	if(!$permission_array=save_permission($link,$_POST['id'],$_POST,$_SESSION['login']))
	{
			//echo 'not authorised to save permissions<br>';
	}
	else
	{
		//echo '<h2>permission_arrray:';print_r($permission_array);echo '</h2>';
		
		$permission_json=json_encode($permission_array);
		//echo '<h2>'.$permission_json.'</h2>';

		$sql='update xml	set
								acl=\''.$permission_json.'\'
							where id=\''.$_POST['id'].'\'';
		$result=run_query($link,$GLOBALS['database'],$sql);
		echo 'updated '.rows_affected($link).' row(s)<br>';
	}
}

if($action=='permission' || $action=='save_permission')
{
	echo '<form method=post>';
	echo '<h3>Set Permissions for Groups / Users for record : '.$_POST['id'].'</h3>';
	echo '<div class=two_column_auto>';
		echo '<div class="border border-warning">';
			manage_group($link,$_POST['id']);
		echo '</div>';
		echo '<div class="border border-warning">';
			manage_user($link,$_POST['id']);
		echo '</div>';
	echo '</div>';
	echo '<button  class="btn btn-sm btn-primary m-1"  name=action value=save_permission>Save Permissions</button>';
	echo '</form>';

}



function manage_group($link,$id)
{
	$acl=get_acl($link,$GLOBALS['database'],'xml','acl','id',$id);
	//echo '<pre>ACL:';print_r($acl);echo '</pre>';
	if(!is_array($acl)){$acl=array();}
	$sql='select * from user_group';
	$result=run_query($link,$GLOBALS['database'],$sql);
	echo '<input type=hidden name=session_name value=\''.session_name().'\'>';
	echo '<input type=hidden name=id value=\''.$id.'\'>';

	echo '<table class="table table-sm table-striped">';
	echo '<tr><th>View</th><th>Edit</th><th>Set Permission</th><th>Group</th></tr>';
	while($ar=get_single_row($result))
	{
		$rcheck='';
		$ucheck='';
		$pcheck='';
		
		if(in_array($ar['user_group'],array_keys($acl),true))
		{
			if(strpos($acl[$ar['user_group']],'r')!==false){$rcheck='checked';}
			if(strpos($acl[$ar['user_group']],'u')!==false){$ucheck='checked';}			
			if(strpos($acl[$ar['user_group']],'p')!==false){$pcheck='checked';}			
		}
		
		echo ' <tr>
					<td><input type=checkbox '.$rcheck.' name=\''.$ar['user_group'].'^r\' ></td>
					<td><input type=checkbox '.$ucheck.' name=\''.$ar['user_group'].'^u\' ></td>
					<td><input type=checkbox '.$pcheck.' name=\''.$ar['user_group'].'^p\' ></td>
					<td>'.$ar['user_group'].'</td>
				</tr>';
	}
	echo '</table>';
}



function manage_user($link,$id)
{
	$acl= get_acl($link,$GLOBALS['database'],'xml','acl','id',$id);
	//echo '<pre>ACL:';print_r($acl);echo '</pre>';
	if(!is_array($acl)){$acl=array();}


	$sql='select * from user';
	$result=run_query($link,$GLOBALS['database'],$sql);
	echo '<input type=hidden name=session_name value=\''.session_name().'\'>';
	echo '<input type=hidden name=id value=\''.$id.'\'>';

	echo '<table class="table table-sm table-striped">';
	echo '<tr><th>View</th><th>Edit</th><th>Set Permission</th><th>User</th><th>This User\'s groups</th></tr>';
	while($ar=get_single_row($result))
	{
		//print_r($ar);
		//print_r($ar);
		$rcheck='';
		$ucheck='';
		$pcheck='';
		if(array_key_exists($ar['user'],$acl))
		{
			if(strpos($acl[$ar['user']],'r')!==false){$rcheck=' checked ';}
			if(strpos($acl[$ar['user']],'u')!==false){$ucheck='checked  ';}
			
			/*if(strpos($acl[$ar['user']],'x')!==false)
			{
				echo '<h1>--'.strpos($acl[$ar['user']],'x').'--</h1>';
			}
			else
			{
				
				echo '<h1>--False--</h1>';
			}*/
			
			if(strpos($acl[$ar['user']],'p')!==false){$pcheck='checked';}			
			
		}
		echo '<tr>
				<td><input  type=checkbox '.$rcheck.' name=\''.$ar['user'].'^r\' ></td>
				<td><input  type=checkbox '.$ucheck.' name=\''.$ar['user'].'^u\' ></td>
				<td><input type=checkbox '.$pcheck.' name=\''.$ar['user'].'^p\' ></td>
				
				<td>'.$ar['name'].'('.$ar['user'].')</td>
				<td>'.$ar['group'].'</td>
			</tr>';

	}
	echo '</table>';
}

function save_permission($link,$id,$post,$user)
{
	/*
    [session_name] => sn_1262032656
    [id] => 69
    [3^r] => on
    [3^u] => on
    [4^u] => on
    [4^p] => on
    [action] => save_user
	*/
	
	//check if permitted to change permissions
	if(!is_permitted($link,$GLOBALS['database'],'xml','acl','id',$id,'p',$user)){echo 'not authorized to save permissions<br>';return false;}
	
	$permission=array();
	//echo '<pre>';
	foreach($post as $k=>$v)
	{
		

		if(!in_array($k,array('session_name','action','id')))
		{
			//print_r($permission);
			$tok=explode('^',$k);
			//print_r($tok);
			if(!isset($permission[$tok[0]]))
			{
				$permission[$tok[0]]='';
			}
			$permission[$tok[0]]=$permission[$tok[0]].$tok[1];
			//print_r($permission);
		}
	}
	//print_r($permission);
	return $permission;
}
?>
