<?php
echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">';
echo '<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>';

$GLOBALS['class_name']=101;

$xml=simplexml_load_file("psychiatry.xml");
//echo '<pre>';
//print_r($xml);
echo '<ul><span class=bg-warning>'.$xml->getName().'</span>';
edit_direct_xml($xml,'_99');
display_direct_xml($xml,'_99');
echo '</ul>';


function get_classs()
{
  $GLOBALS['class_name']=$GLOBALS['class_name']+1;
  return '_'.$GLOBALS['class_name'];
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

function edit_direct_xml($xml,$cls)
{
  foreach($xml->children() as $node)
  {
    if (count($node->children()) == 0)
    {
      edit_leaf($node->getName(),$node,$cls);
    }
    else
    {
      $ccls=get_classs();
      display_branch($node->getName(),$ccls);
      echo '<ul class="'.$ccls.' collapse show">';
        edit_direct_xml($node,$ccls);
      echo '</ul>';
    }
  }
}

function edit_leaf($name,$value,$cls)
{
  echo '<li>
    <div class=two_column>
      <div><b>'.$name.':</b></div>
      <div>';
  edit_field($value);    
  echo '</div></div></li>';
}



function edit_field($node)
{
  $type=$node->attributes()->{'type'};
	if($type=='date')
  {
     echo '<input type=date value=\''.$node.'\'>'; 
  }
  else
  {
    echo '<input type=text value=\''.$node.'\'>';
  }
   
}

?>
<style>
.two_column 
{
  display: grid;
  grid-template-columns: 33% 67%;
}
</style>
