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
    <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="js/dom_processing.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            var parsed_textarea =  GetElementInsideContainer("dom-source-parsed", "Parsed");

            $("h3").click(function(){
                var source_div = document.getElementById("dom-source");
                var source_textarea = GetElementInsideContainer("dom-source", "Desc");
                var summary_div = document.getElementById("dom-summary");

                parser = new DOMParser();
                //doc = parser.parseFromString(source_textarea.textContent, "text/xml");
                doc = parseXml(source_textarea.textContent);

                // here we do the summary stuff, also need to bind the click of tags with the dom-source (the source code)
                // a rough idea: traverse the html document, for each tag, put it in a hash map, also create a count for
                // each tag. At the end, show the tag/count pairs.

                var hashmap = walkDOM(doc);
                var destination_div = document.getElementById("dom-source-parsed");

                var string ='';
                // print out map content
                // https://sunfishempire.wordpress.com/2014/08/19/5-ways-to-use-a-javascript-hashmap/
                //
                // MXU (TBD): in click function for each tag, upon click, we go to source_div (dom-source), and highlight
                // the corresponding elements.
                //
                for (var x in hashmap)
                {
                    string = string + x;
                    string = string + ":";
                    var value = hashmap[x];
                    string = string + value;
                    string = string + ', ';

                    // http://stackoverflow.com/questions/9643311/pass-string-parameter-in-an-onclick-function
                    var inputElement = document.createElement('input');
                    inputElement.type = "button"
                    inputElement.value = x + ":" + value;
                    inputElement.addEventListener('click', function(){
                        // http://stackoverflow.com/questions/12024483/how-to-pass-parameter-to-function-using-in-addeventlistener
                        parsed_textarea.textContent  = this.value;

                        $("#parsed_textarea").highlight({
                            words:  [this.value],
                            width:  500,
                            height: 250
                        });

                    }, false);
                    summary_div.appendChild(inputElement);
                }
                parsed_textarea.textContent = string;
            });

            $("h4").click(function(){
                parsed_textarea.textContent = '';

                // http://stackoverflow.com/questions/3955229/remove-all-child-elements-of-a-dom-node-in-javascript
                var myNode = document.getElementById("dom-summary");
                while (myNode.firstChild) {
                    myNode.removeChild(myNode.firstChild);
                }
            });
        });


    </script>

    <style type="text/css">
        .blue { color: blue; }
        .noShow {display: none;}
    </style>
</head>
<body>

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
                <!-- http://stackoverflow.com/questions/23740548/how-to-pass-variables-and-data-from-php-to-javascript -->
                <div id="dom-source" contenteditable="true">
                    <?php
                        echo "<br>";
                        echo "<textarea id=\"Desc\" cols=\"45\" rows=\"30\" wrap=\"soft\" name=\"Desc\">";
                        echo $html;
                        echo "</textarea>";
                    ?>
                </div>
            </td>
            <td>
                <div id="dom-source-parsed">
                    <?php
                    echo "<br>";
                    echo "<textarea id=\"Parsed\" cols=\"45\" rows=\"30\" wrap=\"soft\" name=\"Parsed\">";
                    echo "</textarea>";
                    ?>
                 </div>
            </td>
        </tr>
        <tr>
            <td><h3>Click to show summary.</h3></td>
            <td><h4>Click to clear summary</h4></td>
        </tr>
        <tr>

        </tr>
</table>

<div id="dom-summary">

</div>

</body>
</html>
