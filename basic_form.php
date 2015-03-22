<?php
/**
 * Created by PhpStorm.
 * User: minjiexu
 * Date: 3/21/15
 * Time: 2:53 PM
 */
error_reporting(E_ERROR);
include_once('simple_html_dom.php');

?>

<html>
<body>

Welcome to

<?php
echo $_GET["websiteurl"];

// need to check if the url is reachable, throw 404 if we can not find it
// also needed: define a page size limit, if too big, it could crash the server or take too long, so throw an error message
// nice to have: add a progressive bar
$html = file_get_html($_GET["websiteurl"]);

if($html == false)
    echo "<br>Failed to get the url: ";
else
    echo $html;
?><br>

Your last name is: <?php echo $_GET["lastname"]; ?>

</body>
</html>