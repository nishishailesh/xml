<?php
echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">';
echo '<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>';

$GLOBALS['linkk']=get_link('root','nishiiilu');
$GLOBALS['database']='xml';

$xml = new DOMDocument();
$xml->load('psychiatry.xml');
 
$xsl = new DOMDocument;
$xsl->load('psychiatry.xsl');
 
$proc = new XSLTProcessor();
//$proc->registerPHPFunctions('mk_select_from_sql');
$proc->registerPHPFunctions();
$proc->importStyleSheet($xsl);
 
echo $proc->transformToXML($xml);




function mk_array_from_sql($sql,$field_name)
{
	$result=run_query($GLOBALS['linkk'],$GLOBALS['database'],$sql);
	$ret=array();
	while($ar=get_single_row($result))
	{
		$ret[]=$ar[$field_name];
	}
	return $ret;
}

function mk_array_from_sql_kv($link,$sql,$field_name_k,$field_name_v)
{
	$result=run_query($link,$GLOBALS['database'],$sql);
	$ret=array();
	while($ar=get_single_row($result))
	{
		$ret[$ar[$field_name_k]]=$ar[$field_name_v];
	}
	return $ret;
}

function mk_select_from_sql($sql,$field_name,$select_name,$select_id,$disabled='',$default='',$blank='no')
{
	//echo '<h1>'.$blank.'</h1>';
	$ar=mk_array_from_sql($sql,$field_name);
	if($blank=='yes')
	{
		array_unshift($ar,"");
	}
	mk_select_from_array($select_name,$ar,$disabled,$default);
}

function mk_select_from_array($name, $select_array,$disabled='',$default='')
{	
	echo '<select  '.$disabled.' name=\''.$name.'\'>';
	foreach($select_array as $key=>$value)
	{
				//echo $default.'?'.$value;
		if($value==$default)
		{
			echo '<option  selected > '.$value.' </option>';
		}
		else
		{
			echo '<option > '.$value.' </option>';
		}
	}
	echo '</select>';	
	return TRUE;
}


function mk_select_from_sql_kv($link,$sql,$field_name_k,$field_name_v,$select_name,$select_id,$disabled='',$default='',$blank='no')
{
	$ar=mk_array_from_sql_kv($link,$sql,$field_name_k,$field_name_v);
	if($blank=='yes')
	{
		array_unshift($ar,"");
	}
	mk_select_from_array_kv($select_name,$ar,$disabled,$default);
}


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

?>
