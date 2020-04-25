<?
session_start();
include "../config.php";
include "authentification.php";

if($_POST[coords]){
  mysql_query("UPDATE players SET hide_coords = '$_POST[coords]' WHERE p_uniq = '$iam_player[p_uniq]'",$bdconnect) or die('error 22');
}

?>
