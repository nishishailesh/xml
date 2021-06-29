<?php
require_once 'base/verify_login.php';
require_once 'xml_common.php';
///////User code below/////////////////////
$link=get_link($GLOBALS['main_user'],$GLOBALS['main_pass']);
echo '<pre>';print_r($_POST);echo '</pre>';
$action=isset($_POST['action'])?$_POST['action']:'';
//echo 'action='.$action;

if($action=='print')
{
	view_grid($link,$_POST['id']);
}

tail();

function view_grid($link,$id)
{
	echo 'hi';
	
}


function grid_display_direct_xml($link,$xml)
{
  foreach($xml->children() as $node)
  {
    if (count($node->children()) == 0)
    {
      grid_display_leaf($node->getName(),$node);
    }
    else
    {
      grid_display_branch($link,$node);
    }
  }
}

function grid_display_leaf($name,$value)
{
  echo '<li><div class="two_column">
                <div><b>'.$name.':</b></div>
                <div>'.nl2br($value).'</div>
            </div></li>';
}

function grid_display_branch($link,$node)
{
    $ccls=get_classs();
    echo '<li>
				<span class=" text-info" data-toggle="collapse" data-target=".'.$ccls.'" >'.$node->getName().'</span>';
				echo '<ul class="'.$ccls.' collapse show">';
					grid_display_direct_xml($link,$node);
				echo '</ul>';	
	echo '</li>';
}

function view_grid($link,$id)
{
	$sql='select * from xml where id=\''.$id.'\'';
	$result=run_query($link,$GLOBALS['database'],$sql);
	$ar=get_single_row($result);
	$xml=simplexml_load_string($ar['xml']);
		
	echo '<form method=post>';
	echo '<input type=hidden name=session_name value=\''.session_name().'\'>';
	//	<!-- style="position:fixed;top:0;left:300px"--> 
	echo '<div class=bg-warning><span ><h2 class="d-inline">'.$xml->getName().':</h2><input type=text readonly name=id value=\''.$id.'\'>';
	echo '<input  class="btn btn-sm btn-secondary m-1 print_hide"  type=submit name=action value=edit>';
	echo '<button  formaction=print_single.php formtarget=_blank class="btn btn-sm btn-secondary m-1 print_hide"  type=submit name=action value=print>print</button></div>';
	echo '<ul>';
	grid_display_direct_xml($link,$xml);
	echo '</ul>';
	echo '</form>';
}

?>
