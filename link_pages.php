<?
if(!$_GET[page]){
  $_GET[page] = "index";
}

if($_GET[page] == index){
  $page = "pages/index.php";
  $css = '<link rel="stylesheet" href="css/index.css" type="text/css" />';
}

if($_GET[page] == "reg" && !$_GET[variable_2]){
  $page = "pages/register_accounts.php";
}
if($_GET[page] == "reg" && preg_match("/[\d]+/",$_GET[variable_2])){
  $page = "pages/register_players.php";
  $css = '<link rel="stylesheet" href="css/register_players.css" type="text/css" />';
}

if($_GET[page] == "game" && preg_match("/[\d]+/", $_GET[variable_2])){
  $page = "pages/game.php";
  $css = '<link rel="stylesheet" href="css/game.css" type="text/css" />';
}

if($_GET[page] == "game" && $_GET[variable_2] == "add" && !$GET[variable_3]){
  $page = "pages/game_add.php";
  $css = '<link rel="stylesheet" href="css/game_add.css" type="text/css" />';
}

if($_GET[page] == "game" && $_GET[variable_2] == "add" && preg_match("/[\d]+/", $_GET[variable_3])){
  $page = "pages/game_edit.php";
  $css = '<link rel="stylesheet" href="css/game_edit.css" type="text/css" />';
}

if($_GET[page] == "game" && $_GET[variable_2] == "watch" && preg_match("/[\d]+/", $_GET[variable_3])){
  $page = "pages/game_watch.php";
  $css = '<link rel="stylesheet" href="css/game_watch.css" type="text/css" />';
}

if($_GET[page] == "profile"){
  $page = "pages/profile.php";
}

if(!$page && !$css){
  $page = "pages/not_found.php";
}
?>
