<?
session_start();
include "../config.php";
include "authentification.php";

if($_POST[detection]){
  $game = mysql_query("SELECT * FROM games WHERE game = '$iam_player[game]'", $bdconnect) or die('die =(');
  $game = mysql_fetch_assoc($game);

  if($iam_player[player_status] == 2 && $iam_player[hide_coords]){
    $detect = mysql_query("SELECT * FROM players WHERE p_uniq = '$_POST[detection]' AND player_status = '3'" ,$bdconnect) or die('error 1');
    $detect = mysql_fetch_assoc($detect);

    $who_detect = $detect[p_uniq];
    $detected = $iam_player[p_uniq];

    if($detect[p_uniq]){
      $WHERE = "detect_list.game = '$iam_player[game]' AND detect_list.who_detect = '$who_detect' AND detect_list.detected = '$detected' AND detect_list.detect_status = '0' AND detect_list.detect_time >= '".($time-180)."'";

      $detect = mysql_query("SELECT detect_list.*, who_detect.who_detect_car_number, detected.detected_car_number, count_rabbits.count_rabbits
        FROM detect_list
        LEFT JOIN (SELECT p_uniq, team_name as who_detect_car_number FROM players) as who_detect ON (who_detect.p_uniq = detect_list.who_detect)
        LEFT JOIN (SELECT p_uniq, team_name as detected_car_number FROM players) as detected ON (detected.p_uniq = detect_list.detected)
        LEFT JOIN (SELECT game, count(*) as count_rabbits FROM players WHERE player_status = '2' AND hide_coords != '' GROUP BY game) as count_rabbits ON (count_rabbits.game = detect_list.game)
        WHERE $WHERE" ,$bdconnect) or die('error 1 '.mysql_error());
      $detect = mysql_fetch_assoc($detect);

      mysql_query("UPDATE detect_list SET detect_status = 1, detect_time = '$time' WHERE $WHERE",$bdconnect) or die('error 2');
      mysql_query("UPDATE players SET player_status = $iam_player[player_status_after_detect] WHERE p_uniq = '$detected'",$bdconnect) or die('error 22');
      mysql_query("UPDATE games SET timer_last_point = '$time' WHERE game = $iam_player[game]",$bdconnect);
      if($detect[count_rabbits] == 1){
        mysql_query("UPDATE players SET bonus_points = (bonus_points + $game[bonus_points_for_last_rabbit]) WHERE p_uniq = '$who_detect'",$bdconnect) or die('error 22');
      }
      $text = $detect[who_detect_car_number]." нашел ".$detect[detected_car_number];
      mysql_query("INSERT INTO chat (game,text,date_send) VALUES ($iam_player[game],'$text',$time);", $bdconnect) or die('error 222 '.mysql_error());
    }

  }
  if($iam_player[player_status] == 3){
    $detect = mysql_query("SELECT * FROM players WHERE p_uniq = '$_POST[detection]' AND player_status = '2' AND hide_coords IS NOT NULL" ,$bdconnect) or die('error 12');
    $detect = mysql_fetch_assoc($detect);

    $who_detect = $iam_player[p_uniq];
    $detected = $detect[p_uniq];

    if($detect[p_uniq]){
      mysql_query("DELETE FROM detect_list WHERE game = '$iam_player[game]' AND who_detect = '$iam_player[p_uniq]' AND detect_status = '0'",$bdconnect) or die('error 11');

      mysql_query("INSERT INTO detect_list (game,who_detect,detected,detect_time,detect_status) VALUES ($iam_player[game],$who_detect,$detected,$time,0)",$bdconnect) or die('error 3 '.mysql_error());
    }
  }
}

?>
