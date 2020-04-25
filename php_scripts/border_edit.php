<?
session_start();
include "../config.php";
include "authentification.php";

if(($iam_player[admin] || $iam[account_admin]) && $_POST[game] && $_POST[border]){
  mysql_query("UPDATE games SET game_border = '$_POST[border]' WHERE game = '$_POST[game]'",$bdconnect) or die('error 1!');
}
?>
