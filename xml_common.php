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

function display_direct_xml($xml,$cls)
{
  foreach($xml->children() as $node)
  {
    if (count($node->children()) == 0)
    {
      display_leaf($node->getName(),$node,$cls);
    }
    else
    {
      display_branch($node);
    }
  }
}

function display_leaf($name,$value,$cls)
{
  echo '<li><div class="two_column '.$cls.' ">
                <div><b>'.$name.':</b></div>
                <div>'.$value.'</div>
            </div></li>';
}

function display_branch($link,$node)
{
    $ccls=get_classs();
    echo '<li>
				<span class=" text-info" data-toggle="collapse" data-target=".'.$ccls.'" >'.$node->getName().'</span>';
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
      display_branch($link,$node);
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
	if($type=='date')
  {
     echo '<input id=\''.$idd.'\' name=\''.$element_name.'\' type=date value=\''.$node.'\'>'; 
  }
	else if($type=='number')
  {
     echo '<input id=\''.$idd.'\' name=\''.$element_name.'\' class="w-100 form-control" type=number value=\''.$node.'\'>'; 
  }  
	else if($type=='textarea')
  {
     $rows=$node->attributes()->rows;
     echo '<textarea id=\''.$idd.'\' name=\''.$element_name.'\' class="w-100 form-control" rows='.$rows.' >'.$node.'</textarea>'; 
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
      mk_select_from_sql($link,$sql,$field_name,$element_name,$idd,'',$node,$blank='yes');
    }
  } 
  else
  {
    echo '<input type=text class="w-100 form-control"  name=\''.$element_name.'\'  value=\''.$node.'\'>';
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
	<button  class="btn btn-sm btn-success m-1" name=action value=get_edit_id>Edit</button>
	<button  class="btn btn-sm btn-success m-1" name=action value=get_search>Search</button>
	</form>';
}
?>
<style>
.two_column 
{
  display: grid;
  grid-template-columns: 20% 80%;
}

@media only print 
{
.print_hide
	 {
		 display:none
	 }
}

</style>
