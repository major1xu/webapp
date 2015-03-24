<?php
/**
 * Created by PhpStorm.
 * User: minjiexu
 * Date: 3/21/15
 * Time: 2:53 PM
 */
error_reporting(E_ERROR);
include_once('simple_html_dom.php');

// need to check if the url is reachable, throw 404 if we can not find it
// also needed: define a page size limit, if too big, it could crash the server or take too long, so throw an error message
// nice to have: add a progressive bar
$html = file_get_html($_GET["websiteurl"]);

if($html == false) {
    echo '<script language="javascript">';
    echo 'alert("Failed to get the url:" )';
    echo '</script>';
}

?>

<html>
<head>
    <title>html source code and summary</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <script type="text/javascript" src="js/jquery-1.4.2.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $("h3").click(function(){
                var source_div = document.getElementById("dom-target");
                //var html_string = source_div.getElementById("Desc")
                var html_textarea = GetElementInsideContainer("dom-target", "Desc");
                alert(html_textarea.textContent);
                var myData = source_div.textContent;
                var destination_div = document.getElementById("dom-summary");

                // here we do the summary stuff, also need to bind the click of tags with the dom-target (the source code)
                destination_div.textContent = html_textarea.textContent;
                parser = new DOMParser();
                doc = parser.parseFromString(html_textarea.textContent, "text/xml");
                // a rough idea: traverse the html document, for each tag, put it in a hash map, also create a count for
                // each tag. At the end, show the tag/count pairs.

                walkDOM(doc);
            });
        });

        function walkDOM(main) {
            var arr = [];
            var loop = function(main) {
                do {

                    if(main.nodeType == 1)
                        arr.push(main);
                    if(main.hasChildNodes())
                        loop(main.firstChild);
                }
                while (main = main.nextSibling);
            }
            loop(main);
            alert(arr.toString());
            return arr;
        }

        function GetElementInsideContainer(containerID, childID) {
            var elm = document.getElementById(childID);
            var parent = elm ? elm.parentNode : {};
            return (parent.id && parent.id === containerID) ? elm : {};
        }
    </script>

    <style type="text/css">
        .blue { color: blue; }
        .noShow {display: none;}
    </style>
</head>
<body>
<!-- http://stackoverflow.com/questions/23740548/how-to-pass-variables-and-data-from-php-to-javascript -->


<?php
if ($html == false) {
    echo "<a href=\"javascript:history.go(-1)\">Go Back</a>";
}
else {
    echo "Welcome to ";
    echo $_GET["websiteurl"];
}
?>

<table>
    <tr>
        <td>
            <div id="dom-target" >
            <?php
                echo "<br>";
                echo "<textarea id=\"Desc\" cols=\"45\" rows=\"30\" wrap=\"soft\" name=\"Desc\">";
                echo $html;
                echo "</textarea>";
            ?>
            </div>
        </td>
        <td><h3>Click to show summary.</h3></td>
        <td>
            <div id="dom-summary">
                <textarea id="summary" cols="45" rows="30" wrap="soft" name="Summary">
                </textarea>
            </div>
        </td>
    </tr>
</table>

</body>
</html>
