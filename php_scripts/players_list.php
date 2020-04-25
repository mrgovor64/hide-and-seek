<?
session_start();
include "../config.php";
include "authentification.php";
include "functions.php";

if(preg_match("/[\d]*/",$_POST[game]) && !$_POST[type]){
  $ar = array();
  $players = mysql_query("SELECT * FROM players LEFT JOIN cars_marks USING (car_mark) WHERE game='$_POST[game]' ORDER BY date_reg",$bdconnect) or die('error bd!');
  while($player = mysql_fetch_assoc($players)){
    $ar[] = array(
      'car_mark_url' => $player[car_mark_url],
      'car_model' => $player[car_model],
      'team_name' => $player[team_name]
    );
  }
  echo json_encode($ar, JSON_UNESCAPED_UNICODE);
}

if(preg_match("/[\d]*/",$_POST[game]) && ($iam_player[admin] || $iam[account_admin]) && $_POST[type] == "all"){

  $ar = array();
  $players = mysql_query("SELECT * FROM
    players
    LEFT JOIN cars_marks USING (car_mark)
    LEFT JOIN (SELECT who_detect, count(*) as points FROM detect_list WHERE detect_status = 1 GROUP BY who_detect) as count ON (players.p_uniq = count.who_detect)
    WHERE game='$_POST[game]' ORDER BY date_reg",$bdconnect) or die('error bd!');
  while($player = mysql_fetch_assoc($players)){
    $ar[] = array(
      'p_uniq' => $player[p_uniq],
      'pass' => $player[pass],
      'car_mark_url' => $player[car_mark_url],
      'car_model' => $player[car_model],
      'car_color' => $player[car_color],
      'car_number' => $player[car_number],
      'team_name' => $player[team_name],
      'mobile_number' => $player[mobile_number],
      'player_status' => $player[player_status],
      'hide_coords' => $player[hide_coords],
      'points' => $player[points]
    );
  }
  echo json_encode($ar, JSON_UNESCAPED_UNICODE);
}
?>
