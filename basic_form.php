<?php
/**
 * Created by PhpStorm.
 * User: minjiexu
 * Date: 3/21/15
 * Time: 2:53 PM
 */
error_reporting(E_ALL);
include_once('simple_html_dom.php');

?>

<html>
<body>

Welcome to

<?php
echo $_GET["websiteurl"];

$html = file_get_html($_GET["websiteurl"]);

echo $html;
?><br>

Your last name is: <?php echo $_GET["lastname"]; ?>

</body>
</html>