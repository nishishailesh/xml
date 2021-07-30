<?php
function get_classs()
{
  $GLOBALS['class_name']=$GLOBALS['class_name']+1;
  return '_'.$GLOBALS['class_name'];
}

function get_idd()
{
  $GLOBALS['id_name']=$GLOBALS['id_name']+1;
  return '__'.$GLOBALS['id_name'];
}

function display_direct_xml($link,$xml)
{
  foreach($xml->children() as $node)
  {
    if (count($node->children()) == 0)
    {
	  $html=$node->attributes()->{'html'};   
      display_leaf($node->getName(),$node,$html);
    }
    else
    {
      display_branch($link,$node);
    }
  }
}

function display_leaf($name,$value,$html)
{
	if($html=='')
	{
		echo '<li><div class="two_column">
                <div><b>'.$name.':</b></div>
                <div>'.nl2br($value).'</div>
            </div></li>';
	}
	else
	{
		echo '<li><div class="two_column">
                <div><b>'.$name.':</b></div>
                <div>'.($value).'</div>
            </div></li>';
	}
}
//<div>'.nl2br($value).'</div>

function display_branch($link,$node)
{
    $ccls=get_classs();
    echo '<li>
				<span class=" text-info" data-toggle="collapse" data-target=".'.$ccls.'" ><b>'.$node->getName().'</b></span>';
				echo '<ul class="'.$ccls.' collapse show">';
					display_direct_xml($link,$node);
				echo '</ul>';	
	echo '</li>';
}


function edit_branch($link,$node)
{
    $ccls=get_classs();
    echo '<li>
				<span class=" text-info" data-toggle="collapse" data-target=".'.$ccls.'" ><b>'.$node->getName().'</b></span>';
				echo '<ul class="'.$ccls.' collapse show">';
					edit_direct_xml($link,$node);
				echo '</ul>';	
	echo '</li>';
}

function edit_direct_xml($link,$xml)
{
  foreach($xml->children() as $node)
  {
    if (count($node->children()) == 0)
    {
		edit_leaf($link,$node->getName(),$node);
    }
    else
    {
      edit_branch($link,$node);
    }
  }
}

function edit_leaf($link,$name,$value)
{
	echo '<li>
			<div class=two_column>
				<div><b>'.$name.':</b></div>
				<div>';edit_field($link,$value);echo '</div>
			</div>
	</li>';
}


function slash_to_caret($str)
{
  return str_replace('/','^',$str);
}

function caret_to_slash($str)
{
  return str_replace('^','/',$str);
}

function save_post_as_xml($xml)
{
  foreach($_POST as $k=>$v)
  {
    if($k[0]!='^'){}
    else
    {
      $xpath=caret_to_slash($k);
      $result = $xml->xpath($xpath);
      //print_r($result);
      $result[0][0] = $v;
    }
  }
}

function edit_field($link,$node)
{
  $idd=get_idd();
  $dom = dom_import_simplexml($node);
  $element_name=slash_to_caret($dom->getNodePath());
  $type=$node->attributes()->{'type'};
  $readonly=$node->attributes()->{'readonly'};
  
	if($type=='date')
  {
     echo '<input '.$readonly.' id=\''.$idd.'\' name=\''.$element_name.'\' type=date value=\''.$node.'\'>'; 
  }
	else if($type=='number')
  {
     echo '<input '.$readonly.' id=\''.$idd.'\' name=\''.$element_name.'\' class="w-100 form-control" type=number value=\''.$node.'\'>'; 
  }  
	else if($type=='textarea')
  {
     $rows=$node->attributes()->rows;
     $tiny=isset($node->attributes()->html)?$node->attributes()->html:'';
     //echo '<h1>xx'.$tiny.'yy</h1>';
     //all text area are tiny
     //$tiny='tiny';
     echo '<textarea '.$readonly.' id=\''.$idd.'\' name=\''.$element_name.'\' class="w-100 form-control '.$tiny.'" rows='.$rows.' >'.$node.'</textarea>'; 
  }
	else if($type=='select')
  {
    $source=$node->attributes()->{'source'};
    //<name type="select" source="table" table="icd" field="name">Schizoaffective disorder, bipolar type</name>
    if($source=='table')
    {
      $table=$node->attributes()->{'table'};
      $field_object=$node->attributes()->{'field'};
      //echo '<h1>'.gettype($field_object).'</h1>';
      //echo '<h1>'.gettype($field_object->getName()).'</h1>';
      $field_name=(string)$field_object;
      $sql='select `'.$field_name.'`  from `'.$table.'`';
      //echo $sql;
      //$link,$sql,$field_name,$select_name,$select_id,$disabled='',$default='',$blank='no'
      mk_select_from_sql($link,$sql,$field_name,$element_name,$idd,$readonly,$node,$blank='yes');
    }
  } 

  else
  {
    echo '<input '.$readonly.' type=text class="w-100 form-control"  name=\''.$element_name.'\'  value=\''.$node.'\'>';
  }
   
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

function mk_array_from_sql($link,$sql,$field_name)
{
	//echo $sql;
	$result=run_query($link,$GLOBALS['database'],$sql);
	$ret=array();
	//echo gettype($field_name);
	while($ar=get_single_row($result))
	{
		//print_r($ar);
		$ret[]=$ar[$field_name];
	}
	return $ret;
}

function mk_select_from_sql($link,$sql,$field_name,$select_name,$select_id,$disabled='',$default='',$blank='no')
{
	//echo '<h1>'.$blank.'</h1>';
	//echo $sql;
	$ar=mk_array_from_sql($link,$sql,$field_name);
	//print_r($ar);
	//echo $field_name;
	if($blank=='yes')
	{
		array_unshift($ar,"");
	}
	mk_select_from_array($select_name,$ar,$disabled,$default);
}


function get_dependent_value_from_table($link,$node,$xml)
{
 /*
  * <code 
          type="dependent" 
          depends_on="/discharge_card/clinical_information/diagnosis/icd/name" 
          source="table" 
          table="icd" 
          source_field="name"
          target_field="id">F250</code>
          * */
  $depends_on=$node->attributes()->{'depends_on'};
  $table=$node->attributes()->{'table'};
  $source_field=$node->attributes()->{'source_field'};
  $target_field=$node->attributes()->{'target_field'};

  //echo (string)$depends_on;
  $result = $xml->xpath($depends_on);
  $sql='select * from `'.$table.'` where `'.$source_field.'`=\''.(string)$result[0].'\'';
  //echo '<pre>';echo $sql;
  //return (string)$depends_on;
  $sql_result=run_query($link,$GLOBALS['database'],$sql);
  $ar=get_single_row($link,$sql_result);
  return $ar[(string)$target_field];
}

function main_menu()
{
	echo '<form method=post class="print_hide">
	<input type=hidden name=session_name value=\''.session_name().'\'>
	<button  class="btn btn-sm btn-success m-1"  name=action value=new>New</button>
	<!-- <button  class="btn btn-sm btn-success m-1" name=action value=get_edit_id>Edit</button>
	<button  class="btn btn-sm btn-success m-1" name=action value=get_search>Search</button> -->
	<button  class="btn btn-sm btn-success m-1" name=action value=get_view_id>Search</button>
	</form>';
}

function show_templates($link)
{
	$sql='select * from xml_template';
	$result=run_query($link,$GLOBALS['database'],$sql);
	echo '<form method=post>';
	echo '<input type=hidden name=session_name value=\''.session_name().'\'>';
	echo '<input type=hidden name=action value=select_template>';
	echo '<h4 class="text-danger">Select template from below</h4>';
	while($ar=get_single_row($result))
	{
		echo '<button class="btn btn-sm btn-secondary m-1" name=xml_template_type value=\''.$ar['id'].'\'>'.$ar['template_name'].'</button>';
	}	
	echo '</form>';
}

function insert_template($link,$template_id)
{
	if(!is_permitted($link,$GLOBALS['database'],'xml_template','acl','id',$template_id,'i')){return false;}
	
	$t_sql='select * from xml_template where id=\''.$template_id.'\'';
	$t_result=run_query($link,$GLOBALS['database'],$t_sql);
	$ar=get_single_row($t_result);
	$json_str='{"'.$_SESSION['login'].'":"ru"}';
	echo $json_str.'<br>';
	$sql='insert into xml 
						(	xml_template_id, 
							xml,
							recorded_by,
							recording_time,
							acl
						) 
						values	
						(
							\''.$template_id.'\' , 
							\''.my_safe_string($link,$ar['xml']).'\' ,
							\''.$_SESSION['login'].'\' ,
							\''.strftime("%Y%m%d%H%M%S").'\' ,
							\''.$json_str.'\' 
						)';

	$result=run_query($link,$GLOBALS['database'],$sql);
	$id=last_autoincrement_insert($link);
	//append_meta($link,$id);
	
	return $id;
}


function append_meta($link,$id)
{
	$sql='select * from xml where id=\''.$id.'\'';
	$result=run_query($link,$GLOBALS['database'],$sql);
	$ar=get_single_row($result);
	$xml=simplexml_load_string($ar['xml']);
	$xml_data=$xml->addChild('XML', '');
	$xml_data->addChild('ID', $id);
	$xml_data->addChild('name', $xml->getName());	
	$sql='update xml set xml=\''.my_safe_string($link,$xml->asXML()).'\' where  id=\''.$id.'\'';
	run_query($link,$GLOBALS['database'],$sql);	
}

function edit($link,$id)
{
	//if(!is_authorized($link,'edit')){echo 'not authorized';return false;}
	if(!is_permitted($link,$GLOBALS['database'],'xml','acl','id',$id,'u')){echo 'not authorized';return false;}
	$sql='select * from xml where id=\''.$id.'\'';
	$result=run_query($link,$GLOBALS['database'],$sql);
	$ar=get_single_row($result);
	$xml=simplexml_load_string($ar['xml']);
	echo '<script language="javascript" type="text/javascript" src="tinymce/tinymce.min.js"></script>';
	echo '<script language="javascript" type="text/javascript">
	tinyMCE.init(

			{
			mode : "specific_textareas",
			editor_selector : "tiny",
			plugins: "table",
			menubar : "false",
			toolbar: "table undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent"
			}
		);</script>';
	
	echo '<form method=post>';
	echo '<input type=hidden name=session_name value=\''.session_name().'\'>';
	//	<!-- style="position:fixed;top:0;left:300px"--> 
	echo '<div class=bg-warning><span ><h2 class="d-inline">'.$xml->getName().':<input type=text readonly name=id value=\''.$id.'\'></h2>';
	echo '<input  class="btn btn-sm btn-secondary m-1"  type=submit name=action value=save>';
	echo '<input  class="btn btn-sm btn-secondary m-1"  type=submit name=action value=view></div>';
	echo '<ul>';
	edit_direct_xml($link,$xml);
	echo '</ul>';
	echo '</form>';
}

function view($link,$id)
{
	if(!is_permitted($link,$GLOBALS['database'],'xml','acl','id',$id,'r')){echo 'not authorized';return false;}

	$sql='select * from xml where id=\''.$id.'\'';
	$result=run_query($link,$GLOBALS['database'],$sql);
	$ar=get_single_row($result);
	$xml=simplexml_load_string($ar['xml']);
	$user_data=get_user_info($link,$_SESSION['login']);
	
	echo '<form method=post>';
	echo '<input type=hidden name=session_name value=\''.session_name().'\'>';
	//	<!-- style="position:fixed;top:0;left:300px"--> 
	echo '<div class=bg-warning>
				<h2 class="d-inline">'.$xml->getName().':<input type=text size=10 readonly name=id value=\''.$id.'\'></h2>';
				echo '<input  class="btn btn-sm btn-secondary m-1 print_hide" type=submit name=action value=edit>';
				echo '<input  formaction=permission.php formtarget=_blank class="btn btn-sm btn-secondary m-1 print_hide" type=submit name=action value=permission>';
				echo '<button  formaction=print_single.php formtarget=_blank class="btn btn-sm btn-secondary m-1 print_hide"  type=submit name=action value=print>print</button>';
				echo 'Last Edited by:'.$user_data['name'].'('.$_SESSION['login'].') at '.$ar['recording_time'];
				
	echo '</div>';
	echo '<ul>';
	display_direct_xml($link,$xml);
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
	$sql='update xml set 
				xml=\''.my_safe_string($link,$xml->asXML()).'\' ,
				recorded_by=\''.$_SESSION['login'].'\' ,
				recording_time=\''.strftime("%Y%m%d%H%M%S").'\' 

			where  id=\''.$post['id'].'\'';
	return run_query($link,$GLOBALS['database'],$sql);
}


function show_search_form($link)
{
	$sql='select * from search_path';
	$result=run_query($link,$GLOBALS['database'],$sql);
	echo '<form method=post>';
	echo '<input type=hidden name=session_name value=\''.session_name().'\'>';
	echo '<h4 class="text-danger">Search by Content</h4>';
	echo '<div class=two_column>';
	while($ar=get_single_row($result))
	{
		//print_r($ar);
		echo '<label for=\'xpath_'.$ar['id'].'\'>' .$ar['search_path']. '</label>';
		echo '<input type=text id=\'xpath_'.$ar['id'].'\' name=\'xpath_'.$ar['id'].'\'>';
	}
		echo '<div></div><button  class="btn btn-sm btn-primary m-1"  name=action value=show_search_result>Search</button>';
	
	echo '</div>';

	echo '</form>';	
}

function get_xpath($link,$id)
{
	$sql='select * from search_path where id=\''.$id.'\'';
	//echo $sql;
	$result=run_query($link,$GLOBALS['database'],$sql);
	$ar=get_single_row($result);
	//echo 'sssss';
	//print_r($ar);
	return $ar['search_path'];	
}

function find_search_result($link,$post)
{
	//echo '<form method=post>';
	//echo '<input type=hidden name=session_name value=\''.session_name().'\'>';
	//echo '<h4 class="text-danger">Select record from below</h4>';
	$db_array=array();
	
	foreach($post as $k=>$v)
	{
		//echo substr($k,0,6);
		if(substr($k,0,6)=='xpath_' && strlen($v)>0)
		{
		$db_array[$k]=array();

		$search_id=substr($k,6);
		//echo $search_id;
		$sql='select * from xml';
			$result=run_query($link,$GLOBALS['database'],$sql);
			while($ar=get_single_row($result))
			{
				libxml_use_internal_errors(true); // to suppress warning due to bad xml tags
				$xml=simplexml_load_string($ar['xml']);
				if(!$xml){continue;}
				$xpath=get_xpath($link,$search_id);
				//echo 'ddddd'.$xpath;
				if(count($xresult = $xml->xpath($xpath))>0)
				{
					foreach($xresult as $xk=>$xv)
					{
						//echo $ar['id'].'--->'.$xpath.'====='.$xv.'<br>';
						if(substr_count(strtolower($xv),strtolower($v))>0)
						{
							//echo '<h5>found</h5>';							
							$db_array[$k][]=$ar['id'];
						}
					}
				}
			}
		}
	}
	//echo '<pre>';print_r($db_array);
	$union=array();
	$intersect=array();
	$first=0;
	foreach($db_array as $ids)
	{
		$union=$union+$ids;
		if($first==0){$intersect=$ids;$first=1;}
		else{$intersect=array_intersect($intersect,$ids);}
	}
	//echo '<pre>';print_r($db_array);
	//echo '<pre>';print_r($union);
	//echo '<pre>';print_r($intersect);
	
	//echo '</form>';	
	return array($union,$intersect);
}

function show_search_result($link,$sa,$post)
{
	echo '<table class="table table-sm table-striped">';
	echo '<tr><th  colspan="100%">Any Condition Matching</th></tr>';
	xpath_search_header($link,$post);
	foreach($sa[0] as $id)
	{
		xml_id_view_button($link,$id,$action='','target=_blank',$id,$post);		
	}
	echo '</table>';
	
	echo '<table  class="table table-sm table-striped">';
	echo '<tr><th colspan="100%">All Condition Matching</th></tr>';
	xpath_search_header($link,$post);
	foreach($sa[1] as $id)
	{
		xml_id_view_button($link,$id,$action='','target=_blank',$id,$post);		
	}
	echo '</table>';

}

function xpath_search_header($link,$post)
{
	echo '<tr><td>ID</td>';
	foreach($post as $k=>$v)
	{
		if(substr($k,0,6)=='xpath_' && strlen($v)>0)
		{
			$search_id=substr($k,6);
			//echo $search_id;
			$xpath=get_xpath($link,$search_id);
			echo '<td>'.$xpath.'</td>';
		}
	}	
	echo '</tr>';

}
function xml_id_view_button($link,$id,$action='',$target='',$label='View',$xpath_array)
{
	$id_data=get_id_data($link,$id,$xpath_array);
	echo '<tr>';
	echo '<td><div><form method=post '.$action.' class=print_hide '.$target.'>
	<button class="btn btn-outline-success btn-sm text-dark " name=id value=\''.$id.'\' >'.$label.'</button>
	<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
	<input type=hidden name=action value=view_single>';
	
	echo '</form></td></div>';
	foreach ($id_data as $v)
	{
		echo '<td>'.$v.'</td>';
	}
	echo '</tr>';
}

function get_id_data($link,$id,$xpath_array)
{
	$sql='select * from xml where id=\''.$id.'\'';
	$result=run_query($link,$GLOBALS['database'],$sql);
	$ar=get_single_row($result);
	$xml=simplexml_load_string($ar['xml']);
	if(!$xml){return;}
	$ret=array();
	foreach($xpath_array as $k=>$v)
	{
		if(substr($k,0,6)=='xpath_' && strlen($v)>0)
		{
			$search_id=substr($k,6);
			//echo $search_id;
			$xpath=get_xpath($link,$search_id);
			//echo 'ddddd'.$xpath;
			if(count($xresult = $xml->xpath($xpath))>0)
			{
				foreach($xresult as $xk=>$xv)
				{
					//print_r($xresult);
					$ret[]=(string)$xv[0];
				}
			}
		}
	}	
	//print_r($ret);
	return $ret;
}


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




function is_permitted($link,$db,$table,$field,$id_fname,$id,$permission_type)
{
	echo 'is_permitted('.$permission_type.')<br>';
	//$acl=(array)get_acl($link,$GLOBALS['database'],'xml','acl','id',$id);
	$acl=(array)get_acl($link,$db,$table,$field,$id_fname,$id);
	echo '<pre>ACL:';print_r($acl);echo '</pre>';
	echo '<pre>GROUP:';print_r($GLOBALS['grp']);echo '</pre>';
	
	$ret=false;
	
	if(array_key_exists($_SESSION['login'],$acl))
	{
		//echo 'yessss:'.$acl[$_SESSION['login']];
		//strpos return position or false
		echo 'ACL entry for user:'.$_SESSION['login'].' found<br>';
		if(strpos($acl[$_SESSION['login']],$permission_type)!==false)
		{
			echo 'permitted because you are allowed as user:'.$_SESSION['login'].' and have "'.$permission_type.'" permission. <br>Success<br>';
			$ret=true;
		}
		else
		{
			echo 'not permitted as user:'.$_SESSION['login'].'<br>';
		}
	}
	else
	{
		echo 'no ACL entry for user:'.$_SESSION['login'].'<br>';
	}
	
	if($ret==false)
	{
		foreach($GLOBALS['grp'] as $k=>$v)
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
	return $ret;
}

?>
<style>
.two_column 
{
  display: grid;
  grid-template-columns: 20% 80%;
}

.two_column_40_60
{
  display: grid;
  grid-template-columns: 40% 60%;
}

.two_column_auto
{
  display: grid;
  grid-template-columns: auto auto;
}

@media only print 
{
.print_hide
	 {
		 display:none
	 }
}

*
{
	word-break: break-all;
}

p {
  margin: 0px;
  padding: 0px;
}

</style>
