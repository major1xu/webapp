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
<head>
    <title>html source code and summary</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <script type="text/javascript" src="js/jquery-1.4.2.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $("h1").click(function(){
                var div = document.getElementById("dom-target");
                var myData = div.textContent;
                alert(myData);
            });
        });
    </script>

    <style type="text/css">
        .blue { color: blue; }
    </style>
</head>
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
?>
<h1>Click here to show summary.</h1>

</body>
</html>
