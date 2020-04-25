<?
session_start();
include "../config.php";
include "authentification.php";

if($_POST[game] && $_POST[who] == "hunters"){
  $hunters = mysql_query("SELECT players.*, geo.*
                          FROM
                          players JOIN
                          (SELECT p_uniq, geo_ip, geo_coords, MAX(geo_date) as geo_date FROM geo GROUP BY p_uniq) as geo USING (p_uniq)
                          WHERE
                          players.game = '$_POST[game]' AND
                          players.player_status = '3' AND
                          players.p_uniq != '$iam_player[p_uniq]'
                          ", $bdconnect) or die('error 123 '.mysql_error());

  $ar = array();
  while($hunter = mysql_fetch_assoc($hunters)) {
    if($time - $hunter[geo_date] <= 60){
      $ar[] = array('coords'=>$hunter[geo_coords],'time'=>($time - $hunter[geo_date]),'p_uniq'=>$hunter[p_uniq],'team_name'=>$hunter[team_name],'car_number'=>$hunter[car_number]);
    }
  }
  echo preg_replace("/\"(\[[\d]*\.[\d]*\,[\d]*\.[\d]*\])\"/","$1",json_encode($ar,JSON_UNESCAPED_UNICODE));
}

if($_POST[game] && ($iam_player[admin] || $iam[account_admin]) && $_POST[who] == "rabbits"){
  $rabbits = mysql_query("SELECT *
  FROM players
  WHERE game = '$_POST[game]' AND player_status = '2' AND hide_coords != ''", $bdconnect) or die('error 123');
  $array = array();

  while($rabbit = mysql_fetch_assoc($rabbits)) {
      $ar[] = array('coords'=>$rabbit[hide_coords],'p_uniq'=> $rabbit[p_uniq],'team_name'=>$rabbit[team_name],'car_number'=>$rabbit[car_number]);
  }
  echo preg_replace("/\"(\[[\d]*\.[\d]*\,[\d]*\.[\d]*\])\"/","$1",json_encode($ar,JSON_UNESCAPED_UNICODE));
}

if($_POST[game] && $_POST[who] == "finding"){
  $time_60 = $time - 60;
  $runnings = mysql_query("SELECT detect_list.*, players.* FROM detect_list JOIN players ON (detect_list.detected = players.p_uniq) WHERE detect_list.game = '$_POST[game]' && detect_list.detect_time > '$time_60' && detect_list.detect_status = '1'", $bdconnect) or die('error 123 '.mysql_error());

  $ar = array();

  while($running = mysql_fetch_assoc($runnings)) {
    $ar[] = array(
      'coords'=>$running[hide_coords],
      'p_uniq'=>$running[p_uniq],
      'team_name'=>$running[team_name],
      'car_number'=>$running[car_number]
    );
  }
  echo preg_replace("/\"(\[[\d]*\.[\d]*\,[\d]*\.[\d]*\])\"/","$1",json_encode($ar,JSON_UNESCAPED_UNICODE));
}

?>
