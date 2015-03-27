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

        // http://stackoverflow.com/questions/142527/highlight-text-inside-of-a-textarea?rq=1
        // the plugin that would do the trick
        (function($){
            $.fn.extend({
                highlight: function() {
                    // the main class
                    var pluginClass = function() {};
                    // init the class
                    // Bootloader
                    pluginClass.prototype.__init = function (element) {
                        try {
                            this.element = element;
                        } catch (err) {
                            this.error(err);
                        }
                    };
                    // centralized error handler
                    pluginClass.prototype.error = function (e) {
                        // manage error and exceptions here
                        //console.info("error!",e);
                    };
                    // Centralized routing function
                    pluginClass.prototype.execute = function (fn, options) {
                        try {
                            options = $.extend({},options);
                            if (typeof(this[fn]) == "function") {
                                var output = this[fn].apply(this, [options]);
                            } else {
                                this.error("undefined_function");
                            }
                        } catch (err) {
                            this.error(err);
                        }
                    };
                    // **********************
                    // Plugin Class starts here
                    // **********************
                    // init the component
                    pluginClass.prototype.init = function (options) {
                        try {
                            // the element's reference ( $("#container") ) is stored into "this.element"
                            var scope                   = this;
                            this.options                = options;

                            // just find the different elements we'll need
                            this.highlighterContainer   = this.element.find('#highlighterContainer');
                            this.inputContainer         = this.element.find('#inputContainer');
                            this.textarea               = this.inputContainer.find('textarea');
                            this.highlighter            = this.highlighterContainer.find('#highlighter');

                            // apply the css
                            this.element.css('position','relative');

                            // place both the highlight container and the textarea container
                            // on the same coordonate to superpose them.
                            this.highlighterContainer.css({
                                'position':         'absolute',
                                'left':             '0',
                                'top':              '0',
                                'border':           '1px dashed #ff0000',
                                'width':            this.options.width,
                                'height':           this.options.height,
                                'cursor':           'text'
                            });
                            this.inputContainer.css({
                                'position':         'absolute',
                                'left':             '0',
                                'top':              '0',
                                'border':           '1px solid #000000'
                            });
                            // now let's make sure the highlit div and the textarea will superpose,
                            // by applying the same font size and stuffs.
                            // the highlighter must have a white text so it will be invisible
                            this.highlighter.css({

                                'padding':          '7px',
                                'color':            '#eeeeee',
                                'background-color': '#ffffff',
                                'margin':           '0px',
                                'font-size':        '11px',
                                'font-family':      '"lucida grande",tahoma,verdana,arial,sans-serif'
                            });
                            // the textarea must have a transparent background so we can see the highlight div behind it
                            this.textarea.css({
                                'background-color': 'transparent',
                                'padding':          '5px',
                                'margin':           '0px',
                                'font-size':        '11px',
                                'width':            this.options.width,
                                'height':           this.options.height,
                                'font-family':      '"lucida grande",tahoma,verdana,arial,sans-serif'
                            });

                            // apply the hooks
                            this.highlighterContainer.bind('click', function() {
                                scope.textarea.focus();
                            });
                            this.textarea.bind('keyup', function() {
                                // when we type in the textarea,
                                // we want the text to be processed and re-injected into the div behind it.
                                scope.applyText($(this).val());
                            });
                        } catch (err) {
                            this.error(err);
                        }
                        return true;
                    };
                    pluginClass.prototype.applyText = function (text) {
                        try {
                            var scope                   = this;

                            // parse the text:
                            // replace all the line braks by <br/>, and all the double spaces by the html version &nbsp;
                            text = this.replaceAll(text,'\n','<br/>');
                            text = this.replaceAll(text,'  ','&nbsp;&nbsp;');

                            // replace the words by a highlighted version of the words
                            for (var i=0;i<this.options.words.length;i++) {
                                text = this.replaceAll(text,this.options.words[i],'<span style="background-color: #D8DFEA;">'+this.options.words[i]+'</span>');
                            }

                            // re-inject the processed text into the div
                            this.highlighter.html(text);

                        } catch (err) {
                            this.error(err);
                        }
                        return true;
                    };
                    // "replace all" function
                    pluginClass.prototype.replaceAll = function(txt, replace, with_this) {
                        return txt.replace(new RegExp(replace, 'g'),with_this);
                    }

                    // don't worry about this part, it's just the required code for the plugin to hadle the methods and stuffs. Not relevant here.
                    //**********************
                    // process
                    var fn;
                    var options;
                    if (arguments.length == 0) {
                        fn = "init";
                        options = {};
                    } else if (arguments.length == 1 && typeof(arguments[0]) == 'object') {
                        fn = "init";
                        options = $.extend({},arguments[0]);
                    } else {
                        fn = arguments[0];
                        options = $.extend({},arguments[1]);
                    }

                    $.each(this, function(idx, item) {
                        // if the component is not yet existing, create it.
                        if ($(item).data('highlightPlugin') == null) {
                            $(item).data('highlightPlugin', new pluginClass());
                            $(item).data('highlightPlugin').__init($(item));
                        }
                        $(item).data('highlightPlugin').execute(fn, options);
                    });
                    return this;
                }
            });

        })(jQuery);

        //
        // http://stackoverflow.com/questions/9250545/javascript-domparser-access-innerhtml-and-other-properties
        /*
         * DOMParser HTML extension
         * 2012-02-02
         *
         * By Eli Grey, http://eligrey.com
         * Public domain.
         * NO WARRANTY EXPRESSED OR IMPLIED. USE AT YOUR OWN RISK.
         */

        /*! @source https://gist.github.com/1129031 */
        /*global document, DOMParser*/

        (function(DOMParser) {
            "use strict";
            var DOMParser_proto = DOMParser.prototype
              , real_parseFromString = DOMParser_proto.parseFromString;

            // Firefox/Opera/IE throw errors on unsupported types
            try {
                // WebKit returns null on unsupported types
                if ((new DOMParser).parseFromString("", "text/html")) {
                    // text/html parsing is natively supported
                    return;
                }
            } catch (ex) {}

            DOMParser_proto.parseFromString = function(markup, type) {
                if (/^\s*text\/html\s*(?:;|$)/i.test(type)) {
                    var doc = document.implementation.createHTMLDocument("")
                      , doc_elt = doc.documentElement
                      , first_elt;

                    doc_elt.innerHTML = markup;
                    first_elt = doc_elt.firstElementChild;

                    if (doc_elt.childElementCount === 1
                        && first_elt.localName.toLowerCase() === "html") {
                        doc.replaceChild(first_elt, doc_elt);
                    }

                    return doc;
                } else {
                    return real_parseFromString.apply(this, arguments);
                }
            };
        }(DOMParser));

        function parseXml(xmlString) {
            // http://www.w3schools.com/dom/dom_parser.asp
            if (window.DOMParser)
            {
                parser=new DOMParser();
                xmlDoc=parser.parseFromString(xmlString,"text/html");
            }
            else // code for IE
            {
                xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
                xmlDoc.async=false;
                xmlDoc.loadXML(xmlString);
            }

            return xmlDoc;
        }

        // Use the function below to walk the DOM
        // http://stackoverflow.com/questions/8747086/most-efficient-way-to-iterate-over-all-dom-elements
        function walkDOM(main) {
            var map = new Object();
            // A reference to create map in javascript
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

        // a convenience method
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
