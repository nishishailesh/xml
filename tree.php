<?php
echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">';
echo '<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>';

$GLOBALS['class_name']=101;
$GLOBALS['id_name']=101;

$link=get_link('root','nishiiilu');
$GLOBALS['database']='xml';


$xml=simplexml_load_file("psychiatry.xml");
//echo '<pre>';
//print_r($xml);
echo '<ul><span class=bg-warning>'.$xml->getName().'</span>';
edit_direct_xml($link,$xml,'_99');
//display_direct_xml($xml,'_99');
echo '</ul>';


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
    echo '<li><span class=" text-info" data-toggle="collapse" data-target=".'.$ccls.'" >'.$name.'</li>';
}

function edit_direct_xml($link,$xml,$cls)
{
  echo '<form>';
  foreach($xml->children() as $node)
  {
    if (count($node->children()) == 0)
    {
      edit_leaf($link,$node->getName(),$node,$cls);
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
  echo '</form>';
}

function edit_leaf($link,$name,$value,$cls)
{
  echo '<li>
    <div class=two_column>
      <div><b>'.$name.':</b></div>
      <div>';
  edit_field($link,$value);    
  echo '</div></div></li>';
}



function edit_field($link,$node)
{
  $idd=get_idd();
  $type=$node->attributes()->{'type'};
	if($type=='date')
  {
     echo '<input id=\''.$idd.'\' type=date value=\''.$node.'\'>'; 
  }
	else if($type=='number')
  {
     echo '<input id=\''.$idd.'\' class="w-100 form-control" type=number value=\''.$node.'\'>'; 
  }  
	else if($type=='textarea')
  {
     $rows=$node->attributes()->rows;
     echo '<textarea id=\''.$idd.'\' class="w-100 form-control" rows='.$rows.' value=\''.$node.'\'>'.$node.'</textarea>'; 
  }
	else if($type=='select')
  {
    $source=$node->attributes()->{'source'};
    //<name type="select" source="table" table="icd" field="name">Schizoaffective disorder, bipolar type</name>
    if($source=='table')
    {
      $table=$node->attributes()->{'table'};
      $field=$node->attributes()->{'field'};
      $sql='select `'.$field.'`  from `'.$table.'`';
      //echo $sql;
      //$link,$sql,$field_name,$select_name,$select_id,$disabled='',$default='',$blank='no'
      mk_select_from_sql($link,$sql,$field,$idd,$idd,'',$node,$blank='yes');
    }
  }
  else
  {
    echo '<input type=text class="w-100 form-control" value=\''.$node.'\'>';
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
  //echo $field_name;
	while($ar=get_single_row($link,$result))
	{
    print_r($ar);
		$ret[]=$ar[$field_name];
	}
	return $ret;
}

function mk_select_from_sql($link,$sql,$field_name,$select_name,$select_id,$disabled='',$default='',$blank='no')
{
	//echo '<h1>'.$blank.'</h1>';
	$ar=mk_array_from_sql($link,$sql,$field_name);
  print_r($ar);
  echo $field_name;
	if($blank=='yes')
	{
		array_unshift($ar,"");
	}
	mk_select_from_array($select_name,$ar,$disabled,$default);
}

?>
<style>
.two_column 
{
  display: grid;
  grid-template-columns: 33% 67%;
}


</style>
