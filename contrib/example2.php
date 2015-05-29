<!doctype html>
<title>A simple demo</title>
<link rel="stylesheet" href="../styles/base.css"/>
<body>
<h1>Here be demoes!</h1>
<?php
    require_once "../vendor/autoload.php";
    $chechil = new Chechil\Highlighter(file_get_contents(__FILE__), 'php');
    echo $chechil->parse_code();
?>
</body>
