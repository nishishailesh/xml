<?php
echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">';
echo '<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>';


$GLOBALS['class_name']=101;
$GLOBALS['id_name']=101;

$link=get_link('root','nishiiilu');
$GLOBALS['database']='xml';

$sql='select * from xml where id=1';
$result=run_query($link,$GLOBALS['database'],$sql);
$ar=get_single_row($link,$result);

//$GLOBALS['xml']=simplexml_load_file("psychiatry.xml");
$GLOBALS['xml']=simplexml_load_string($ar['xml']);

save_post_as_xml($GLOBALS['xml']);
$sql='update xml set xml=\''.$GLOBALS['xml']->asXML().'\' where  id=\''.$ar['id'].'\'';
run_query($link,$GLOBALS['database'],$sql);

//echo '<pre>';
//print_r($GLOBALS['xml']);
echo '<form method=post>';
echo '<input type=submit name=action value=save>';

echo '<ul><span class=bg-warning>'.$GLOBALS['xml']->getName().'</span>';
edit_direct_xml($link,$GLOBALS['xml'],'_99');
echo '</ul>';

echo '</form>';

//echo '<pre>';
//print_r($_POST);
//echo '</pre>';

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
      $ccls=get_classs();
      display_branch($node->getName(),$ccls);
      echo '<ul class="'.$ccls.' collapse show">';
        display_direct_xml($node,$ccls);
      echo '</ul>';
    }
  }
}

function display_leaf($name,$value,$cls)
{
  echo '<li><div class=two_column>
                <div><b>'.$name.':</b></div>
                <div>'.$value.'</div>
            </div></li>';
}

function display_branch($name,$ccls)
{
    echo '<li><span class=" text-info" data-toggle="collapse" data-target=".'.$ccls.'" >'.$name.'</span></li>';
}

function edit_direct_xml($link,$xml,$cls)
{
  foreach($xml->children() as $node)
  {
    if (count($node->children()) == 0)
    {
      edit_leaf($link,$node->getName(),$node);
    }
    else
    {
      $ccls=get_classs();
      display_branch($node->getName(),$ccls);
      echo '<ul class="'.$ccls.' collapse show">';
        edit_direct_xml($link,$node,$ccls);
      echo '</ul>';
    }
  }
}

function edit_leaf($link,$name,$value)
{
  echo '<li>
    <div class=two_column>
      <div><b>'.$name.':</b></div>
      <div>';
  edit_field($link,$value);    
  echo '</div></div></li>';
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
	else if($type=='dependent')
  {
     $source=$node->attributes()->{'source'};    
    if($source=='table')
    {     
      $value=get_dependent_value_from_table($link,$node);
      //print_r($xml);
      //print_r($xpath);
      echo '<input type=text class="w-100 form-control"  name=\''.$element_name.'\'  value=\''.$value.'\'>';
    }
  }  
  else
  {
    echo '<input type=text class="w-100 form-control"  name=\''.$element_name.'\'  value=\''.$node.'\'>';
  }
   
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

function get_single_row($link,$result)
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
	$result=run_query($link,$GLOBALS['database'],$sql);
	$ret=array();
  //echo gettype($field_name);
	while($ar=get_single_row($link,$result))
	{
    //print_r($ar);
		$ret[]=$ar[$field_name];
	}
	return $ret;
}

function mk_select_from_sql($link,$sql,$field_name,$select_name,$select_id,$disabled='',$default='',$blank='no')
{
	//echo '<h1>'.$blank.'</h1>';
	$ar=mk_array_from_sql($link,$sql,$field_name);
  //print_r($ar);
  //echo $field_name;
	if($blank=='yes')
	{
		array_unshift($ar,"");
	}
	mk_select_from_array($select_name,$ar,$disabled,$default);
}


function get_dependent_value_from_table($link,$node)
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
  $result = $GLOBALS['xml']->xpath($depends_on);
  $sql='select * from `'.$table.'` where `'.$source_field.'`=\''.(string)$result[0].'\'';
  //echo '<pre>';echo $sql;
  //return (string)$depends_on;
  $sql_result=run_query($link,$GLOBALS['database'],$sql);
  $ar=get_single_row($link,$sql_result);
  return $ar[(string)$target_field];
}
?>
<style>
.two_column 
{
  display: grid;
  grid-template-columns: 20% 80%;
}


</style>
