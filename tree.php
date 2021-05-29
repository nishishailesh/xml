<?php
echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">';
echo '<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>';

$xmlDoc = new DOMDocument();
$xmlDoc->preserveWhiteSpace = false;

$xmlDoc->load("psychiatry.xml");
//print $xmlDoc->saveXML();

$x = $xmlDoc->documentElement;
print_node($x);


function print_node($x)
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


?>
