<?php
$GLOBALS['nojunk']='';
require_once 'base/verify_login.php';
require_once 'xml_common.php';
///////User code below/////////////////////
$link=get_link($GLOBALS['main_user'],$GLOBALS['main_pass']);
//echo '<pre>';print_r($_POST);echo '</pre>';

$action=isset($_POST['action'])?$_POST['action']:'';
//echo 'action='.$action;

if($action=='print')
{
	view_grid($link,$_POST['id']);
}


function slash_to_carett($str)
{
  return str_replace('/','_',$str);
}

function caret_to_slashh($str)
{
  return str_replace('^','/',$str);
}

?>

<style>
#root
{
        display: grid;
        grid-template-columns: 30% 70%;
        grid-template-areas:

/*
       '_discharge_card_institute_location  	_discharge_card_clinical_information 	_discharge_card_clinical_information 	_discharge_card_treatment_given'
       '_discharge_card_patient_demography  	_discharge_card_clinical_information	_discharge_card_clinical_information	_discharge_card_notes'
       '_discharge_card_advice_on_discharge 	_discharge_card_advice_on_discharge	_discharge_card_authorization		_discharge_card_authorization';
*/

'_discharge_card_institute_location		_discharge_card_patient_demography'
'_discharge_card_treatment_given		_discharge_card_patient_demography'
'_discharge_card_notes					_discharge_card_clinical_information'
'_discharge_card_authorization			_discharge_card_clinical_information'
'_discharge_card_advice_on_discharge 	_discharge_card_clinical_information'
'_discharge_card_XML 					_discharge_card_clinical_information';

  	grid-gap: 10px;
  	padding: 10px;
}


#_discharge_card_institute_location		{grid-area:_discharge_card_institute_location;}
#_discharge_card_clinical_information	{grid-area:_discharge_card_clinical_information;}
#_discharge_card_patient_demography		{grid-area:_discharge_card_patient_demography;}
#_discharge_card_treatment_given		{grid-area:_discharge_card_treatment_given;}
#_discharge_card_advice_on_discharge	{grid-area:_discharge_card_advice_on_discharge;}
#_discharge_card_notes					{grid-area:_discharge_card_notes;}
#_discharge_card_authorization			{grid-area:_discharge_card_authorization;}

</style>

<?php
tail();


function grid_display_direct_xml($link,$xml)
{
  foreach($xml->children() as $node)
  {
    if (count($node->children()) == 0)
    {
      grid_display_leaf($node);
    }
    else
    {
      grid_display_branch($link,$node);
    }
  }
}

function grid_display_leaf($node)
{
  $dom = dom_import_simplexml($node);
  $element_name=slash_to_carett($dom->getNodePath());

  echo '<li id='.$element_name.'><div class="two_column">
                <div><b>'.$node->getName().':</b></div>
                <div>'.(string)$node.'</div>
            </div></li>';
  //<div>'.nl2br((string)$node).'</div>
}

function grid_display_branch($link,$node)
{
  $dom = dom_import_simplexml($node);
  $element_name=slash_to_carett($dom->getNodePath());
  $ccls=get_classs();
    echo '<li id='.$element_name.'>
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
	//echo '<div class=bg-warning><span ><h2 class="d-inline">'.$xml->getName().':</h2><input type=text readonly name=id value=\''.$id.'\'>';
	echo '<div class=bg-warning><span ><h2 class="d-inline">'.$xml->getName().':'.$id.'</h2>';
	//echo '<input  class="btn btn-sm btn-secondary m-1 print_hide"  type=submit name=action value=edit>';
	//echo '<button  formaction=print_single.php formtarget=_blank class="btn btn-sm btn-secondary m-1 print_hide"  type=submit name=action value=print>print</button>';
	echo '</div>';
	echo '<ul id=root>';
	grid_display_direct_xml($link,$xml);
	echo '</ul>';
	echo '</form>';
}

?>


