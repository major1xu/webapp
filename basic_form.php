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
<!-- http://stackoverflow.com/questions/23740548/how-to-pass-variables-and-data-from-php-to-javascript -->

Welcome to
<?php
echo $_GET["websiteurl"];
?>

<div id="dom-target" >
<?php
// need to check if the url is reachable, throw 404 if we can not find it
// also needed: define a page size limit, if too big, it could crash the server or take too long, so throw an error message
// nice to have: add a progressive bar
$html = file_get_html($_GET["websiteurl"]);

if($html == false) {
    echo "<br>Failed to get the url: ";
}
else {
    echo "<br>";
    echo "<textarea id=\"Desc\" cols=\"45\" rows=\"30\" wrap=\"soft\" name=\"Desc\">";
    echo $html;
    echo "</textarea>";
}
?>
</div>

<?php
echo "<br>";
echo "html doc summary:";
?>

<script type="text/javascript">
    var div = document.getElementById("dom-target");
    var myData = div.textContent;
    alert(myData);
</script>
</body>
</html>
