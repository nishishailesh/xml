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
//display_direct_xml($xml,'_99');
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
  echo '<li><b>'.$name.':</b>'.$value.'</li>';
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
  echo '<li><b>'.$name.':</b><input type=text value=\''.$value.'\'></li>';
}


////best display
/*
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
  echo '<li><b>'.$name.':</b>'.$value.'</li>';
}

function display_branch($name,$ccls)
{
    echo '<li><span class=" text-info" data-toggle="collapse" data-target=".'.$ccls.'" >'.$name.'</li>';
}




$xml=simplexml_load_file("psychiatry.xml");
//echo '<pre>';
//print_r($xml);
echo '<ul><span class=bg-warning>'.$xml->getName().'</span>';
display_direct_xml($xml,'_99');
echo '</ul>';

//$objJsonDocument = json_encode($xml);
//$ar = json_decode($objJsonDocument, True);
//print_r($ar);
//display_xml($xml->getName(),$ar,'xx');


//$xmlDoc = new DOMDocument();
//$xmlDoc->preserveWhiteSpace = false;

//$xmlDoc->load("psychiatry.xml");
//print $xmlDoc->saveXML();

//$x = $xmlDoc->documentElement;
//print_node($x,'root');

function display_xml($key,$val,$pcn)
{
    if(is_array($val))
    {
      if(count($val)>0)
      {
        $ccn='_'.rand(10,100);
        echo '<li class="'.$pcn.'" ><span class=" text-info " data-toggle="collapse" data-target=".'.$ccn.'" >'.$key.'</span><ul>';
        foreach($val as $k=>$v)
        {
          display_xml($k,$v,$ccn);
        }
        echo '</ul></li>';
      }
      else
      {
        echo '<li class=" '.$pcn.'">'.$key.':</li>';
      }
    }
    else
    {
      echo '<li class=" '.$pcn.'">'.$key.':'.$val.'</li>';
    }
}

function print_nodex($x,$class_name)
{
  if($x->hasChildNodes() || $x->nodeName!='#text')
  {
    $child_class_name=str_replace('/','-',$x->getNodePath());
    echo '<ul>';
    echo '<li class=\''.$class_name.'\'>'.$x->nodeName.':';
    $len=$x->childNodes->length;
    for ($i=0;$i<$len;$i++) 
    {
      print_node($x->childNodes[$i],$child_class_name);
    }
    echo '</li>';
    echo '</ul>';
  }
  else
  {
      echo $x->nodeValue;
  }
}



function print_node($x,$class_name)
{
  if($x->hasChildNodes() || $x->nodeName!='#text')
  {
    $child_class_name=str_replace('/','-',$x->getNodePath());
    echo '<ul>';
    echo '<li class=\''.$class_name.'\'>'.$x->nodeName.':';
    $len=$x->childNodes->length;
    for ($i=0;$i<$len;$i++) 
    {
      print_node($x->childNodes[$i],$child_class_name);
    }
    echo '</li>';
    echo '</ul>';
  }
  else
  {
      echo $x->nodeValue;
  }
}

function print_node_good_plain($x)
{
  if($x->hasChildNodes() || $x->nodeName!='#text')
  {
    echo '<ul>';
    echo '<li>'.$x->nodeName.':';
    $len=$x->childNodes->length;
    for ($i=0;$i<$len;$i++) 
    {
      print_node($x->childNodes[$i]);
    }
    echo '</li>';
    echo '</ul>';
  }
  else
  {
      print $x->nodeValue;
  }
}

*/

?>
