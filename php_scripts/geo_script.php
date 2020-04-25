<?
session_start();
include "../config.php";
include "authentification.php";

$ip = ip2long($_SERVER['REMOTE_ADDR']);

if($_POST[coords] && $iam_player[p_uniq]){
  mysql_query("INSERT INTO geo (p_uniq,geo_ip,geo_coords,geo_date) VALUES ('$iam_player[p_uniq]','$ip','$_POST[coords]','$time')", $bdconnect) or die(mysql_error());
}
?>
