<?
session_start();
include "../config.php";
include "authentification.php";
if(!$functions_include){
  include "functions.php";
}

if(preg_match("/[\d]*/",$_POST[game]) && ($iam_player[admin] || $iam[account_admin])){

  $detects = mysql_query("SELECT
    detect_list.*,
    who_detect.team_name AS who_detect_team_name,
    who_detect_car_mark.car_mark_url AS who_detect_car_mark,
    who_detect.car_model AS who_detect_car_model,
    who_detect.car_number AS who_detect_car_number,
    detect.team_name AS detect_team_name,
    detect_car_mark.car_mark_url AS detect_car_mark,
    detect.car_model AS detect_car_model,
    detect.car_number AS detect_car_number
    FROM
    detect_list
    JOIN players AS who_detect ON (detect_list.who_detect = who_detect.p_uniq)
    LEFT JOIN cars_marks AS who_detect_car_mark ON (who_detect_car_mark.car_mark = who_detect.car_mark)
    JOIN players AS detect ON (detect_list.detected = detect.p_uniq)
    LEFT JOIN cars_marks AS detect_car_mark ON (detect_car_mark.car_mark = detect.car_mark)
    WHERE detect_list.game='$_POST[game]' ORDER BY detect_list.detect_time DESC",$bdconnect) or die('error bd!'.mysql_error());
  $ar = array();
  while($detect = mysql_fetch_assoc($detects)){
    $ar[] = array(
      'who_detect_car_mark' => $detect[who_detect_car_mark],
      'who_detect_car_model' => $detect[who_detect_car_model],
      'who_detect_car_number' => $detect[who_detect_car_number],
      'who_detect_team_name' => $detect[who_detect_team_name],
      'detect_car_mark' => $detect[detect_car_mark],
      'detect_car_model' => $detect[detect_car_model],
      'detect_car_number' => $detect[detect_car_number],
      'detect_team_name' => $detect[detect_team_name],
      'detect_status' => $detect[detect_status],
      'detect_time' => date("H:i:s",$detect[detect_time])
    );
  }
  echo json_encode($ar, JSON_UNESCAPED_UNICODE);
}
?>
