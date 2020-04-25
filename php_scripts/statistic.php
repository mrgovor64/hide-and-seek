<?
session_start();
include "../config.php";
include "authentification.php";

if($_POST[page] == "statistic" && $iam_player[game]){
  $ar = array();
  function write_table($status){
    global $bdconnect, $iam_player, $time, $ar;
    if($status >= 1 && $status <= 3){
      $time_180 = $time - 180;
      $players = mysql_query("SELECT players.*, cars_marks.*, count.points, detect.*, detect_me.who_detect as who_detect_me, detect_me.detect_time as when_detect_me
        FROM
        players
        LEFT JOIN cars_marks USING (car_mark)
        LEFT JOIN (SELECT who_detect, count(*) as points FROM detect_list WHERE detect_status = 1 GROUP BY who_detect) as count ON (players.p_uniq = count.who_detect)
        LEFT JOIN (SELECT * FROM detect_list WHERE who_detect = '$iam_player[p_uniq]' AND detect_time >= '$time_180' AND detect_status = 0) as detect ON (detect.game = players.game AND detect.detected = players.p_uniq)
        LEFT JOIN (SELECT * FROM detect_list WHERE detected = '$iam_player[p_uniq]' AND detect_status = '0' AND detect_time >= '$time_180') as detect_me ON (detect_me.game = players.game AND detect_me.who_detect = players.p_uniq)
        WHERE players.game = '$iam_player[game]' AND players.player_status = '$status'
        ORDER BY (IFNULL(count.points,0) + players.bonus_points) DESC, players.hide_coords DESC, players.date_reg DESC", $bdconnect) or die("11".mysql_error());
      while ($player = mysql_fetch_assoc($players)){
        if($player[p_uniq] == $iam_player[p_uniq]){
          $me = 'me';
        }else{
          $me = '';
        }

        if($status == 1){$q = 'game_out';}
        if($status == 2){$q = 'rabbits';}
        if($status == 3){$q = 'hunters';}

        if($status == 2){
          if($player[hide_coords]){
            $second_cell = "Спрятался";
          }else{
            $second_cell = "Прячется";
          }
        }

        if($status == 3){
          if(!$player[points]){
            $player[points] = 0;
          }
          if($player[bonus_points] > 0){
            $bonus_points = " (+".$player[bonus_points].")";
          }elseif($player[bonus_points] < 0){
            $bonus_points = " (".$player[bonus_points].")";
          }else{
            $bonus_points = "";
          }
          $second_cell = $player[points].$bonus_points;
        }

        if($status == 2 || $status == 3){
          if(!$player[detect_status] && $player[detect_time]){
            $sec = 180 - $time + $player[detect_time];
            $min = floor($sec/60);
            $sec = substr("0".($sec % 60),-2,2);
            $button_time = $min.":".$sec;
          }else{
            if($player[player_status] == 2 && $player[hide_coords] && $iam_player[player_status] == 3 || $iam_player[player_status] == 2 && $player[who_detect_me] == $player[p_uniq]){
              $button_time = '<button class="detection" value="'.$player[p_uniq].'">Нашел</button>';
            }elseif(!$player[hide_coords] && $player[player_status] == 2  && $player[p_uniq] == $iam_player[p_uniq]){
              $button_time = '<button class="button_hide_style">Спрятался</button>';
            }else{
              $button_time = '';
            }
          }
        }

        $ar[$q][] = array(
          'class' => $me,
          'car_mark_url' => $player[car_mark_url],
          'car_model' => $player[car_model],
          'car_number' => $player[car_number],
          'second_cell' => $second_cell,
          'button_time' => $button_time
        );
      }
    }
  }
  write_table(1);
  write_table(2);
  write_table(3);
  $timer = mysql_query("SELECT * FROM games WHERE game = '$iam_player[game]'",$bdconnect) or die("error");
  $timer = mysql_fetch_assoc($timer);
  if($timer[timer_amount] + $timer[timer_last_point] - $time > 0){
    $timer = $timer[timer_amount] + $timer[timer_last_point] - $time;
    $hours = floor($timer/3600);
    if($hours < 10){
      $hours = "0".$hours;
    }
    $minutes = floor($timer/60)%60;
    $minutes = substr("0".$minutes, -2);

    $seconds = $timer % 60;
    $seconds = substr("0".$seconds, -2);

    $timer = "$hours:$minutes:$seconds";
  }else{
    $timer = "00:00:00";
  }
  if($timer){
    $ar[timer] = $timer;
  }

  echo json_encode($ar,JSON_UNESCAPED_UNICODE);
}



?>
