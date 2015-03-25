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
    <!-- http://stackoverflow.com/questions/23740548/how-to-pass-variables-and-data-from-php-to-javascript -->
    <script type="text/javascript">
        $(document).ready(function(){
            $("h3").click(function(){
                var source_div = document.getElementById("dom-source");
                var html_textarea = GetElementInsideContainer("dom-source", "Desc");

                parser = new DOMParser();
                doc = parser.parseFromString(html_textarea.textContent, "text/xml");

                // here we do the summary stuff, also need to bind the click of tags with the dom-source (the source code)
                // a rough idea: traverse the html document, for each tag, put it in a hash map, also create a count for
                // each tag. At the end, show the tag/count pairs.

                var hashmap = walkDOM(doc);
                var destination_div = document.getElementById("dom-summary");
                console.log(hashmap);
                var string ='';
                // print out map content
                // https://sunfishempire.wordpress.com/2014/08/19/5-ways-to-use-a-javascript-hashmap/
                //
                // MXU: think add a click function on each tag, upon click, we go to source_div (dom-source), and highlight
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
                        // TBD: parsing the source_div, and highlight the corresponding tag
                        // tried (not working either)
                        // var myNodeList = document.getElementById("dom-source").getElementsByTagName(x);
                        var myNodeList = html_textarea.getElementsByTagName(x);
                        alert(myNodeList.length);
                        for (var i = 0; i < myNodeList.length; ++i) {
                            var item = myNodeList[i];  // Calling myNodeList.item(i) isn't necessary in JavaScript
                            item.style.color="red";
                        }
                    });
                    document.body.appendChild(inputElement);
                }
                destination_div.textContent = string;
            });
        });

        // http://stackoverflow.com/questions/8747086/most-efficient-way-to-iterate-over-all-dom-elements
        function walkDOM(main) {
            var map = new Object();
            // http://stackoverflow.com/questions/4246980/how-to-create-a-simple-map-using-javascript-jquery
            function get(k) {
                return map[k];
            }
            var loop = function(main) {
                do {
                    if(main.nodeType == 1) {
                        if(get(main.tagName) == null) // new element
                            map[main.tagName] = 1;
                        else  // existing element, increase count by 1, and put it back to map
                            map[main.tagName] = get(main.tagName) + 1;
                    }
                    if(main.hasChildNodes())
                        loop(main.firstChild);
                }
                while (main = main.nextSibling);
            }
            loop(main);
            return map;
        }

        // http://stackoverflow.com/questions/7171483/simple-way-to-get-element-by-id-within-a-div-tag
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
            <div id="dom-source" >
            <?php
                echo "<br>";
                echo "<textarea id=\"Desc\" cols=\"45\" rows=\"30\" wrap=\"soft\" name=\"Desc\">";
                echo $html;
                echo "</textarea>";
            ?>
            </div>
        </tr>
        <tr><h3>Click to show summary.</h3></tr>
        <tr>
            <div id="dom-summary">

            </div>
        </tr>
</table>

</body>
</html>
