<? session_start();
include "config.php";
include "php_scripts/login.php";
include "php_scripts/authentification.php";
include "link_pages.php";
//include "../scripts/statistic_enter.php";
//include "../scripts/functions.php";
?>
<!DOCTYPE html>
<html>
<head>
<title>Hide And Seek</title>
<link rel="shortcut icon" href="images/icon.ico" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name=viewport content="width=device-width, initial-scale=1">
<base href="http<? if($_SERVER['HTTPS']){echo "s";} ?>://<?php echo $_SERVER['HTTP_HOST']; ?>/">
<?=$goto?>
<link rel="stylesheet" href="css/semantic.min.css" type="text/css" />
<link rel="stylesheet" href="css/bootstrap-grid.min.css" type="text/css" />
<link rel="stylesheet" href="css/core.css" type="text/css" />
<? if($css){echo $css;}?>
</head>
<body>
<div class="article dark" id="body">
<? if($page){include $page;}?>
</div>

</body>
</html>
