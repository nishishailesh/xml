<?php


function login($action='start.php')
{
echo '
				<form method=post action=\''.$action.'\' class="form-group jumbotron  m-0 p-3">
						<h3>Login</h3>
						<div><input class="form-control" type=text name=login placeholder=Username></div>
						<div><input class="form-control" type=password name=password placeholder=Password></div>
						<input type=hidden name=session_name value=\''.session_name().'\'>
						<button class="form-control btn btn-primary" type=submit name=action value=login>Login</button></div>
				</form>
	';
}

function head($title='blank')
{
	if(!isset($GLOBALS['nojunk']))
	{
		echo '
		<!DOCTYPE html>
		<html lang="en">
		<head>
		  <title>'.$title.'</title>
		  <meta charset="utf-8">
		  <meta name="viewport" content="width=device-width, initial-scale=1">
		  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
		  <script src="bootstrap/jquery-3.3.1.js"></script>
		  <script src="bootstrap/popper.js"></script>
		  <script src="bootstrap/js/bootstrap.min.js"></script> 		  		  
		  <style>
			  #main_container 
				{
					display: grid;
					grid-template-rows: auto auto;
				}
			  #root_menu
				{
					grid-row-start:1;
					grid-row-end:2;
					justify-self:end;
				}
			  #application
				{
					grid-row-start:2;
					grid-row-end:3;
				}	
		  </style>
		</head>
		<body>';
	}
}

function tail()
{
	if(!isset($GLOBALS['nojunk']))
	{
		echo '</body></html>';
	}
}


/////////////////////////////////////


function get_link($u,$p)
{
	$link=mysqli_connect('127.0.0.1',$u,$p);
	//$link=mysqli_connect('gmcsurat.edu.in',$u,$p,'',13306);
	if(!$link)
	{
		echo 'error1:'.mysqli_error($link); 
		return false;
	}
	return $link;
}

function get_remote_link($ip,$u,$p)
{
	$link=mysqli_connect($ip,$u,$p);
	if(!$link)
	{
		echo 'error1:'.mysqli_error($link); 
		return false;
	}
	return $link;
}

function run_query($link,$db,$sql)
{
	$db_success=mysqli_select_db($link,$db);
	//echo $sql;
	if(!$db_success)
	{
		echo 'error2:'.mysqli_error($link); return false;
	}
	else
	{
		$result=mysqli_query($link,$sql);
	}
	
	if(!$result)
	{
		echo 'error3:'.$sql.'<br>'.mysqli_error($link); return false;
	}
	else
	{
		return $result;
	}	
}

function get_single_row($result)
{
		if($result!=false)
		{
			return mysqli_fetch_assoc($result);
			//return NULL if no row (not FALSE)
		}
		else
		{
			//return false;
			echo 'error get_single_row():'.mysqli_error($link); return false;
		}
}

function my_safe_string($link,$str)
{
	return mysqli_real_escape_string($link,$str);
} 

function  last_autoincrement_insert($link)
{
	return mysqli_insert_id($link);
}

function get_row_count($result)
{
  return mysqli_num_rows($result);
}
////////////////////////////////////////

//////authorization based control/////////
function rows_affected($link)
{
	return mysqli_affected_rows($link);
}


function get_authorization($link)
{
	$user=get_user_info($link,$_SESSION['login']);
	return $auth=explode(',',$user['authorization']);
}

function is_authorized($link,$permission)
{
	$auth=get_authorization($link);
	if(in_array($permission,$auth))
	{
		return true;
	}
	else
	{
		return false;
	}
}


/////// much better ACL////////////
//user table must have field called group (group is also a mysql keyword, so used carefully in statement
//user groups are comma seperated values
//uses session variable login
function get_group($link,$user_id)
{
	$user=get_user_info($link,$user_id);
	return $auth=explode(',',$user['group']);
}


///data table must have a field for acl
//it is a json string {"x":"y","a":"b"}
//first key-part of json data is group. second value-part is type of permission defined as single letter. e.g "ru" mean read and update
function get_acl($link,$db,$table,$field,$one_field_primary_key,$one_field_primary_value)
{
	
	$sql='select `'.$field.'` from `'.$table.'` where `'.$one_field_primary_key.'` = \''.$one_field_primary_value.'\'';
	//echo $sql.'<br>';
	$result=run_query($link,$db,$sql);
	$ar=get_single_row($result);
	//echo 'x';print_r($ar);echo 'y';
	$acl=json_decode($ar['acl']);
	//echo 'x';print_r($acl);echo 'y';
	return $acl;
}

///checks user id and groups with possible entry in ACL fields
//returns false if not permitted
function is_permitted($link,$db,$table,$field,$id_fname,$id,$permission_type,$user)
{
	$this_style=isset($GLOBALS['per_style'])?$GLOBALS['per_style']:'style="display:none"';
	
	echo '<div '.$this_style.'>';
	echo 'is_permitted('.$permission_type.')<br>';
	//$acl=(array)get_acl($link,$GLOBALS['database'],'xml','acl','id',$id);
	$acl=(array)get_acl($link,$db,$table,$field,$id_fname,$id);
	$grp=get_group($link,$user);
	
	echo '<pre>ACL:';print_r($acl);echo '</pre>';
	echo '<pre>GROUP:';print_r($grp);echo '</pre>';
	
	$ret=false;
	
	if(array_key_exists($user,$acl))
	{
		//echo 'yessss:'.$acl[$_SESSION['login']];
		//strpos return position or false
		echo 'ACL entry for user:'.$user.' found<br>';
		if(strpos($acl[$user],$permission_type)!==false)
		{
			echo 'permitted because you are allowed as user:'.$user.' and have "'.$permission_type.'" permission. <br>Success<br>';
			$ret=true;
		}
		else
		{
			echo 'not permitted as user:'.$user.' for "'.$permission_type.'" permission. <br>';
		}
	}
	else
	{
		echo 'no ACL entry for user:'.$user.'<br>';
	}
	
	if($ret==false)
	{
		foreach($grp as $k=>$v)
		{
			echo 'User belong to group:('.$k.'===>'.$v.')<br>';
			
			if(in_array($v,array_keys($acl)))
			{
				echo 'your group '.$v.' have entry in acl list<br>';

				if(isset($acl[$v]))
				{
					if(strpos($acl[$v],$permission_type)!==false)
					{
						echo 'your group '.$v.' have entry in acl list and this group have "'.$permission_type.'" permission. <br>Success<br>';
						$ret=true;
						break;		//go ahead
					}
					else
					{
						echo 'your group '.$v.' have entry in acl list but, this group DONOT have "'.$permission_type.'" permission. <br>Falied<br>';
					}
				}
			}
			else
			{
				echo 'no entry for this group in acl<br>';
			}
		}
	}	
	
	if ($ret===false){echo 'not authorized<br>';}
	echo '</div>';
	return $ret;
}

?>
