<?
session_start();
include "../config.php";
include "authentification.php";

if (($iam_player[admin] || $iam[account_admin]) && $_POST[game] && $_POST[p_uniq] && $_POST[p_uniq_do] != '') {
  if($_POST[p_uniq_do] == '2_0'){
    $_POST[p_uniq_do] = '2';
    $hide_coords = ", hide_coords = ''";
  }

  $set = mysql_query("UPDATE players SET player_status = '$_POST[p_uniq_do]' $hide_coords WHERE p_uniq = '$_POST[p_uniq]' AND game='$_POST[game]'", $bdconnect) or die("Ошибка базы данных 1!");
  if(!$set){
    echo "Ошибка базы данных 2!";
  }
}


?>
