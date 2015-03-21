<?php
# create and load the HTML
include('simple_html_dom.php');

$html = new simple_html_dom();
$html->load("<html><body><p>Hello <script language=\"php\">  </script>  !</p><p>We're here</p></body></html>");
 
# get an element representing the second paragraph
$element = $html->find("p");
 
# modify it
$element[1]->innertext .= " and we're here to stay.";
 
# output it!
echo $html->save();
?>
