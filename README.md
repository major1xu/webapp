# webapp
a web app that fetches a web page, display the source code and tags

the main page is basic_form.html, all the guts are in basic_form.php (both php and JavaScript)

Dependencies:
simple_html_dom.php (this is an open source project, the code can be found here http://sourceforge.net/projects/simplehtmldom/).
jQuery

a demo site:
http://www.stlplace.com/demos/webapp/basic_form.html

html source file for testing:
1) http://www.stlplace.com/demos/hello.html

2) http://docs.vagrantup.com/v2/boxes/base.html
This one still got parse error

Known issues
1) Parser error for some html documents;
2) Highlight the relevant html tags in the original html source;
3) UI issues in general:
e.g., disable the link to click for summary when url can not be found, textarea should be dynamic and scrollable
