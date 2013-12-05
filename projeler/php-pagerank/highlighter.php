<?php
if (! $path = $_SERVER['PATH_TRANSLATED'] )
die('error: no file');

header("Content-Type: text/html; charset=utf-8");

$html_head = <<<EOD
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="tr">
<head>
<title>PHP PageRank Scripti</title>
<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-9" />
</head>
<body>

EOD;

$html_foot = <<<EOD

</body>
</html>
EOD;

echo $html_head;
highlight_file($path);
echo $html_foot;
?>